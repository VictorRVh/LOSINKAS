<?php

namespace App\Http\Controllers;

use App\Models\GradoArea;
use App\Models\Nivel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GradoAreaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $gradoAreas = GradoArea::query()
            ->with(['nivel', 'cursos', 'examenes'])
            ->when($request->filled('nivel_id'), fn($query) => $query->where('nivel_id', $request->integer('nivel_id')))
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_grado', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_grado')
            ->paginate($request->integer('per_page', 15));

        return response()->json($gradoAreas);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nivel_id' => ['required', 'integer', 'exists:niveles,id'],
            'nombre_grado' => ['required', 'string', 'max:80'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        GradoArea::create($validated);

        return redirect()
            ->route('niveles.grado-areas', $validated['nivel_id'])
            ->with('success', 'Grado creado correctamente');
    }

    public function show(GradoArea $gradoArea): JsonResponse
    {
        return response()->json($gradoArea->load(['nivel', 'cursos', 'examenes']));
    }

    public function update(Request $request, GradoArea $gradoArea): JsonResponse
    {
        $validated = $request->validate([
            'nivel_id' => ['sometimes', 'integer', 'exists:niveles,id'],
            'nombre_grado' => [
                'sometimes',
                'string',
                'max:80',
                Rule::unique('grado_areas', 'nombre_grado')
                    ->ignore($gradoArea->id)
                    ->where(fn($query) => $query->where('nivel_id', $request->input('nivel_id', $gradoArea->nivel_id))),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $gradoArea->update($validated);

        return response()->json($gradoArea->load(['nivel', 'cursos', 'examenes']));
    }

    public function destroy(GradoArea $gradoArea): JsonResponse
    {
        $gradoArea->delete();

        return response()->json([
            'message' => 'Grado o area eliminado correctamente.',
        ]);
    }

    public function byNivel(Nivel $nivel)
    {
        $gradoAreas = GradoArea::where('nivel_id', $nivel->id)
            ->with(['cursos', 'examenes'])
            ->get();

        return view('niveles.gradoAreas.gradosAreas', compact('nivel', 'gradoAreas'));
    }
}
