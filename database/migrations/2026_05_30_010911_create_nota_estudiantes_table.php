<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nota_estudiantes', function (Blueprint $table) {
            $table->id();

            $table->decimal('nota', 5, 2)->nullable();
            // $table->decimal('puntaje_obtenido', 8, 2)->nullable();
            // $table->json('respuestas_estudiante')->nullable();
            $table->string('respuestas_estudiante')->nullable();
            $table->string('observacion')->nullable();

            $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('curso_examen_id')->constrained('curso_examen')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_estudiantes');
    }
};
