<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $userId = auth()->id();

        // rango del mes (o mes actual si no viene)
        $month = $request->string('month')->toString() ?: now()->format('Y-m');
        [$y, $m] = explode('-', $month);

        $from = sprintf('%04d-%02d-01', (int) $y, (int) $m);
        $to = date('Y-m-d', strtotime("$from +1 month"));
        $end = date('Y-m-d', strtotime("$to -1 day"));

        $incomeMonth = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereBetween('date', [$from, $end])
            ->sum('amount');

        $expenseMonth = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$from, $end])
            ->sum('amount');

        // Ahorro del mes: categoría "Ahorro" (expense)
        $savingsCategoryId = Category::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereRaw('LOWER(name) = ?', ['ahorro'])
            ->value('id');

        $savingsMonth = $savingsCategoryId
            ? Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->where('category_id', $savingsCategoryId)
                ->whereBetween('date', [$from, $end])
                ->sum('amount')
            : 0;

        // saldo total "a hoy" (simple): inicial + ingresos - gastos (sin fecha)
        $initial = Account::where('user_id', $userId)->sum('initial_balance');

        $incomeAll = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $expenseAll = Transaction::where('user_id', $userId)->where('type', 'expense')->sum('amount');

        $totalBalance = $initial + $incomeAll - $expenseAll;

        return response()->json([
            'month' => $month,
            'total_balance' => (float) $totalBalance,
            'income_month' => (float) $incomeMonth,
            'expense_month' => (float) $expenseMonth,
            'savings_month' => (float) $savingsMonth,
        ]);
    }
}
