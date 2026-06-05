<?php

namespace App\Http\Controllers;

use App\Models\GradoArea;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradoAreaController extends Controller
{
    public function byNivel(Nivel $nivel): View
    {
        $gradoAreas = GradoArea::query()
            ->where('nivel_id', $nivel->id)
            ->with(['cursos', 'examenes'])
            ->orderBy('nombre_grado')
            ->paginate(15);

        return view('niveles.gradoAreas.gradosAreas', [
            'nivel' => $nivel,
            'gradoAreas' => $gradoAreas,
        ]);
    }

    public function store(Request $request, Nivel $nivel): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_grado' => [
                'required',
                'string',
                'max:80',
                Rule::unique('grado_areas', 'nombre_grado')
                    ->where(fn($query) => $query->where('nivel_id', $nivel->id)),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $validated['nivel_id'] = $nivel->id;
        $validated['activo'] = $request->boolean('activo');

        GradoArea::create($validated);

        return redirect()
            ->route('niveles.grado-areas', $nivel)
            ->with('status', 'Grado creado correctamente.');
    }

    public function update(Request $request, GradoArea $gradoArea): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_grado' => [
                'required',
                'string',
                'max:80',
                Rule::unique('grado_areas', 'nombre_grado')
                    ->ignore($gradoArea->id)
                    ->where(fn($query) => $query->where('nivel_id', $gradoArea->nivel_id)),
            ],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $validated['activo'] = $request->boolean('activo');

        $gradoArea->update($validated);

        return redirect()
            ->route('niveles.grado-areas', $gradoArea->nivel_id)
            ->with('status', 'Grado actualizado correctamente.');
    }

    public function destroy(GradoArea $gradoArea): RedirectResponse
    {
        $nivelId = $gradoArea->nivel_id;

        $gradoArea->delete();

        return redirect()
            ->route('niveles.grado-areas', $nivelId)
            ->with('status', 'Grado eliminado correctamente.');
    }
}
