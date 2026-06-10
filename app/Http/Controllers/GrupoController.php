<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\GradoArea;
use App\Models\Grupo;
use App\Models\Nivel;
use App\Models\Periodo;
use App\Models\Seccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class GrupoController extends Controller
{
    public function index(Request $request): View
    {
        $data = [
            'grupos' => $this->getGrupos($request),
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),

            'niveles' => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
            'grados' => GradoArea::orderBy('nombre_grado')->get(),
            'cursos' => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
        ];

        if ($request->header('HX-Request')) {
            return view('grupos.partials.module', $data);
        }

        return view('grupos.grupos', $data);
    }

    public function seccionesDisponibles(Request $request)
    {
        $secciones = Seccion::query()
            ->whereHas('grupos', function ($q) use ($request) {

                if ($request->filled('periodo_id')) {
                    $q->where('periodo_id', $request->periodo_id);
                }

                if ($request->filled('grado_id')) {
                    $q->where('grado_id', $request->grado_id);
                }
            })
            ->orderBy('nombre_seccion')
            ->get();

        return view('grupos.partials.secciones-options', compact('secciones'));
    }

    public function gradosDisponibles(Request $request)
    {
        $grados = GradoArea::query()
            ->when(
                $request->nivel_id,
                fn($q) =>
                $q->where('nivel_id', $request->nivel_id)
            )
            ->orderBy('nombre_grado')
            ->get();

        return view('grupos.partials.grados-options', compact('grados'));
    }

    public function byCurso(Request $request, Curso $curso): View
    {
        $curso->load(['gradoArea.nivel']);

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $curso);
        }

        return view('grupos.grupos', [
            'curso' => $curso,
            'grupos' => $this->getGrupos($request, $curso),
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
        ]);
    }


    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'periodo_id' => ['required', 'exists:periodos,id'],
            'seccion_id' => ['required', 'exists:secciones,id'],
            'grado_id'   => ['required', 'exists:grado_areas,id'],
            'curso_ids'   => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['exists:cursos,id'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $cursos = Curso::with('gradoArea')
            ->whereIn('id', $validated['curso_ids'])
            ->get();

        foreach ($cursos as $curso) {
            Grupo::create([
                'periodo_id'   => $validated['periodo_id'],
                'seccion_id'   => $validated['seccion_id'],
                'grado_id'     => $validated['grado_id'],
                'curso_id'     => $curso->id,
                'nombre_grupo' => $curso->nombre_curso,
                'activo'       => $request->boolean('activo'),
            ]);
        }

        session()->flash('success', 'Grupo creado correctamente');

        if ($request->header('HX-Request')) {
            $grupos = $this->getGrupos($request);

            return view('grupos.partials.grid', compact('grupos'));
        }

        return redirect()->route('grupos.index');
    }

    public function show(Grupo $grupo): JsonResponse
    {
        return response()->json($grupo->load(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes']));
    }

    public function update(Request $request, Grupo $grupo): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'periodo_id' => ['sometimes', 'integer', 'exists:periodos,id'],
            'curso_id' => ['sometimes', 'integer', 'exists:cursos,id'],
            'seccion_id' => [
                'sometimes',
                'integer',
                'exists:secciones,id',
                Rule::unique('grupos', 'seccion_id')
                    ->ignore($grupo->id)
                    ->where(fn($query) => $query
                        ->where('periodo_id', $request->input('periodo_id', $grupo->periodo_id))
                        ->where('curso_id', $request->input('curso_id', $grupo->curso_id))),
            ],
            'nombre_grupo' => ['sometimes', 'string', 'max:120'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $grupo->update($validated);

        if ($request->wantsJson()) {
            return response()->json($grupo->load(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes']));
        }

        return redirect()
            ->route('grupos.index')
            ->with('status', 'Grupo actualizado correctamente.');
    }

    public function destroy(Request $request, Grupo $grupo): JsonResponse|RedirectResponse
    {
        $grupo->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Grupo eliminado correctamente.',
            ]);
        }

        return redirect()
            ->route('grupos.index')
            ->with('status', 'Grupo eliminado correctamente.');
    }

    private function getGrupos(Request $request, ?Curso $curso = null)
    {
        return Grupo::query()
            ->when(
                $curso,
                fn($q) => $q->where('curso_id', $curso->id)
            )

            ->when(
                $request->filled('periodo_id'),
                fn($q) => $q->where('periodo_id', $request->periodo_id)
            )

            ->when(
                $request->filled('seccion_id'),
                fn($q) => $q->where('seccion_id', $request->seccion_id)
            )

            ->when(
                $request->filled('grado_id'),
                function ($q) use ($request) {
                    $q->whereHas('curso.gradoArea', function ($sub) use ($request) {
                        $sub->where('id', $request->grado_id);
                    });
                }
            )

            ->when(
                $request->filled('nivel_id'),
                function ($q) use ($request) {
                    $q->whereHas('curso.gradoArea.nivel', function ($sub) use ($request) {
                        $sub->where('id', $request->nivel_id);
                    });
                }
            )

            ->with([
                'periodo',
                'curso.gradoArea.nivel',
                'seccion',
            ])
            ->orderBy('nombre_grupo')
            ->paginate(15)
            ->withQueryString();
    }
    private function htmxModule(Request $request, Curso $curso): View
    {
        return view('grupos.partials.module', [
            'curso' => $curso,
            'grupos' => $this->getGrupos($request, $curso),
            'buscar' => $request->string('buscar'),
        ]);
    }
}
