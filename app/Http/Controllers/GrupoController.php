<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\GradoArea;
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
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index(Request $request): View
    {
        $grados = $request->filled('nivel_id')
            ? GradoArea::where('nivel_id', $request->nivel_id)
            ->orderBy('nombre_grado')
            ->get()
            : collect();

        $secciones_filtro = (
            $request->filled('periodo_id') && $request->filled('grado_id')
        )
            ? Seccion::whereHas('padreGrupos', function ($q) use ($request) {
                $q->where('periodo_id', $request->periodo_id)
                    ->where('grado_id', $request->grado_id);
            })->orderBy('nombre_seccion')->get()
            : collect();

        $data = [
            'grupos' => $this->getGrupos($request),

            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),

            'niveles' => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
            'grados' => GradoArea::orderBy('nombre_grado')->get(),
            'cursos' => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),

            'grados_filtro' => $grados,
            'secciones_filtro' => $secciones_filtro,

            'filtros' => $request->only([
                'periodo_id',
                'nivel_id',
                'grado_id',
                'seccion_id'
            ]),
        ];

        if ($request->header('HX-Request')) {
            return view('grupos.partials.module', $data);
        }

        return view('grupos.grupos', $data);
    }

    public function seccionesDisponibles(Request $request)
    {
        // Si no están ambos, no buscar
        if (!$request->filled('periodo_id') || !$request->filled('grado_id')) {
            return view('grupos.partials.secciones-options', ['secciones' => collect()]);
        }

        $secciones = Seccion::whereHas('padreGrupos', function ($q) use ($request) {
            $q->where('periodo_id', $request->periodo_id)
                ->where('grado_id', $request->grado_id);
        })
            ->orderBy('nombre_seccion')
            ->get();

        return view('grupos.partials.secciones-options', compact('secciones'));
    }

    public function gradosDisponibles(Request $request)
    {
        $grados = GradoArea::query()
            ->when(
                $request->nivel_id,
                fn($q) =>
                $q->where('nivel_id', $request->nivel_id)
            )
            ->orderBy('nombre_grado')
            ->get();

        return view('grupos.partials.grados-options', compact('grados'));
    }

    public function byCurso(Request $request, Curso $curso): View
    {
        $curso->load(['gradoArea.nivel']);

        if ($request->header('HX-Request')) {
            return $this->htmxModule($request, $curso);
        }

        return view('grupos.grupos', [
            'curso' => $curso,
            'grupos' => $this->getGrupos($request, $curso),
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nivel_id'    => ['required', 'exists:niveles,id'],
            'grado_id'    => ['required', 'exists:grado_areas,id'],
            'periodo_id'  => ['required', 'exists:periodos,id'],
            'seccion_id'  => ['required', 'exists:secciones,id'],
            'curso_ids'   => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['exists:cursos,id'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {

            // 1️⃣ Crear PADRE
            $padreGrupo = PadreGrupo::create([
                'periodo_id' => $validated['periodo_id'],
                'grado_id'   => $validated['grado_id'],
                'seccion_id' => $validated['seccion_id'],
            ]);

            // 2️⃣ Crear GRUPOS por curso
            $cursos = Curso::whereIn('id', $validated['curso_ids'])->get();

            foreach ($cursos as $curso) {
                Grupo::create([
                    'padre_id'     => $padreGrupo->id,
                    'curso_id'     => $curso->id,
                    'nombre_grupo' => $curso->nombre_curso,
                    'activo'       => $request->boolean('activo'),
                ]);
            }
        });

        session()->flash('success', 'Grupos creados correctamente');

        // ✅ RESPUESTA HTMX (evita 302)
        if ($request->header('HX-Request')) {
            $cleanRequest = new Request();

            return view('grupos.partials.module', [
                'grupos'           => $this->getGrupos($cleanRequest),
                'periodos'         => Periodo::orderBy('nombre_periodo')->get(),
                'secciones'        => Seccion::orderBy('nombre_seccion')->get(),
                'niveles'          => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
                'grados'           => GradoArea::orderBy('nombre_grado')->get(),
                'cursos'           => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
                'grados_filtro'    => collect(),
                'secciones_filtro' => collect(),
                'filtros'          => [],
            ]);
        }

        return redirect()->route('grupos.index');
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

    public function update(Request $request, Grupo $grupo): JsonResponse|RedirectResponse|View
    {
        $validated = $request->validate([
            'periodo_id'  => ['required', 'integer', 'exists:periodos,id'],
            'grado_id'    => ['required', 'integer', 'exists:grado_areas,id'],
            'seccion_id'  => [
                'required',
                'integer',
                'exists:secciones,id',
            ],
            'curso_ids'   => ['required', 'array', 'min:1'],
            'curso_ids.*' => ['exists:cursos,id'],
            'activo'      => ['nullable', 'boolean'],
        ]);

        // En edit solo se edita un curso (el primero seleccionado)
        $grupo->padre->update([
            'periodo_id' => $validated['periodo_id'],
            'seccion_id' => $validated['seccion_id'],
            'grado_id'   => $validated['grado_id'],
        ]);

        $grupo->update([
            'curso_id' => $validated['curso_ids'][0],
            'activo'   => $request->boolean('activo'),
        ]);

        session()->flash('success', 'Grupo actualizado correctamente.');

        if ($request->header('HX-Request')) {
            $cleanRequest = new \Illuminate\Http\Request();

            $data = [
                'grupos'           => $this->getGrupos($cleanRequest),
                'periodos'         => Periodo::orderBy('nombre_periodo')->get(),
                'secciones'        => Seccion::orderBy('nombre_seccion')->get(),
                'niveles'          => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
                'grados'           => GradoArea::orderBy('nombre_grado')->get(),
                'cursos'           => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
                'grados_filtro'    => collect(),
                'secciones_filtro' => collect(),
                'filtros'          => [],
            ];

            return view('grupos.partials.module', $data);
        }

        return redirect()->route('grupos.index')->with('status', 'Grupo actualizado correctamente.');
    }

    // En GrupoController — agrega este método
    // GrupoController — método edit()
    public function edit(Request $request, Grupo $grupo): View
    {
        return view('components.grupos.form', [
            'grupo'    => $grupo,
            'niveles'  => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
            'cursos'   => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
            'action'   => route('grupos.update', $grupo),
            'method'     => 'PATCH',
            'buttonText' => 'Guardar Cambios',
        ]);
    }

    public function destroy(Request $request, Grupo $grupo): JsonResponse|RedirectResponse|View
    {
        $grupo->delete();

        session()->flash('success', 'Grupo eliminado correctamente.');

        if ($request->header('HX-Request')) {
            $cleanRequest = new \Illuminate\Http\Request();

            $data = [
                'grupos'           => $this->getGrupos($cleanRequest),
                'periodos'         => Periodo::orderBy('nombre_periodo')->get(),
                'secciones'        => Seccion::orderBy('nombre_seccion')->get(),
                'niveles'          => Nivel::with('gradoAreas')->orderBy('nombre_nivel')->get(),
                'grados'           => GradoArea::orderBy('nombre_grado')->get(),
                'cursos'           => Curso::with('gradoArea')->orderBy('nombre_curso')->get(),
                'grados_filtro'    => collect(),
                'secciones_filtro' => collect(),
                'filtros'          => [],
            ];

            return view('grupos.partials.module', $data);
        }

        return redirect()->route('grupos.index')->with('status', 'Grupo eliminado correctamente.');
    }

    private function getGrupos(Request $request, ?Curso $curso = null)
    {
        return Grupo::query()
            ->when(
                $curso,
                fn($q) => $q->where('curso_id', $curso->id)
            )

            ->when(
                $request->filled('periodo_id'),
                fn($q) => $q->whereHas(
                    'padre',
                    fn($p) =>
                    $p->where('periodo_id', $request->periodo_id)
                )
            )

            ->when(
                $request->filled('seccion_id'),
                fn($q) => $q->whereHas(
                    'padre',
                    fn($p) =>
                    $p->where('seccion_id', $request->seccion_id)
                )
            )

            ->when(
                $request->filled('grado_id'),
                fn($q) => $q->whereHas(
                    'padre',
                    fn($p) =>
                    $p->where('grado_id', $request->grado_id)
                )
            )

            ->when(
                $request->filled('nivel_id'),
                fn($q) => $q->whereHas(
                    'curso.gradoArea.nivel',
                    fn($n) => $n->where('id', $request->nivel_id)
                )
            )

            ->with([
                'padre.periodo',
                'padre.seccion',
                'padre.grado',
                'curso.gradoArea.nivel',
            ])
            ->orderBy('activo')
            ->paginate(15)
            ->withQueryString();
    }

    private function htmxModule(Request $request, Curso $curso): View
    {
        return view('grupos.partials.module', [
            'curso' => $curso,
            'grupos' => $this->getGrupos($request, $curso),
            'buscar' => $request->string('buscar'),
        ]);
    }
}
