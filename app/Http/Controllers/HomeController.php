<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\V1\Transaction;
use App\Models\V1\Category;
use App\Models\V1\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        $userId = Auth::id();

        // Totales generales
        $count_incomes = Transaction::where('created_by', $userId)
            ->where('type', 'income')
            ->sum('amount');

        $count_expense = Transaction::where('created_by', $userId)
            ->where('type', 'expense')
            ->sum('amount');

        $count_saving = Transaction::where('created_by', $userId)
            ->where('type', 'saving')
            ->sum('amount');

        // Total de deudas me deben
        $count_incomes_am_owed = Transaction::where('created_by', $userId)
            ->where('type', 'debt_in')
            ->sum('amount');

        // Total de deudas que debo pagar
        $count_expense_must = Goal::where('created_by', $userId)
            ->whereHas('transactions', function ($query) {
                $query->whereIn('type', ['debt_on']);
            })->sum('current_amount');

        // Categorías más usadas (Top 10)
        $categories = Category::where('created_by', $userId)
            ->withCount('transactions')
            ->orderByDesc('transactions_count')
            ->take(10)
            ->get();

        // Metas con progreso
        $goals = Goal::where('created_by', $userId)
            ->whereHas('transactions', function ($query) {
                $query->where('type', 'saving');
            })->get();

        // Deudas individuales (para los gráficos de "Debt status")
        $incomes_owing = Goal::where('created_by', $userId)
            ->whereHas('transactions', function ($query) {
                $query->whereIn('type', ['debt_on', 'debt_in']);
            })->get();

        return view('pages.dashboard', compact(
            'count_incomes',
            'count_expense',
            'count_saving',
            'count_incomes_am_owed',
            'count_expense_must',
            'categories',
            'goals',
            'incomes_owing'
        ));
    }

    /**
     * Cargar datos para selects dinámicos en formularios
     */
    public function getDataSelects(Request $request)
    {
        $userId = Auth::id();
        $fields = json_decode($request->query('fields', '[]'), true);

        if (!is_array($fields) || empty($fields)) {
            return response()->json([
                'success' => false,
                'message' => 'No se enviaron tablas para consultar.'
            ], 400);
        }

        $data = [];

        foreach ($fields as $table) {
            if (!Schema::hasTable($table)) {
                return response()->json([
                    'success' => false,
                    'message' => "La tabla '$table' no existe."
                ], 400);
            }

            $records = DB::table($table)
                ->select('id', DB::raw('name AS label'))
                ->where('created_by', $userId)
                ->get();

            $data[$table] = $records;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
