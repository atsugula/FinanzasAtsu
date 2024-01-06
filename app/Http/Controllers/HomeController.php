<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\V1\Saving;
use App\Models\V1\Income;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;

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

        

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $today = Carbon::now()->toDateString(); // Obtener la fecha actual en formato 'Y-m-d'
        $count_incomes = Income::where('created_by', $id_auth)->whereDate('created_at', $today)->count();
        $count_expense = Expense::where('created_by', $id_auth)->whereDate('created_at', $today)->count();
        $count_saving = Saving::where('created_by', $id_auth)->whereDate('created_at', $today)->count();

        $categories = ExpensesCategory::where('created_by', $id_auth)->latest()
                                        ->take(5)
                                        ->get();

        return view('pages.dashboard', compact('count_incomes', 'count_expense', 'count_saving', 'categories'));
    }
}
