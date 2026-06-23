<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grupo;
use App\Models\PadreGrupo;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PadreGrupoController extends Controller
{
    public function index(PadreGrupo $padreGrupo)
    {
        $padreGrupo->load([
            'grado',
            'seccion',
            'periodo',
        ]);

        $cursosAsignados = $padreGrupo->grupos()
            ->with('curso')
            ->orderBy('id')
            ->get();

        return Inertia::render('GrupoCursos/Index', [
            'padreGrupo' => $padreGrupo,
            'cursosAsignados' => $cursosAsignados,
        ]);
    }

    public function store(Request $request, PadreGrupo $padreGrupo)
    {
        $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
        ]);

        // Evitar duplicados
        $existe = Grupo::where('padre_id', $padreGrupo->id)
            ->where('curso_id', $request->curso_id)
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'El curso ya está asignado',
            ], 409);
        }

        Grupo::create([
            'padre_id' => $padreGrupo->id,
            'curso_id' => $request->curso_id,
            'activo' => true,
        ]);

        return response()->json([
            'message' => 'Curso agregado correctamente',
        ]);
    }

    public function disponibles(PadreGrupo $padreGrupo)
    {
        $cursosAsignadosIds = $padreGrupo->grupos()
            ->pluck('curso_id');

        $cursosDisponibles = Curso::whereNotIn('id', $cursosAsignadosIds)
            ->orderBy('nombre_curso')
            ->get();

        return response()->json($cursosDisponibles);
    }

    // PadreGrupoCursoController
    public function asignadosJson(PadreGrupo $padreGrupo)
    {
        return $padreGrupo->grupos()
            ->with('curso')
            ->orderBy('id')
            ->get();
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return response()->json([
            'message' => 'Curso eliminado correctamente',
        ]);
    }
}
