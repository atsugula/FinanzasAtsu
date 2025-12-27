<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Mes (YYYY-MM) opcional; por defecto el actual
        $month = $request->query('month', now()->format('Y-m'));
        if (!preg_match('/^\d{4}\-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }

        [$y, $m] = explode('-', $month);
        $from = sprintf('%04d-%02d-01', (int) $y, (int) $m);
        $to = date('Y-m-d', strtotime("$from +1 month"));
        $end = date('Y-m-d', strtotime("$to -1 day"));

        // Totales del mes
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

        // Saldo total (simple): inicial + ingresos - gastos (histórico)
        $initial = Account::where('user_id', $userId)->sum('initial_balance');

        $incomeAll = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $expenseAll = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $totalBalance = $initial + $incomeAll - $expenseAll;

        // Top categorías del mes (solo expense para que sea más útil)
        $topExpenseCategories = Category::query()
            ->where('categories.user_id', $userId)
            ->where('categories.type', 'expense')
            ->where('categories.is_archived', false)
            ->leftJoin('transactions', function ($join) use ($userId, $from, $end) {
                $join->on('transactions.category_id', '=', 'categories.id')
                    ->where('transactions.user_id', $userId)
                    ->where('transactions.type', 'expense')
                    ->whereBetween('transactions.date', [$from, $end]);
            })
            ->selectRaw('categories.id, categories.name, COALESCE(SUM(transactions.amount),0) as total')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Últimos movimientos (para la tarjeta de “Recientes”)
        $recentTransactions = Transaction::where('user_id', $userId)
            ->with(['account:id,name', 'category:id,name,type'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('pages.dashboard', [
            'month' => $month,
            'from' => $from,
            'end' => $end,

            'totalBalance' => (float) $totalBalance,
            'incomeMonth' => (float) $incomeMonth,
            'expenseMonth' => (float) $expenseMonth,
            'savingsMonth' => (float) $savingsMonth,

            'topExpenseCategories' => $topExpenseCategories,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
