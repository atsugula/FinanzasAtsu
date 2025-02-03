<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\V1\Goal;
use App\Models\V1\Saving;
use App\Models\V1\Income;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use App\Models\V1\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        /* Inicializamos variables */
        $count_incomes = $count_expense = $count_saving = $count_incomes_am_owed = $count_expense_must = 0;

        /* Capturamos los datos del usuario logeado */
        $user = Auth::user();
        $id_auth = $user->id;

        $today = Carbon::now()->toDateString(); // Obtener la fecha actual en formato 'Y-m-d'

        // Calculamos los incomes o ingresos por acá
        $incomes = Transaction::where('created_by', $id_auth)
            ->where('type', 'I')
            ->whereNotIn('status_id', [config('status.PEN'), config('status.CANC'), config('status.REC'), config('status.DED')])
            ->get();

        foreach ($incomes as $key => $income) {
            $count_incomes += $income->amount;
        }
        // Calculamos los expense por acá
        $expenses = Transaction::where('created_by', $id_auth)
            ->where('type', 'E')
            ->whereNotIn('status_id', [config('status.PEN'), config('status.CANC'), config('status.REC'), config('status.DED')])
            ->get();

        foreach ($expenses as $key => $expense) {
            $count_expense += $expense->amount;
        }

        // Calculamos los saving por acá
        $savings = Transaction::where('created_by', $id_auth)
            ->where('type', 'A')
            ->get();
        foreach ($savings as $key => $saving) {
            $count_saving += $saving->amount;
        }

        // Me deben esto
        $incomes_am_owed = Transaction::where('created_by', $id_auth)
            ->where('type', 'I')
            ->whereIn('status_id', [config('status.PEN')])
            ->get();
        // Calculamos los incomes_am_owed por acá
        foreach ($incomes_am_owed as $key => $income_am_owed) {
            $count_incomes_am_owed += $income_am_owed->amount;
        }

        // Debo lo siguiente
        $expenses_must = Transaction::where('created_by', $id_auth)
            ->where('type', 'E')
            ->whereIn('status_id', [config('status.DED')])
            ->get();
        // Calculamos los expenses_must por acá
        foreach ($expenses_must as $key => $expense_must) {
            $count_expense_must += $expense_must->amount;
        }

        // Categorias de gastos
        $categories = ExpensesCategory::where('created_by', $id_auth)
            ->withCount('transactions') // Contamos las transacciones relacionadas
            ->orderByDesc('transactions_count') // Ordenamos por la cantidad de transacciones (de mayor a menor)
            ->take(10)
            ->get();

        // Traemos los objetivos
        $goals = Goal::where('created_by', $id_auth)->with('transactions')->get();

        foreach ($goals as $goal) {
            $totalTransactions = $goal?->transactions->sum('amount'); // Sum 'amount' for each goal's transactions
            $goal->total_savings = $totalTransactions; // Add it as a custom attribute
        }

        // Traemos las egresos que estan como deudas
        $incomes_owing = Transaction::where('created_by', $id_auth)
            ->where('type', 'E')
            ->whereIn('status_id', [config('status.DED'), config('status.ENPROC')])
            ->with('payments', 'expensesCategory')
            ->get();

        foreach ($incomes_owing as $income_owing) {
            $income_owing->total_debt = $income_owing?->payments->sum('paid');
        }

        return view('pages.dashboard', compact('incomes_owing', 'goals', 'count_incomes', 'count_expense', 'count_saving', 'categories', 'user', 'count_incomes_am_owed', 'count_expense_must'));
    }

    function getDataSelects(Request $request)
    {
        // Obtiene los nombres de las tablas desde el request
        $fields = json_decode($request->query('fields', '[]'), true);

        // Verifica que se haya enviado al menos una tabla
        if (!is_array($fields) || empty($fields)) {
            return response()->json([
                'success' => false,
                'message' => 'No se enviaron tablas para consultar.'
            ], 400);
        }

        $data = [];

        foreach ($fields as $table) {
            // Verifica si la tabla existe en la base de datos
            if (!Schema::hasTable($table)) {
                return response()->json([
                    'success' => false,
                    'message' => "La tabla '$table' no existe."
                ], 400);
            }

            switch ($table) {
                case 'partners':
                    // Obtiene los registros con id y nombre genérico
                    $records = DB::table($table)->select('id', DB::raw('company_name AS label'))->get();
                    break;
                default:
                    // Obtiene los registros con id y nombre genérico
                    $records = DB::table($table)->select('id', DB::raw('name AS label'))->get();
                    break;
            }

            $data[$table] = $records;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
