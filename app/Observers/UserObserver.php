<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\UserSetting;

class UserObserver
{
    public function created(User $user): void
    {
        // Settings
        UserSetting::create([
            'user_id' => $user->id,
            'currency' => 'COP',
            'month_start_day' => 1,
        ]);

        // Accounts
        $defaultAccounts = ['Efectivo', 'Banco', 'Billetera'];

        foreach ($defaultAccounts as $name) {
            Account::create([
                'user_id' => $user->id,
                'name' => $name,
                'initial_balance' => 0,
                'is_archived' => false,
            ]);
        }

        // Categories
        $income = ['Salario', 'Ventas', 'Regalo', 'Préstamo recibido', 'Otros ingresos'];
        $expense = [
            'Arriendo/Hipoteca',
            'Servicios',
            'Mercado',
            'Transporte',
            'Salud',
            'Educación',
            'Entretenimiento',
            'Restaurantes',
            'Ahorro',
            'Pago préstamo',
            'Otros gastos'
        ];

        foreach ($income as $name) {
            Category::create([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'income',
                'icon' => null,
                'is_archived' => false,
            ]);
        }

        foreach ($expense as $name) {
            Category::create([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'expense',
                'icon' => null,
                'is_archived' => false,
            ]);
        }
    }
}
