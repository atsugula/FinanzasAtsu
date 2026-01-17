<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $settings = $user->settings; // creado por default al registrar

        [$start, $end] = $this->monthRange($settings->month_start_day);

        $incomeMonth = Transaction::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->where('type', 'income')
            ->sum('amount');

        $expenseMonth = Transaction::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->where('type', 'expense')
            ->sum('amount');

        $savingsMonth = Transaction::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->where('type', 'expense')
            ->whereHas('category', fn($q) => $q->where('name', 'Ahorro'))
            ->sum('amount');

        $initialBalances = $user->accounts()->where('is_archived', false)->sum('initial_balance');

        $totalBalance = (float) $initialBalances + (float) $incomeMonth - (float) $expenseMonth;

        return view('dashboard.index', compact(
            'incomeMonth',
            'expenseMonth',
            'savingsMonth',
            'totalBalance',
            'start',
            'end',
            'settings'
        ));
    }

    private function monthRange(int $monthStartDay): array
    {
        $today = Carbon::today();

        // Si hoy es antes del "día de inicio", el periodo empezó el mes pasado
        $start = $today->copy()->startOfMonth()->day($monthStartDay);
        if ($today->day < $monthStartDay) {
            $start = $start->subMonthNoOverflow();
        }

        $end = $start->copy()->addMonthNoOverflow()->subDay(); // fin inclusivo

        return [$start->toDateString(), $end->toDateString()];
    }
}
