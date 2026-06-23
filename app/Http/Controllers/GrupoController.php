<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Nivel;
use App\Models\PadreGrupo;
use App\Models\Periodo;
use App\Models\Seccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index(Request $request)
    {
        $padres = $this->filtrarPadres($request);

        return Inertia::render('Grupos/Index', [
            'padres' => $padres,
            'periodos' => Periodo::all(),
            'niveles' => Nivel::all(),
            'secciones' => Seccion::all(),
            'filtros' => $request->all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periodo_id' => 'required',
            'grado_id' => 'required',
            'seccion_id' => 'required',
            'cursos' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            // 1. Crear padre grupo
            $padre = PadreGrupo::create([
                'periodo_id' => $request->periodo_id,
                'grado_id' => $request->grado_id,
                'seccion_id' => $request->seccion_id,
                'nombre_grupo' => 'Grupo generado',
            ]);

            // 2. Crear hijos (grupos por curso)
            foreach ($request->cursos as $cursoId) {
                Grupo::create([
                    'padre_id' => $padre->id,
                    'curso_id' => $cursoId,
                    'nombre_grupo' => 'Grupo ' . $cursoId,
                    'activo' => 1,
                ]);
            }
        });

        return back()->with('success', 'Grupo creado correctamente');
    }

    public function show(Grupo $grupo): JsonResponse
    {
        return response()->json(
            $grupo->load([
                'padre.periodo',
                'padre.seccion',
                'padre.grado',
                'curso',
                'matriculas',
                'estudiantes',
                'notaEstudiantes'
            ])
        );
    }

    public function update(Request $request, PadreGrupo $padreGrupo)
    {
        $validated = $request->validate([
            'periodo_id'  => ['required', 'exists:periodos,id'],
            'grado_id'    => ['required', 'exists:grados,id'],
            'seccion_id'  => ['required', 'exists:secciones,id'],
            'curso_ids'   => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['exists:cursos,id'],
        ]);

        DB::transaction(function () use ($padreGrupo, $validated) {

            $padreGrupo->update([
                'periodo_id' => $validated['periodo_id'],
                'grado_id'   => $validated['grado_id'],
                'seccion_id' => $validated['seccion_id'],
            ]);

            // eliminar cursos antiguos
            $padreGrupo->grupos()->delete();

            // recrear cursos
            foreach ($validated['curso_ids'] as $cursoId) {
                Grupo::create([
                    'padre_id' => $padreGrupo->id,
                    'curso_id' => $cursoId,
                    'activo'   => true,
                ]);
            }
        });

        return redirect()
            ->route('grupos.index')
            ->with('success', 'Grupo actualizado correctamente');
    }

    public function destroy(PadreGrupo $padreGrupo)
    {
        $padreGrupo->grupos()->delete();
        $padreGrupo->delete();

        return redirect()
            ->route('grupos.index')
            ->with('success', 'Grupo eliminado correctamente');
    }

    public function gradosPorNivel(Request $request)
    {
        $request->validate([
            'nivel_id' => 'required|exists:niveles,id',
        ]);

        $grados = Grado::where('nivel_id', $request->nivel_id)
            ->orderBy('nombre_grado')
            ->get(['id', 'nombre_grado']);

        return response()->json($grados);
    }

    public function cursosDisponibles(Request $request)
    {
        $gradoId = $request->grado_id;

        if (!$gradoId) {
            return response()->json([]);
        }

        $cursos = Curso::where('grado_id', $gradoId)
            ->where('activo', 1)
            ->orderBy('nombre_curso')
            ->get(['id', 'nombre_curso']);

        return response()->json($cursos);
    }

    public function seccionesDisponibles(Request $request)
    {
        $gradoId = $request->grado_id;
        $periodoId = $request->periodo_id;

        if (!$gradoId || !$periodoId) {
            return response()->json([]);
        }

        // IDs de secciones ya ocupadas
        $ocupadas = PadreGrupo::where('grado_id', $gradoId)
            ->where('periodo_id', $periodoId)
            ->pluck('seccion_id');

        // Secciones libres
        $secciones = Seccion::whereNotIn('id', $ocupadas)
            ->orderBy('nombre_seccion')
            ->get(['id', 'nombre_seccion']);

        return response()->json($secciones);
    }

    private function filtrarPadres(Request $request)
    {
        $query = PadreGrupo::with([
            'grado.nivel',
            'seccion',
            'periodo',
            'grupos.curso'
        ]);

        if ($request->periodo_id) {
            $query->where('periodo_id', $request->periodo_id);
        }

        if ($request->nivel_id) {
            $query->whereHas('grado', function ($q) use ($request) {
                $q->where('nivel_id', $request->nivel_id);
            });
        }

        if ($request->grado_id) {
            $query->where('grado_id', $request->grado_id);
        }

        return $query->get();
    }
}
