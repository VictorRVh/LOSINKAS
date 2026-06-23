<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PadreGrupoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'authUser' => auth()->user(),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware('auth')->group(function () {

    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');


    Route::get('/niveles', [NivelController::class, 'index'])
        ->name('niveles.index');

    Route::post('/niveles', [NivelController::class, 'store'])
        ->name('niveles.store');

    Route::put('/niveles/{nivel}', [NivelController::class, 'update'])
        ->name('niveles.update');

    Route::delete('/niveles/{nivel}', [NivelController::class, 'destroy'])
        ->name('niveles.destroy');

    // RUTAS GRADOS

    Route::get('/niveles/{nivel}/grados', [GradoController::class, 'index'])
        ->name('niveles.grados');

    Route::post('/niveles/{nivel}/grados', [GradoController::class, 'store'])
        ->name('grados.store');

    Route::put('/grados/{grado}', [GradoController::class, 'update'])
        ->name('grados.update');

    Route::delete('/grados/{grado}', [GradoController::class, 'destroy'])
        ->name('grados.destroy');

    //CURSOS 

    Route::get('/grados/{grado}/cursos', [CursoController::class, 'index'])
        ->name('grados.cursos');

    Route::post('/grados/{grado}/cursos', [CursoController::class, 'store'])
        ->name('cursos.store');

    Route::put('/cursos/{curso}', [CursoController::class, 'update'])
        ->name('cursos.update');

    Route::delete('/cursos/{curso}', [CursoController::class, 'destroy'])
        ->name('cursos.destroy');


    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // GRUPOS

    Route::prefix('grupos')->name('grupos.')->group(function () {
        Route::get('/', [GrupoController::class, 'index'])->name('index');
        Route::post('/', [GrupoController::class, 'store'])->name('store');
        Route::put('{padreGrupo}', [GrupoController::class, 'update'])->name('update');
        Route::delete('{padreGrupo}', [GrupoController::class, 'destroy'])->name('destroy');

        Route::get('grados-por-nivel', [GrupoController::class, 'gradosPorNivel'])
            ->name('grados.por-nivel');

        Route::get('cursos-disponibles', [GrupoController::class, 'cursosDisponibles'])
            ->name('cursos.disponibles');

        Route::get('secciones-disponibles', [GrupoController::class, 'seccionesDisponibles'])
            ->name('secciones.disponibles');
    });

    // PERIODOS

    Route::get('/periodos', [PeriodoController::class, 'index'])
        ->name('periodos.index');

    Route::post('/periodos', [PeriodoController::class, 'store'])
        ->name('periodos.store');

    Route::put('/periodos/{periodo}', [PeriodoController::class, 'update'])
        ->name('periodos.update');

    Route::delete('/periodos/{periodo}', [PeriodoController::class, 'destroy'])
        ->name('periodos.destroy');


    // CURSOS DE GRADO Y SECCION (GRUPOS)
    Route::get(
        '/padre-grupos/{padreGrupo}/cursos',
        [PadreGrupoController::class, 'index']
    )->name('padre-grupos.cursos.index');

    Route::get(
        'padre-grupos/{padreGrupo}/cursos-json',
        [PadreGrupoController::class, 'asignadosJson']
    )->name('padre-grupos.cursos.json');

    Route::get(
        '/padre-grupos/{padreGrupo}/cursos-disponibles',
        [PadreGrupoController::class, 'disponibles']
    )->name('padre-grupos.cursos.cursos-disponibles');

    Route::post(
        '/padre-grupos/{padreGrupo}/cursos',
        [PadreGrupoController::class, 'store']
    )->name('padre-grupos.cursos.store');

    Route::delete(
        '/padre-grupos/{grupo}',
        [PadreGrupoController::class, 'destroy']
    )->name('padre-grupos.grupos.destroy');




    // MATRCULAS
    Route::prefix('matriculas')->name('matriculas.')->group(function () {

        // INDEX (layout con submenu)
        Route::get('/', [MatriculaController::class, 'index'])
            ->name('index');

        Route::get('/tab/matricular', [MatriculaController::class, 'tabMatricular'])
            ->name('tab.matricular');

        Route::get('/tab/grupos', [MatriculaController::class, 'tabGrupos'])
            ->name('tab.grupos');

        Route::post('/', [MatriculaController::class, 'store'])->name('store');
        Route::delete('/{matricula}', [MatriculaController::class, 'destroy'])->name('destroy');
    });

});



require __DIR__ . '/auth.php';
