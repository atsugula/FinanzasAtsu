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
        $count_incomes = $count_expense = $count_saving = 0;

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $today = Carbon::now()->toDateString(); // Obtener la fecha actual en formato 'Y-m-d'
        $incomes = Income::where('created_by', $id_auth)/* ->whereDate('created_at', $today) */->get();
        // Calculamos los incomes por acá
        foreach ($incomes as $key => $income) {
            $count_incomes += $income->amount;
        }
        $expenses = Expense::where('created_by', $id_auth)/* ->whereDate('created_at', $today) */->get();
        // Calculamos los expense por acá
        foreach ($expenses as $key => $expense) {
            $count_expense += $expense->amount;
        }
        $savings = Saving::where('created_by', $id_auth)/* ->whereDate('created_at', $today) */->get();
        // Calculamos los saving por acá
        foreach ($savings as $key => $saving) {
            $count_saving += $saving->amount;
        }

        $categories = ExpensesCategory::where('created_by', $id_auth)->latest()
                                        ->take(5)
                                        ->get();

        return view('pages.dashboard', compact('count_incomes', 'count_expense', 'count_saving', 'categories'));
    }
}
