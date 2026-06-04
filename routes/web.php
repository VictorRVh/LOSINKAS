<?php

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

    Route::get('/usuarios', [UserController::class, 'index'])
        ->name('usuarios');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // Route::post('/niveles', [NivelController::class, 'store'])->name('niveles.store');
    // Route::get('/niveles', [NivelController::class, 'index'])->name('niveles.index');

    Route::resource('niveles', NivelController::class);
});

require __DIR__ . '/auth.php';
