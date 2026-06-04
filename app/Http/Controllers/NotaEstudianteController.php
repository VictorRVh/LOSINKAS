<?php

namespace App\Http\Controllers;

use App\Models\NotaEstudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotaEstudianteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notaEstudiantes = NotaEstudiante::query()
            ->with(['estudiante', 'grupo', 'cursoExamen'])
            ->when($request->filled('estudiante_id'), fn ($query) => $query->where('estudiante_id', $request->integer('estudiante_id')))
            ->when($request->filled('grupo_id'), fn ($query) => $query->where('grupo_id', $request->integer('grupo_id')))
            ->when($request->filled('curso_examen_id'), fn ($query) => $query->where('curso_examen_id', $request->integer('curso_examen_id')))
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($notaEstudiantes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'estudiante_id' => [
                'required',
                'integer',
                'exists:estudiantes,id',
                Rule::unique('nota_estudiantes')->where(fn ($query) => $query
                    ->where('grupo_id', $request->input('grupo_id'))
                    ->where('curso_examen_id', $request->input('curso_examen_id'))),
            ],
            'grupo_id' => ['required', 'integer', 'exists:grupos,id'],
            'curso_examen_id' => ['required', 'integer', 'exists:curso_examen,id'],
            'nota' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'respuestas_estudiante' => ['nullable'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $notaEstudiante = NotaEstudiante::create($validated);

        return response()->json($notaEstudiante->load(['estudiante', 'grupo', 'cursoExamen']), 201);
    }

    public function show(NotaEstudiante $notaEstudiante): JsonResponse
    {
        return response()->json($notaEstudiante->load(['estudiante', 'grupo', 'cursoExamen']));
    }

    public function update(Request $request, NotaEstudiante $notaEstudiante): JsonResponse
    {
        $validated = $request->validate([
            'estudiante_id' => [
                'sometimes',
                'integer',
                'exists:estudiantes,id',
                Rule::unique('nota_estudiantes')
                    ->ignore($notaEstudiante->id)
                    ->where(fn ($query) => $query
                        ->where('grupo_id', $request->input('grupo_id', $notaEstudiante->grupo_id))
                        ->where('curso_examen_id', $request->input('curso_examen_id', $notaEstudiante->curso_examen_id))),
            ],
            'grupo_id' => ['sometimes', 'integer', 'exists:grupos,id'],
            'curso_examen_id' => ['sometimes', 'integer', 'exists:curso_examen,id'],
            'nota' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'respuestas_estudiante' => ['nullable'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $notaEstudiante->update($validated);

        return response()->json($notaEstudiante->load(['estudiante', 'grupo', 'cursoExamen']));
    }

    public function destroy(NotaEstudiante $notaEstudiante): JsonResponse
    {
        $notaEstudiante->delete();

        return response()->json([
            'message' => 'Nota del estudiante eliminada correctamente.',
        ]);
    }
}