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

class GrupoPadreController extends Controller
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
            'secciones'=> Seccion::orderBy('nombre_seccion')->get(),
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
                'padres' => PadreGrupo::with(['periodo','grado','seccion','grupos.curso'])
                    ->orderByDesc('id')->paginate(10),
            ]);
        }

        return redirect()->route('grupo-padre.index');
    }

    public function destroy(Request $request, PadreGrupo $grupoPadre)
    {
        $grupoPadre->delete();

        session()->flash('success', 'Grupo eliminado');

        if ($request->header('HX-Request')) {
            return view('grupo_padres.partials.table', [
                'padres' => PadreGrupo::with(['periodo','grado','seccion','grupos.curso'])
                    ->orderByDesc('id')->paginate(10),
            ]);
        }

        return back();
    }
}