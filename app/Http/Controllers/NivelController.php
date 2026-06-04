<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NivelController extends Controller
{
    public function index(Request $request)
    {
        $niveles = Nivel::query()
            ->with('gradoAreas')
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_nivel', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_nivel')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return view('niveles.nivel', [
            'niveles' => $niveles,
            'buscar' => $request->string('buscar'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_nivel' => ['required', 'string', 'max:80', 'unique:niveles,nombre_nivel'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        Nivel::create($validated);

        return redirect()
            ->route('niveles.index')
            ->with('status', 'Nivel creado correctamente.');
    }

    public function show(Nivel $nivel)
    {
        $nivel->load('gradoAreas');

        return view('niveles.show', [
            'nivel' => $nivel,
        ]);
    }

    public function edit(Nivel $nivel)
    {
        return view('niveles.edit', [
            'nivel' => $nivel,
        ]);
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
            'activo' => ['nullable', 'boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        $nivel->update($validated);

        return redirect()
            ->route('niveles.index')
            ->with('status', 'Nivel actualizado correctamente.');
    }

    public function destroy(Nivel $nivel)
    {
        $nivel->delete();

        return redirect()
            ->route('niveles.index')
            ->with('status', 'Nivel eliminado correctamente.');
    }
}
