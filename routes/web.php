<?php

use App\Http\Controllers\GradoAreaController;
use App\Http\Controllers\NivelController;
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

    //GRADOS
    //  Route::get('/grado-areas/{gradoArea}', [GradoAreaController::class, 'showGradoArea']);
    Route::get('/niveles/{nivel}/grado-areas', [GradoAreaController::class, 'byNivel'])
        ->name('niveles.grado-areas');

    Route::post('/grado-areas', [GradoAreaController::class, 'store'])
        ->name('grado-areas.store');

    Route::put('/grado-areas/{gradoArea}', [GradoAreaController::class, 'update'])
        ->name('grado-areas.update');

    Route::delete('/grado-areas/{gradoArea}', [GradoAreaController::class, 'destroy'])
        ->name('grado-areas.destroy');

    // NIVELES

    Route::post('/niveles', [NivelController::class, 'store'])->name('niveles.store');
    Route::get('/niveles', [NivelController::class, 'index'])->name('niveles.index');
    Route::get('/niveles/{nivel}', [NivelController::class, 'show'])->name('niveles.show');
    Route::patch('/niveles/{nivel}', [NivelController::class, 'update'])->name('niveles.update');
    Route::delete('/niveles/{nivel}', [NivelController::class, 'destroy'])->name('niveles.destroy');
});

require __DIR__ . '/auth.php';
