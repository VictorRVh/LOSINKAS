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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_grupo', 120)->nullable();
            // $table->string('codigo_qr', 120)->nullable()->unique();
            $table->boolean('activo')->default(true);

            $table->foreignId('periodo_id')->constrained('periodos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('seccion_id')->constrained('secciones')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
