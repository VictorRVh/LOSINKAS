<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Inertia\Inertia;

class CursoController extends Controller
{
    public function index(Grado $grado)
    {
        $cursos = Curso::where('grado_id', $grado->id)
            ->orderBy('nombre_curso')
            ->get();

        return Inertia::render('Cursos/Index', [
            'grado' => $grado,
            'cursos' => $cursos,
        ]);
    }

    public function store(Request $request, Grado $grado)
    {
        $request->validate([
            'nombre_curso' => ['required', 'string', 'max:120'],
        ]);

        Curso::create([
            'grado_id' => $grado->id,
            'nombre_curso' => $request->nombre_curso,
        ]);

        return back()->with('success', 'Curso creado');
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nombre_curso' => ['required', 'string', 'max:120'],
        ]);

        $curso->update($request->only('nombre_curso'));

        return back()->with('success', 'Curso actualizado');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return back()->with('success', 'Curso eliminado');
    }
}
