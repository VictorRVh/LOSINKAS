<?php

use App\Http\Controllers\AjusteExamenController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CursoExamenController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\GradoAreaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\NotaEstudianteController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\SeccionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('ajuste-examenes', AjusteExamenController::class)
    ->parameters([
        'ajuste-examenes' => 'ajusteExamen'
    ]);
Route::apiResource('cursos', CursoController::class);
Route::apiResource('curso-examenes', CursoExamenController::class);
Route::apiResource('estudiantes', EstudianteController::class)
    ->parameters([
        'estudiantes' => 'estudiante'
    ]);
Route::apiResource('examenes', ExamenController::class)
    ->parameters([
        'examenes' => 'examen'
    ]);
Route::apiResource('grado-areas', GradoAreaController::class);
Route::apiResource('grupos', GrupoController::class);
Route::apiResource('matriculas', MatriculaController::class);
Route::apiResource('niveles', NivelController::class);
Route::apiResource('nota-estudiantes', NotaEstudianteController::class);
Route::apiResource('periodos', PeriodoController::class);
Route::apiResource('secciones', SeccionController::class);
