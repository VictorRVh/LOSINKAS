<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\GradoArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cursos = Curso::query()
            ->with(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo'])
            ->orderBy('nombre_curso')
            ->paginate($request->integer('per_page', 15));

        return response()->json($cursos);
    }

    public function byGradoArea(Request $request, GradoArea $gradoArea): View
    {
        $gradoArea->load('nivel');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $gradoArea);
        }

        return view('niveles.gradoAreas.cursos.cursos', [
            'gradoArea' => $gradoArea,
            'cursos' => $this->getCursos($request, $gradoArea),
        ]);
    }

    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'grado_area_id' => ['required', 'integer', 'exists:grado_areas,id'],
            'nombre_curso' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $curso = Curso::create([
            'grado_area_id' => $validated['grado_area_id'],
            'nombre_curso' => $validated['nombre_curso'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        $gradoArea = GradoArea::with('nivel')->findOrFail($curso->grado_area_id);

        session()->flash('success', 'Curso creado correctamente');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $gradoArea);
        }

        return redirect()
            ->route('grado-areas.cursos', $curso->grado_area_id)
            ->with('success', 'Curso creado correctamente');
    }

    public function show(Curso $curso): JsonResponse
    {
        return response()->json($curso->load(['gradoArea', 'ajusteExamenes', 'cursoExamenes', 'grupo']));
    }

    public function update(Request $request, Curso $curso): RedirectResponse|View
    {
        $validated = $request->validate([
            'nombre_curso' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $curso->update([
            'nombre_curso' => $validated['nombre_curso'],
            'descripcion' => $validated['descripcion'] ?? null,
            'activo' => $request->boolean('activo'),
        ]);

        $gradoArea = GradoArea::with('nivel')->findOrFail($curso->grado_area_id);

        session()->flash('success', 'Curso actualizado correctamente');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $gradoArea);
        }

        return redirect()
            ->route('grado-areas.cursos', $curso->grado_area_id)
            ->with('success', 'Curso actualizado correctamente');
    }

    public function destroy(Request $request, Curso $curso): RedirectResponse|View
    {
        $gradoArea = GradoArea::with('nivel')->findOrFail($curso->grado_area_id);

        $curso->delete();

        session()->flash('success', 'Curso eliminado correctamente');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $gradoArea);
        }

        return redirect()
            ->route('grado-areas.cursos', $gradoArea)
            ->with('success', 'Curso eliminado correctamente');
    }

    private function getCursos(Request $request, GradoArea $gradoArea)
    {
        return Curso::query()
            ->where('grado_area_id', $gradoArea->id)
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_curso', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_curso')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();
    }

    private function htmxModule(Request $request, GradoArea $gradoArea): View
    {
        $gradoArea->load('nivel');

        return view('niveles.gradoAreas.cursos.partials.module', [
            'gradoArea' => $gradoArea,
            'cursos' => $this->getCursos($request, $gradoArea),
            'buscar' => $request->string('buscar'),
        ]);
    }
}
