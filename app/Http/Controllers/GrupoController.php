<?php

namespace App\Http\Controllers;

use App\Models\Curso;
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
        return view('grupos.grupos', [
            'grupos' => $this->getGrupos($request),
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
            'niveles' => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
            'cursos' => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
        ]);
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
                'curso_id'     => $curso->id,
                'grado_id'     => $request->grado_id,
                'nombre_grupo' => $curso->nombre_curso,
                'activo'       => $request->boolean('activo'),
            ]);
        }

        session()->flash('success', 'Grupo creado correctamente');

        if ($request->header('HX-Request')) {
            return view('grupos.partials.module', [
                'grupos'    => $this->getGrupos($request),
                'niveles'   => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
                'cursos'    => Curso::with('gradoArea')->orderBy('nombre_curso')->get(), 
                'periodos'  => Periodo::orderBy('nombre_periodo')->get(),
                'secciones' => Seccion::orderBy('nombre_seccion')->get(),
            ]);
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
            ->when($curso, fn($q) => $q->where('curso_id', $curso->id))
            ->when(
                $request->filled('periodo_id'),
                fn($q) => $q->where('periodo_id', $request->integer('periodo_id'))
            )
            ->when(
                $request->filled('seccion_id'),
                fn($q) => $q->where('seccion_id', $request->integer('seccion_id'))
            )
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $q->where('nombre_grupo', 'like', "%{$request->buscar}%");
            })
            ->with(['periodo', 'curso', 'seccion'])
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
