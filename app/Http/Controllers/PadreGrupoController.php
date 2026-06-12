<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Curso;
use App\Models\Periodo;
use App\Models\Nivel;
use App\Models\GradoArea;
use App\Models\PadreGrupo;
use App\Models\Seccion;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class PadreGrupoController extends Controller
{
    public function index(Request $request): View
    {
        return view('grupo_padres.index', [
            'padres'   => PadreGrupo::with(['periodo', 'grado', 'seccion', 'grupos.curso'])
                ->orderByDesc('id')
                ->paginate(10),

            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'niveles'  => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
            'grados'   => GradoArea::orderBy('nombre_grado')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
            'cursos'   => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'periodo_id'  => ['required', 'exists:periodos,id'],
            'grado_id'    => ['required', 'exists:grado_areas,id'],
            'seccion_id'  => ['required', 'exists:secciones,id'],
            'curso_ids'   => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['exists:cursos,id'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {

            $padre = PadreGrupo::create([
                'periodo_id' => $validated['periodo_id'],
                'grado_id'   => $validated['grado_id'],
                'seccion_id' => $validated['seccion_id'],
            ]);

            $cursos = Curso::whereIn('id', $validated['curso_ids'])->get();

            foreach ($cursos as $curso) {
                Grupo::create([
                    'padre_id'     => $padre->id,
                    'curso_id'     => $curso->id,
                    'nombre_grupo' => $curso->nombre_curso,
                    'activo'       => $request->boolean('activo'),
                ]);
            }
        });

        session()->flash('success', 'Grupo creado correctamente');

        if ($request->header('HX-Request')) {
            return view('grupo_padres.partials.table', [
                'padres' => PadreGrupo::with(['periodo', 'grado', 'seccion', 'grupos.curso'])
                    ->orderByDesc('id')->paginate(10),
            ]);
        }

        return redirect()->route('grupo-padre.index');
    }

    public function edit(PadreGrupo $padreGrupo)
    {
        $cursosDisponibles = Curso::whereNotIn(
            'id',
            $padreGrupo->grupos()->pluck('curso_id')
        )->orderBy('nombre_curso')->get();

        return view('padre-grupo.form', [
            'padreGrupo' => $padreGrupo->load('grupos.curso'),
            'cursosDisponibles' => $cursosDisponibles,
        ]);
    }

    public function agregarCurso(Request $request, PadreGrupo $padreGrupo)
    {
        $request->validate([
            'curso_id' => ['required', 'exists:cursos,id'],
        ]);

        // Evitar duplicados
        if ($padreGrupo->grupos()->where('curso_id', $request->curso_id)->exists()) {
            return $this->edit($padreGrupo);
        }

        $curso = Curso::findOrFail($request->curso_id);

        Grupo::create([
            'padre_id'     => $padreGrupo->id,
            'curso_id'     => $curso->id,
            'nombre_grupo' => $curso->nombre_curso,
            'activo'       => true,
        ]);

        return $this->edit($padreGrupo);
    }

    public function quitarCurso(Grupo $grupo)
    {
        $padreGrupoId = $grupo->padre_id;

        $grupo->delete();

        return redirect()
            ->route('padre-grupos.edit', $padreGrupoId);
    }

    public function update(Request $request, PadreGrupo $grupoPadre)
    {
        $validated = $request->validate([
            'curso_ids'   => ['required', 'array'],
            'curso_ids.*' => ['exists:cursos,id'],
        ]);

        DB::transaction(function () use ($grupoPadre, $validated) {

            // Cursos actuales
            $actuales = $grupoPadre->grupos()->pluck('curso_id')->toArray();

            // Cursos nuevos
            $nuevos = $validated['curso_ids'];

            $paraAgregar = array_diff($nuevos, $actuales);

            foreach ($paraAgregar as $cursoId) {
                $curso = Curso::find($cursoId);

                Grupo::create([
                    'padre_id'     => $grupoPadre->id,
                    'curso_id'     => $cursoId,
                    'nombre_grupo' => $curso->nombre_curso,
                    'activo'       => true,
                ]);
            }

            $paraEliminar = array_diff($actuales, $nuevos);

            Grupo::where('padre_id', $grupoPadre->id)
                ->whereIn('curso_id', $paraEliminar)
                ->delete();
        });

        session()->flash('success', 'Cursos actualizados correctamente');

        return redirect()->route('padre-grupos.index');
    }

    public function destroy(Grupo $grupo)
    {
        $padreGrupo = $grupo->padreGrupo;

        $grupo->delete();

        return view('components.padre-grupo.form', [
            'padreGrupo' => $padreGrupo,
            'cursosDisponibles' => Curso::whereNotIn(
                'id',
                $padreGrupo->grupos->pluck('curso_id')
            )->get(),
        ]);
    }
}
