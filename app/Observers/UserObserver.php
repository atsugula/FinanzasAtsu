<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    public function created(User $user): void
    {
        DB::transaction(function () use ($user) {

            // Settings (1:1)
            UserSetting::firstOrCreate(
                ['user_id' => $user->id],
                ['currency' => 'COP', 'month_start_day' => 1]
            );

            // Cuentas por defecto
            $defaultAccounts = [
                ['name' => 'Efectivo', 'initial_balance' => 0],
                ['name' => 'Banco', 'initial_balance' => 0],
                ['name' => 'Billetera', 'initial_balance' => 0],
            ];

            foreach ($defaultAccounts as $acc) {
                Account::firstOrCreate(
                    ['user_id' => $user->id, 'name' => $acc['name']],
                    ['initial_balance' => $acc['initial_balance'], 'is_archived' => false]
                );
            }

            // Categorías por defecto
            $defaultCategories = [
                // income
                ['type' => 'income', 'name' => 'Salario'],
                ['type' => 'income', 'name' => 'Ventas'],
                ['type' => 'income', 'name' => 'Regalo'],
                ['type' => 'income', 'name' => 'Préstamo recibido'],
                ['type' => 'income', 'name' => 'Otros ingresos'],

                // expense
                ['type' => 'expense', 'name' => 'Arriendo/Hipoteca'],
                ['type' => 'expense', 'name' => 'Servicios'],
                ['type' => 'expense', 'name' => 'Mercado'],
                ['type' => 'expense', 'name' => 'Transporte'],
                ['type' => 'expense', 'name' => 'Salud'],
                ['type' => 'expense', 'name' => 'Educación'],
                ['type' => 'expense', 'name' => 'Entretenimiento'],
                ['type' => 'expense', 'name' => 'Restaurantes'],
                ['type' => 'expense', 'name' => 'Ahorro'],
                ['type' => 'expense', 'name' => 'Pago préstamo'],
                ['type' => 'expense', 'name' => 'Otros gastos'],
            ];

            foreach ($defaultCategories as $cat) {
                Category::firstOrCreate(
                    ['user_id' => $user->id, 'type' => $cat['type'], 'name' => $cat['name']],
                    ['is_archived' => false]
                );
            }
        });
    }
}
