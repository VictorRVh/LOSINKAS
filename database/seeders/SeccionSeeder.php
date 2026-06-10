<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionSeeder extends Seeder
{
    public function run(): void
    {
        $secciones = ['U','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($secciones as $seccion) {
            DB::table('secciones')->insert([
                'nombre_seccion' => $seccion,
                'descripcion' => "Sección {$seccion}",
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}