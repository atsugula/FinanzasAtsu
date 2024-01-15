<?php

namespace Database\Seeders;

use App\Models\V1\TypeDocument;
use Illuminate\Database\Seeder;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeDocument::create([
            'name' => 'CEDULA DE CIUDADANIA',
        ]);

        TypeDocument::create([
            'name' => 'CEDULA DE EXTRANJERIA',
        ]);

        TypeDocument::create([
            'name' => 'NUMERO DE IDENTIFICACION PERSONAL',
        ]);

        TypeDocument::create([
            'name' => 'NUMERO DE IDENTIFICACION TRIBUTARIA',
        ]);

        TypeDocument::create([
            'name' => 'PASAPORTE',
        ]);
    }
}
