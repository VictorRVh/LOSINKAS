<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class NivelController extends Controller
{
    public function index(Request $request)
    {
        $niveles = Nivel::query()
            ->withCount('grado')
            ->when($request->buscar, function ($query, $buscar) {
                $query->where('nombre_nivel', 'like', "%{$buscar}%")
                    ->orWhere('descripcion', 'like', "%{$buscar}%");
            })
            ->orderBy('nombre_nivel')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Niveles/Index', [
            'niveles' => $niveles,
            'filters' => $request->only('buscar'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_nivel' => ['required', 'string', 'max:80', 'unique:niveles,nombre_nivel'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        Nivel::create($validated);

        return redirect()
            ->route('niveles.index')
            ->with('success', 'Nivel creado correctamente.');
    }

    public function update(Request $request, Nivel $nivel)
    {
        $validated = $request->validate([
            'nombre_nivel' => [
                'required',
                'string',
                'max:80',
                Rule::unique('niveles', 'nombre_nivel')->ignore($nivel->id),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        $nivel->update($validated);

        return redirect()
            ->route('niveles.index')
            ->with('success', 'Nivel actualizado correctamente.');
    }

    public function destroy(Nivel $nivel)
    {
        $nivel->delete();

        return redirect()
            ->route('niveles.index')
            ->with('success', 'Nivel eliminado correctamente.');
    }
}
