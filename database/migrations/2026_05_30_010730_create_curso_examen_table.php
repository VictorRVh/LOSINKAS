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
        Schema::create('curso_examen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnUpdate()->cascadeOnDelete();
            // $table->foreignId('ajuste_examen_id')->nullable()->constrained('ajuste_examenes')->cascadeOnUpdate()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_examen');
    }
};
