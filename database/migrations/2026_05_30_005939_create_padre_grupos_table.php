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
        Schema::create('padre_grupos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_grupo', 120)->nullable();

            $table->foreignId('periodo_id')->constrained('periodos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('seccion_id')->constrained('secciones')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('grado_id')->constrained('grado_areas')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padre_grupos');
    }
};
