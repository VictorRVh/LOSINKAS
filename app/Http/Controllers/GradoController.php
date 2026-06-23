<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GradoController extends Controller
{
    public function index(Nivel $nivel)
    {
        $grados = Grado::where('nivel_id', $nivel->id)
            ->orderBy('nombre_grado')
            ->get();

        return Inertia::render('Grados/Index', [
            'nivel' => $nivel,
            'grados' => $grados,
        ]);
    }

    public function store(Request $request, Nivel $nivel)
    {
        $request->validate([
            'nombre_grado' => ['required', 'string', 'max:80'],
        ]);

        Grado::create([
            'nivel_id' => $nivel->id,
            'nombre_grado' => $request->nombre_grado,
        ]);

        return back()->with('success', 'Grado creado');
    }

    public function update(Request $request, Grado $grado)
    {
        $request->validate([
            'nombre_grado' => ['required', 'string', 'max:80'],
        ]);

        $grado->update($request->only('nombre_grado'));

        return back()->with('success', 'Grado actualizado');
    }

    public function destroy(Grado $grado)
    {
        $grado->delete();

        return back()->with('success', 'Grado eliminado');
    }
}
