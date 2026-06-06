<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PeriodoController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $periodos = Periodo::query()
            ->with('grupos')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where('nombre_periodo', 'like', "%{$buscar}%");
            })
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json($periodos);
        }

        return view('periodos.periodos', compact('periodos'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nombre_periodo' => ['required', 'string', 'max:80', 'unique:periodos,nombre_periodo'],
        ]);

        $periodo = Periodo::create($validated);

        if ($request->wantsJson()) {
            return response()->json($periodo->load('grupos'), 201);
        }

        return redirect()
            ->route('periodos.index')
            ->with('status', 'Periodo creado correctamente.');
    }

    public function show(Periodo $periodo): JsonResponse
    {
        return response()->json($periodo->load('grupos'));
    }

    public function update(Request $request, Periodo $periodo): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'nombre_periodo' => ['sometimes', 'string', 'max:80', Rule::unique('periodos', 'nombre_periodo')->ignore($periodo->id)],
        ]);

        $periodo->update($validated);

        if ($request->wantsJson()) {
            return response()->json($periodo->load('grupos'));
        }

        return redirect()
            ->route('periodos.index')
            ->with('status', 'Periodo actualizado correctamente.');
    }

    public function destroy(Request $request, Periodo $periodo): JsonResponse|RedirectResponse
    {
        $periodo->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Periodo eliminado correctamente.',
            ]);
        }

        return redirect()
            ->route('periodos.index')
            ->with('status', 'Periodo eliminado correctamente.');
    }
}