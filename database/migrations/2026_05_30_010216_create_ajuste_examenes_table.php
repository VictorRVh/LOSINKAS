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
        Schema::create('ajuste_examenes', function (Blueprint $table) {
            $table->id();
            $table->string('nro_preguntas', 120)->nullable();
            $table->string('peso', 120)->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);

            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuste_examenes');
    }
};
