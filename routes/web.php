<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\GradoAreaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // GRADOS

    Route::get('/niveles/{nivel}/grado-areas', [GradoAreaController::class, 'byNivel'])
        ->name('niveles.grado-areas');

    Route::post('/niveles/{nivel}/grado-areas', [GradoAreaController::class, 'store'])
        ->name('niveles.grado-areas.store');

    Route::patch('/grado-areas/{gradoArea}', [GradoAreaController::class, 'update'])
        ->name('grado-areas.update');

    Route::delete('/grado-areas/{gradoArea}', [GradoAreaController::class, 'destroy'])
        ->name('grado-areas.destroy');

    Route::get(
        '/niveles/{nivel}/grado-options',
        [GradoAreaController::class, 'optionsByNivel']
    )->name('niveles.grado-options');
    Route::get(
        '/grupos/secciones-disponibles',
        [GrupoController::class, 'seccionesDisponibles']
    )->name('grupos.secciones-disponibles');

    Route::get('/grupos/grados-disponibles', [GrupoController::class, 'gradosDisponibles'])
        ->name('grupos.grados-disponibles');

    // NIVEL
    Route::get('/niveles', [NivelController::class, 'index'])->name('niveles.index');
    Route::post('/niveles', [NivelController::class, 'store'])->name('niveles.store');
    Route::get('/niveles/{nivel}', [NivelController::class, 'show'])->name('niveles.show');
    Route::patch('/niveles/{nivel}', [NivelController::class, 'update'])->name('niveles.update');
    Route::delete('/niveles/{nivel}', [NivelController::class, 'destroy'])->name('niveles.destroy');


    //CURSOS 
    Route::get('/grado-areas/{gradoArea}/cursos', [CursoController::class, 'byGradoArea'])
        ->name('grado-areas.cursos');

    Route::post('/cursos', [CursoController::class, 'store'])
        ->name('cursos.store');

    Route::patch('/cursos/{curso}', [CursoController::class, 'update'])
        ->name('cursos.update');

    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])
        ->name('cursos.destroy');

    //GRUPOS 
    Route::get('/grupos', [GrupoController::class, 'index'])
        ->name('grupos.index');

    Route::post('/grupos', [GrupoController::class, 'store'])
        ->name('grupos.store');

    Route::patch('/grupos/{grupo}', [GrupoController::class, 'update'])
        ->name('grupos.update');

    Route::delete('/grupos/{grupo}', [GrupoController::class, 'destroy'])
        ->name('grupos.destroy');

    Route::get('grupos/{grupo}/edit', [GrupoController::class, 'edit'])->name('grupos.edit');

    Route::get('/periodos', [PeriodoController::class, 'index'])
        ->name('periodos.index');

    Route::post('/periodos', [PeriodoController::class, 'store'])
        ->name('periodos.store');

    Route::patch('/periodos/{periodo}', [PeriodoController::class, 'update'])
        ->name('periodos.update');

    Route::delete('/periodos/{periodo}', [PeriodoController::class, 'destroy'])
        ->name('periodos.destroy');
    ///////////////MATERICULAS
    Route::prefix('matriculas')->name('matriculas.')->group(function () {

        // Pestaña: Matricular estudiante
        Route::get('/', [MatriculaController::class, 'index'])
            ->name('index');

        Route::post('/', [MatriculaController::class, 'store'])
            ->name('store');

        Route::delete('/{matricula}', [MatriculaController::class, 'destroy'])
            ->name('destroy');


        // Pestaña: Lista por grupos
        Route::get('/grupos', [MatriculaController::class, 'index'])
            ->name('grupos.index');

        Route::get('/grupos/{grupo}', [MatriculaController::class, 'show'])
            ->name('grupos.show');


        // // Pestaña: Estudiantes con reserva
        // Route::get('/reservas', [MatriculaReservaController::class, 'index'])
        //     ->name('reservas.index');
    });

    // ESTUDIANTES

    Route::resource('estudiantes', EstudianteController::class);

    Route::get(
        '/estudiantes/buscar-dni',
        [EstudianteController::class, 'buscarPorDni']
    )->name('estudiantes.buscar-dni');
});

require __DIR__ . '/auth.php';
