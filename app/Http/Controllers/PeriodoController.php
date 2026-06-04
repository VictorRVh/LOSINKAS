<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $periodos = Periodo::query()
            ->with('grupos')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where('nombre_periodo', 'like', "%{$buscar}%");
            })
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($periodos);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre_periodo' => ['required', 'string', 'max:80', 'unique:periodos,nombre_periodo'],
        ]);

        $periodo = Periodo::create($validated);

        return response()->json($periodo->load('grupos'), 201);
    }

    public function show(Periodo $periodo): JsonResponse
    {
        return response()->json($periodo->load('grupos'));
    }

    public function update(Request $request, Periodo $periodo): JsonResponse
    {
        $validated = $request->validate([
            'nombre_periodo' => ['sometimes', 'string', 'max:80', Rule::unique('periodos', 'nombre_periodo')->ignore($periodo->id)],
        ]);

        $periodo->update($validated);

        return response()->json($periodo->load('grupos'));
    }

    public function destroy(Periodo $periodo): JsonResponse
    {
        $periodo->delete();

        return response()->json([
            'message' => 'Periodo eliminado correctamente.',
        ]);
    }
}