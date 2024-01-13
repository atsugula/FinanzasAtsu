<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\V1\Saving;
use App\Models\V1\Income;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use Illuminate\Support\Facades\Auth;

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
        $incomes = Income::where('created_by', $id_auth)
            ->whereNotIn('status',[config('status.PEN'), config('status.CANC'), config('status.REC'), config('status.DED')])->get();
        // Calculamos los incomes por acá
        foreach ($incomes as $key => $income) {
            $count_incomes += $income->amount;
        }
        $expenses = Expense::where('created_by', $id_auth)
            ->whereNotIn('status',[config('status.CANC'), config('status.REC'), config('status.DED')])->get();
        // Calculamos los expense por acá
        foreach ($expenses as $key => $expense) {
            $count_expense += $expense->amount;
        }
        $savings = Saving::where('created_by', $id_auth)->get();
        // Calculamos los saving por acá
        foreach ($savings as $key => $saving) {
            $count_saving += $saving->amount;
        }

        // Me deben esto
        $incomes_am_owed = Income::where('created_by', $id_auth)
            ->where('status',[config('status.PEN')])->get();
        // Calculamos los incomes_am_owed por acá
        foreach ($incomes_am_owed as $key => $income_am_owed) {
            $count_incomes_am_owed += $income_am_owed->amount;
        }

        // Debo lo siguiente
        $expenses_must = Expense::where('created_by', $id_auth)
            ->where('status',[config('status.DED')])->get();
        // Calculamos los expense por acá
        foreach ($expenses_must as $key => $expense_must) {
            $count_expense_must += $expense_must->amount;
        }

        $categories = ExpensesCategory::where('created_by', $id_auth)->latest()
                                        ->take(5)
                                        ->get();

        return view('pages.dashboard', compact('count_incomes', 'count_expense', 'count_saving', 'categories', 'user', 'count_incomes_am_owed', 'count_expense_must'));
    }
}
