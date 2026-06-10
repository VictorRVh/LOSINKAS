<?php

namespace App\Http\Controllers;

use App\Models\GradoArea;
use App\Models\Nivel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradoAreaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $gradoAreas = GradoArea::query()
            ->with(['nivel', 'cursos'])
            ->orderBy('nombre_grado')
            ->paginate($request->integer('per_page', 15));

        return response()->json($gradoAreas);
    }

    public function byNivel(Request $request, Nivel $nivel): View
    {
        $gradoAreas = $this->getGradoAreas($request, $nivel);

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $nivel);
        }

        return view('niveles.gradoAreas.gradosAreas', [
            'nivel' => $nivel,
            'gradoAreas' => $gradoAreas,
        ]);
    }
    public function optionsByNivel(Nivel $nivel)
    {
        $grados = GradoArea::query()
            ->where('nivel_id', $nivel->id)
            ->orderBy('nombre_grado')
            ->get();

        return view('grupos.partials.grado-options', compact('grados'));
    }
    public function store(Request $request, Nivel $nivel): RedirectResponse|View
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

        session()->flash('status', 'Grado creado correctamente.');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $nivel);
        }

        return redirect()
            ->route('niveles.grado-areas', $nivel)
            ->with('status', 'Grado creado correctamente.');
    }

    public function update(Request $request, GradoArea $gradoArea): RedirectResponse|View
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

        $nivel = Nivel::findOrFail($gradoArea->nivel_id);

        session()->flash('status', 'Grado actualizado correctamente.');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $nivel);
        }

        return redirect()
            ->route('niveles.grado-areas', $nivel)
            ->with('status', 'Grado actualizado correctamente.');
    }

    public function destroy(Request $request, GradoArea $gradoArea): RedirectResponse|View
    {
        $nivel = Nivel::findOrFail($gradoArea->nivel_id);

        $gradoArea->delete();

        session()->flash('status', 'Grado eliminado correctamente.');

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $nivel);
        }

        return redirect()
            ->route('niveles.grado-areas', $nivel)
            ->with('status', 'Grado eliminado correctamente.');
    }

    private function getGradoAreas(Request $request, Nivel $nivel)
    {
        return GradoArea::query()
            ->where('nivel_id', $nivel->id)
            ->with(['cursos', 'examenes'])
            ->when($request->filled('buscar'), function ($query) use ($request) {
                $buscar = $request->string('buscar');

                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre_grado', 'like', "%{$buscar}%")
                        ->orWhere('descripcion', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre_grado')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();
    }

    private function htmxModule(Request $request, Nivel $nivel): View
    {
        return view('niveles.gradoAreas.partials.module', [
            'nivel' => $nivel,
            'gradoAreas' => $this->getGradoAreas($request, $nivel),
            'buscar' => $request->string('buscar'),
        ]);
    }
}
