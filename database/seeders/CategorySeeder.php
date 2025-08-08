<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\V1\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Gastos
            ['name' => 'Comida',        'icon' => '🍔', 'type' => 'expense'],
            ['name' => 'Transporte',    'icon' => '🚌', 'type' => 'expense'],
            ['name' => 'Vivienda',      'icon' => '🏠', 'type' => 'expense'],
            ['name' => 'Servicios',     'icon' => '💡', 'type' => 'expense'],
            ['name' => 'Salud',         'icon' => '💊', 'type' => 'expense'],
            ['name' => 'Entretenimiento','icon' => '🎮', 'type' => 'expense'],
            ['name' => 'Educación',     'icon' => '📚', 'type' => 'expense'],

            // Ingresos
            ['name' => 'Salario',       'icon' => '💼', 'type' => 'income'],
            ['name' => 'Freelance',     'icon' => '🖥️', 'type' => 'income'],
            ['name' => 'Inversiones',   'icon' => '📈', 'type' => 'income'],
            ['name' => 'Otros ingresos','icon' => '💰', 'type' => 'income'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
