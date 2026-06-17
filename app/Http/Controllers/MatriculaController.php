<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\GradoArea;
use App\Models\Grupo;
use App\Models\Matricula;
use App\Models\Nivel;
use App\Models\PadreGrupo;
use App\Models\Periodo;
use App\Models\Seccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class MatriculaController extends Controller
{
    public function index(): View
    {
        return view(
            'matriculas.index',
            $this->sharedData()
        );
    }


    public function store(Request $request): RedirectResponse|View
    {
        $validated = $request->validate([
            'estudiante_id' => ['nullable', 'integer'],
            'dni' => ['required'],
            'nombres' => ['required'],
            'apellidos' => ['required'],
            'periodo_id' => ['required', 'integer'],
            'grado_id' => ['required', 'integer'],
            'seccion_id' => ['required', 'integer'],
        ]);

        $estudiante = $request->filled('estudiante_id')
            ? Estudiante::findOrFail($request->estudiante_id)
            : Estudiante::firstOrCreate(
                ['dni' => $request->dni],
                [
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                ]
            );

        $grupo = PadreGrupo::where('seccion_id', $request->seccion_id)
            ->where('periodo_id', $request->periodo_id)
            ->where('grado_id', $request->grado_id)
            ->first();

        if (!$grupo) {
            if ($request->header('HX-Request')) {
                return view('matriculas.partials.error', [
                    'message' => 'No se encontró un grupo con esos datos.'
                ]);
            }

            return back()->withErrors(['grupo' => 'No se encontró un grupo con esos datos.']);
        }

        $existe = Matricula::where('estudiante_id', $estudiante->id)
            ->where('aula_id', $grupo->id)
            ->exists();

        if ($existe) {
            if ($request->header('HX-Request')) {
                return view('matriculas.partials.error', [
                    'message' => 'El estudiante ya está matriculado en este grupo.'
                ]);
            }

            return back()->withErrors(['matricula' => 'El estudiante ya está matriculado en este grupo.']);
        }

        Matricula::create([
            'estudiante_id' => $estudiante->id,
            'aula_id' => $grupo->id,
            'fecha' => now()->toDateString(),
            'activo' => 0
        ]);

        // 👇 HTMX RESPONSE
        if ($request->header('HX-Request')) {
            return view('matriculas.partials.success', [
                'message' => 'Matrícula registrada correctamente.'
            ]);
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula registrada correctamente.');
    }

    public function update(Request $request, Matricula $matricula): View|RedirectResponse
    {
        $validated = $request->validate([
            'estudiante_id' => ['sometimes', 'integer', 'exists:estudiantes,id'],
            'grupo_id' => ['sometimes', 'integer', 'exists:grupos,id'],
        ]);

        $matricula->update($validated);

        if ($request->header('HX-Request')) {
            return view('matriculas.partials.module', [
                'matriculas' => $this->getMatriculas($request),
            ] + $this->sharedData());
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula actualizada correctamente.');
    }
    public function seccionesPorGrado(Request $request)
    {
        $secciones = Seccion::query()
            ->whereIn(
                'id',
                PadreGrupo::query()
                    ->where('grado_id', $request->grado_id)
                    ->where('periodo_id', $request->periodo_id)
                    ->pluck('seccion_id')
            )
            ->orderBy('nombre_seccion')
            ->get();

        return view(
            'matriculas.partials.secciones',
            compact('secciones')
        );
    }

    private function sharedData(): array
    {
        return [
            'estudiantes' => Estudiante::orderBy('apellidos')->get(),

            'grupos' => Grupo::with([
                'curso',
                'padre.periodo',
                'padre.seccion',
                'padre.grado.nivel',
            ])
                ->orderBy('nombre_grupo')
                ->get()
                ->map(function ($grupo) {

                    return [
                        'id' => $grupo->id,
                        'nombre_grupo' => $grupo->nombre_grupo,

                        'periodo_id' => $grupo->padre->periodo_id,
                        'grado_id' => $grupo->padre->grado_id,
                        'seccion_id' => $grupo->padre->seccion_id,
                        'nivel_id' => $grupo->padre->grado->nivel_id,

                        'grado' => [
                            'id' => $grupo->padre->grado->id,
                            'nombre_grado' => $grupo->padre->grado->nombre_grado,
                        ],

                        'nivel' => [
                            'id' => $grupo->padre->grado->nivel->id,
                            'nombre_nivel' => $grupo->padre->grado->nivel->nombre_nivel,
                        ],

                        'seccion' => [
                            'id' => $grupo->padre->seccion->id,
                            'nombre_seccion' => $grupo->padre->seccion->nombre_seccion,
                        ],
                    ];
                })
                ->values(),

            'periodos' => Periodo::orderBy('nombre_periodo')->get(),

            'niveles' => Nivel::orderBy('nombre_nivel')->get(),

            'grados' => GradoArea::with('nivel')
                ->orderBy('nombre_grado')
                ->get(),

            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
        ];
    }

    public function destroy(Request $request, Matricula $matricula): View|RedirectResponse
    {
        $matricula->delete();

        if ($request->header('HX-Request')) {
            return view('matriculas.partials.module', [
                'matriculas' => $this->getMatriculas($request),
            ] + $this->sharedData());
        }

        return redirect()
            ->route('matriculas.index')
            ->with('status', 'Matrícula eliminada correctamente.');
    }


    public function tabMatricular(): View
    {
        return view('matriculas.partials.matricular', $this->sharedData());
    }

    public function tabGrupos(Request $request): View
    {
        $gruposPadre = PadreGrupo::query()

            ->when($request->filled('periodo_id'), function ($q) use ($request) {
                $q->where('periodo_id', $request->periodo_id);
            })

            ->when($request->filled('grado_id'), function ($q) use ($request) {
                $q->where('grado_id', $request->grado_id);
            })

            ->when($request->filled('seccion_id'), function ($q) use ($request) {
                $q->where('seccion_id', $request->seccion_id);
            })

            ->with([
                'periodo',
                'grado.nivel',
                'seccion',
                'grupos',
            ])

            ->orderBy('grado_id')
            ->orderBy('seccion_id')

            ->paginate(15)
            ->withQueryString();

        return view('matriculas.partials.grupos', [
            'gruposPadre' => $gruposPadre,

            'periodos' => Periodo::orderBy('nombre_periodo')->get(),
            'grados' => GradoArea::orderBy('nombre_grado')->get(),
            'secciones' => Seccion::orderBy('nombre_seccion')->get(),
            'niveles' => Nivel::orderBy('nombre_nivel')->get(),
        ]);
    }
}
