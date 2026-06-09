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
        Schema::create('grado_areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_grado', 80)->nullable();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);

            $table->foreignId('nivel_id')->constrained('niveles')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grado_areas');
    }
};
