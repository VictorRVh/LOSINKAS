<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatriculaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $matriculas = Matricula::query()
            ->with(['estudiante', 'grupo'])
            ->when($request->filled('estudiante_id'), fn ($query) => $query->where('estudiante_id', $request->integer('estudiante_id')))
            ->when($request->filled('grupo_id'), fn ($query) => $query->where('grupo_id', $request->integer('grupo_id')))
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->input('estado')))
            // ->orderByDesc('fecha_matricula')
            ->paginate($request->integer('per_page', 15));

        return response()->json($matriculas);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'estudiante_id' => [
                'required',
                'integer',
                'exists:estudiantes,id',
                Rule::unique('matriculas')->where(fn ($query) => $query->where('grupo_id', $request->input('grupo_id'))),
            ],
            'grupo_id' => ['required', 'integer', 'exists:grupos,id'],
        ]);

        $matricula = Matricula::create($validated);

        return response()->json($matricula->load(['estudiante', 'grupo']), 201);
    }

    public function show(Matricula $matricula): JsonResponse
    {
        return response()->json($matricula->load(['estudiante', 'grupo']));
    }

    public function update(Request $request, Matricula $matricula): JsonResponse
    {
        $validated = $request->validate([
            'estudiante_id' => [
                'sometimes',
                'integer',
                'exists:estudiantes,id',
                Rule::unique('matriculas')
                    ->ignore($matricula->id)
                    ->where(fn ($query) => $query->where('grupo_id', $request->input('grupo_id', $matricula->grupo_id))),
            ],
            'grupo_id' => ['sometimes', 'integer', 'exists:grupos,id'],
            // 'fecha_matricula' => ['nullable', 'date'],
        ]);

        $matricula->update($validated);

        return response()->json($matricula->load(['estudiante', 'grupo']));
    }

    public function destroy(Matricula $matricula): JsonResponse
    {
        $matricula->delete();

        return response()->json([
            'message' => 'Matricula eliminada correctamente.',
        ]);
    }
}