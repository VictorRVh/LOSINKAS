<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\GradoArea;
use App\Models\Grupo;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\Seccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MatriculaController extends Controller
{
    public function index(): View
    {
        return view(
            'matriculas.index',
            $this->sharedData()
        );
    }
    public function grupos(Request $request): View
    {
        $estudiantes = Estudiante::query()
            ->when(
                $request->filled('periodo_id'),
                fn($q) => $q->whereHas(
                    'matriculas.grupo',
                    fn($g) => $g->where('periodo_id', $request->periodo_id)
                )
            )
            ->when(
                $request->filled('grado_id'),
                fn($q) => $q->whereHas(
                    'matriculas.grupo',
                    fn($g) => $g->where('grado_id', $request->grado_id)
                )
            )
            ->when(
                $request->filled('seccion_id'),
                fn($q) => $q->whereHas(
                    'matriculas.grupo',
                    fn($g) => $g->where('seccion_id', $request->seccion_id)
                )
            )
            ->paginate(15)
            ->withQueryString();

        return view('matriculas.partials.grupos', [
            'estudiantes' => $estudiantes,
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'grados' => GradoArea::orderBy('nombre_grado')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
        ]);
    }
    private function sharedData()
    {
        return [
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),

            'grupos' => Grupo::with([
                'curso',
                'seccion',
                'periodo'
            ])->get(),

            'periodos' => Periodo::orderBy('nombre_periodo')->get(),

            'niveles' => Nivel::orderBy('nombre_nivel')->get(),

            'grados' => GradoArea::orderBy('nombre_grado')->get(),

            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
        ];
    }

    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'estudiante_id' => ['required', 'integer', 'exists:estudiantes,id'],
            'grupo_ids' => ['required', 'array'],
            'grupo_ids.*' => ['integer', 'exists:grupos,id'],
        ]);

        foreach ($validated['grupo_ids'] as $grupoId) {
            Matricula::firstOrCreate([
                'estudiante_id' => $validated['estudiante_id'],
                'grupo_id' => $grupoId,
            ]);
        }

        if ($request->header('HX-Request')) {
            return view('matriculas.partials.module', [
                'matriculas' => $this->getMatriculas($request),
            ] + $this->sharedData());
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula registrada correctamente.');
    }

    public function update(Request $request, Matricula $matricula): View|RedirectResponse
    {
        $validated = $request->validate([
            'estudiante_id' => ['sometimes', 'integer', 'exists:estudiantes,id'],
            'grupo_id' => ['sometimes', 'integer', 'exists:grupos,id'],
        ]);

        $matricula->update($validated);

        if ($request->header('HX-Request')) {
            return view('matriculas.partials.module', [
                'matriculas' => $this->getMatriculas($request),
            ] + $this->sharedData());
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula actualizada correctamente.');
    }

    public function destroy(Request $request, Matricula $matricula): View|RedirectResponse
    {
        $matricula->delete();

        if ($request->header('HX-Request')) {
            return view('matriculas.partials.module', [
                'matriculas' => $this->getMatriculas($request),
            ] + $this->sharedData());
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula eliminada correctamente.');
    }

    public function previewGrupos(Request $request)
    {
        $validated = $request->validate([
            'periodo_id' => ['required', 'integer'],
            'grado_id' => ['required', 'integer'],
            'seccion_id' => ['required', 'integer'],
        ]);

        $grupos = Grupo::with(['curso'])
            ->where('periodo_id', $validated['periodo_id'])
            ->where('grado_id', $validated['grado_id'])
            ->where('seccion_id', $validated['seccion_id'])
            ->get();

        return view('matriculas.partials.grupos-preview', [
            'grupos' => $grupos
        ]);
    }

    private function getMatriculas(Request $request)
    {
        return Matricula::query()
            ->with(['estudiante', 'grupo.curso', 'grupo.seccion', 'grupo.periodo'])
            ->when(
                $request->filled('estudiante_id'),
                fn($q) => $q->where('estudiante_id', $request->integer('estudiante_id'))
            )
            ->when(
                $request->filled('grupo_id'),
                fn($q) => $q->where('grupo_id', $request->integer('grupo_id'))
            )
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();
    }

    // private function sharedData()
    // {
    //     return [
    //         'estudiantes' => Estudiante::orderBy('apellidos')->get(),

    //         'grupos' => Grupo::with(['curso', 'seccion', 'periodo'])
    //             ->orderBy('nombre_grupo')
    //             ->get(),

    //         'periodos' => Periodo::orderBy('nombre_periodo')->get(),

    //         'grados' => GradoArea::orderBy('nombre_grado')->get(),

    //         'secciones' => Seccion::orderBy('nombre_seccion')->get(),
    //     ];
    // }
}
