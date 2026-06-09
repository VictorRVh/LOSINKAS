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
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_examen', 150)->nullable();
            $table->string('numero_examen', 150)->nullable();
            $table->string('descripcion')->nullable();
            $table->date('fecha_examen')->nullable();
            // $table->json('clave_respuestas')->nullable();
            $table->string('clave_respuestas')->nullable();
            $table->boolean('activo')->default(true);

            $table->foreignId('grado_area_id')->constrained('grado_areas')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
