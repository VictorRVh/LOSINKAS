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
    public function index(Request $request): View|JsonResponse
    {
        $query = Grupo::query()
            ->with(['periodo', 'curso', 'seccion', 'matriculas', 'estudiantes', 'notaEstudiantes'])
            ->when($request->filled('periodo_id'), fn ($query) => $query->where('periodo_id', $request->integer('periodo_id')))
            ->when($request->filled('curso_id'), fn ($query) => $query->where('curso_id', $request->integer('curso_id')))
            ->when($request->filled('seccion_id'), fn ($query) => $query->where('seccion_id', $request->integer('seccion_id')))
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where('nombre_grupo', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre_grupo');

        if ($request->wantsJson()) {
            $grupos = $query->paginate($request->integer('per_page', 15));
            return response()->json($grupos);
        }

        $grupos = $query->paginate(15);
        $periodos = Periodo::orderBy('nombre_periodo')->get();
        $cursos = Curso::orderBy('nombre_curso')->get();
        $secciones = Seccion::orderBy('nombre_seccion')->get();
        $niveles = Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get();

        // Prepare simple arrays for the frontend
        $nivelesForJs = $niveles->map(function ($n) {
            return [
                'id' => $n->id,
                'nombre_nivel' => $n->nombre_nivel,
                'gradoAreas' => $n->gradoAreas->map(fn($g) => ['id' => $g->id, 'nombre_grado' => $g->nombre_grado])->values(),
            ];
        });

        return view('grupos.grupos', compact('grupos', 'periodos', 'cursos', 'secciones', 'nivelesForJs'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'periodo_id' => ['required', 'integer', 'exists:periodos,id'],
            'seccion_id' => ['required', 'integer', 'exists:secciones,id'],
            'curso_ids' => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['integer', 'exists:cursos,id'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $periodoId = $validated['periodo_id'];
        $seccionId = $validated['seccion_id'];
        $activo = $request->boolean('activo');

        $created = [];

        foreach ($validated['curso_ids'] as $cursoId) {
            // Skip if group already exists for same periodo/seccion/curso
            $exists = Grupo::where('periodo_id', $periodoId)
                ->where('seccion_id', $seccionId)
                ->where('curso_id', $cursoId)
                ->exists();

            if ($exists) {
                continue;
            }

            $curso = Curso::find($cursoId);

            $created[] = Grupo::create([
                'periodo_id' => $periodoId,
                'seccion_id' => $seccionId,
                'curso_id' => $cursoId,
                'nombre_grupo' => $curso?->nombre_curso ?? null,
                'activo' => $activo,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json($created, 201);
        }

        return redirect()
            ->route('grupos.index')
            ->with('status', count($created) . ' grupos creados correctamente.');
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
                    ->where(fn ($query) => $query
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
}