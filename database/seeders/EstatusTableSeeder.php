<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Borra los datos existentes en la tabla
        // DB::table('statuses')->truncate();

        // Inserta datos de ejemplo
        DB::table('statuses')->insert([
            ['name' => 'Pendiente de Aprobación', 'description' => 'El ingreso ha sido registrado pero aún no ha sido aprobado por algún proceso interno (puede ser que no se haya recibido).'],
            ['name' => 'Aprobado', 'description' => 'El ingreso ha sido aprobado y se considera válido.'],
            ['name' => 'Procesado', 'description' => 'El ingreso ha sido procesado correctamente.'],
            ['name' => 'Cancelado', 'description' => 'El ingreso inicialmente registrado ha sido cancelado.'],
            ['name' => 'En Revisión', 'description' => 'El ingreso está siendo revisado para verificar su validez.'],
            ['name' => 'Programado', 'description' => 'Se ha programado un ingreso para un período futuro.'],
            ['name' => 'Rechazado', 'description' => 'El ingreso ha sido revisado y no se ha aprobado por algún motivo.'],
            ['name' => 'Deuda', 'description' => 'Es cuando se hacen prestamos de dinero.'],
            ['name' => 'Proceso de pago', 'description' => 'Para cuando se esta realizando abonos a una deuda.'],
        ]);
    }
}
