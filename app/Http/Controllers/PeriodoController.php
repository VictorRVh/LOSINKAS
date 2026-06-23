<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class PeriodoController extends Controller
{
    public function index(Request $request)
    {
        $periodos = Periodo::query()
            ->when($request->buscar, function ($query, $buscar) {
                $query->where('nombre_periodo', 'like', "%{$buscar}%");
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Periodos/Index', [
            'periodos' => $periodos,
            'filters' => $request->only('buscar'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_periodo' => [
                'required',
                'string',
                'max:80',
                'unique:periodos,nombre_periodo',
            ],
            'activo' => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        Periodo::create($validated);

        return redirect()
            ->route('periodos.index')
            ->with('success', 'Periodo creado correctamente.');
    }

    public function update(Request $request, Periodo $periodo)
    {
        $validated = $request->validate([
            'nombre_periodo' => [
                'required',
                'string',
                'max:80',
                Rule::unique('periodos', 'nombre_periodo')->ignore($periodo->id),
            ],
            'activo' => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        $periodo->update($validated);

        return redirect()
            ->route('periodos.index')
            ->with('success', 'Periodo actualizado correctamente.');
    }

    public function destroy(Periodo $periodo)
    {
        $periodo->delete();

        return redirect()
            ->route('periodos.index')
            ->with('success', 'Periodo eliminado correctamente.');
    }
}
