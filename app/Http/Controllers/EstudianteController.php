<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $estudiantes = Estudiante::query()
            ->with(['matriculas', 'grupos', 'notaEstudiantes'])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('dni', 'like', "%{$buscar}%")
                        ->orWhere('nombres', 'like', "%{$buscar}%")
                        ->orWhere('apellidos', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate($request->integer('per_page', 15));

        return response()->json($estudiantes);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['nullable', 'string', 'max:20', 'unique:estudiantes,dni'],
            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150', 'unique:estudiantes,email'],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $estudiante = Estudiante::create($validated);

        return response()->json($estudiante->load(['matriculas', 'grupos', 'notaEstudiantes']), 201);
    }

    public function show(Estudiante $estudiante): JsonResponse
    {
        return response()->json($estudiante->load(['matriculas', 'grupos', 'notaEstudiantes']));
    }

    public function update(Request $request, Estudiante $estudiante): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['nullable', 'string', 'max:20', Rule::unique('estudiantes', 'dni')->ignore($estudiante->id)],
            'nombres' => ['sometimes', 'string', 'max:120'],
            'apellidos' => ['sometimes', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150', Rule::unique('estudiantes', 'email')->ignore($estudiante->id)],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $estudiante->update($validated);

        return response()->json($estudiante->load(['matriculas', 'grupos', 'notaEstudiantes']));
    }

    public function destroy(Estudiante $estudiante): JsonResponse
    {
        $estudiante->delete();

        return response()->json([
            'message' => 'Estudiante eliminado correctamente.',
        ]);
    }
}