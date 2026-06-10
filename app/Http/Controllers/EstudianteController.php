<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function index(Request $request)
    {
        $estudiantes = Estudiante::query()

            ->when($request->filled('buscar'), function ($query) use ($request) {

                $buscar = $request->buscar;

                $query->where(function ($q) use ($buscar) {
                    $q->where('dni', 'like', "%{$buscar}%")
                        ->orWhere('nombres', 'like', "%{$buscar}%")
                        ->orWhere('apellidos', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%");
                });
            })

            ->orderBy('apellidos')
            ->orderBy('nombres')
            ->paginate(15);

        return view('Estudiantes.estudiantes', [
            'estudiantes' => $estudiantes,
        ]);
    }

    public function create()
    {
        return view('estudiantes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => ['nullable', 'string', 'max:20', 'unique:estudiantes,dni'],
            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150', 'unique:estudiantes,email'],
        ]);

        Estudiante::create($validated);

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante creado correctamente.');
    }

    public function show(Estudiante $estudiante)
    {
        return view(
            'estudiantes.show',
            compact('estudiante')
        );
    }

    public function edit(Estudiante $estudiante)
    {
        return view(
            'estudiantes.edit',
            compact('estudiante')
        );
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'dni' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('estudiantes', 'dni')
                    ->ignore($estudiante->id)
            ],

            'nombres' => ['required', 'string', 'max:120'],
            'apellidos' => ['required', 'string', 'max:120'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefono' => ['nullable', 'string', 'max:30'],

            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('estudiantes', 'email')
                    ->ignore($estudiante->id)
            ],
        ]);

        $estudiante->update($validated);

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante actualizado correctamente.');
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();

        return redirect()
            ->route('estudiantes.index')
            ->with('success', 'Estudiante eliminado correctamente.');
    }

    public function buscarPorDni(Request $request)
    {
        $dni = $request->dni;

        $estudiante = Estudiante::where('dni', $dni)->first();

        return view(
            'estudiantes.partials.resultado-dni',
            compact('estudiante')
        );
    }
}
