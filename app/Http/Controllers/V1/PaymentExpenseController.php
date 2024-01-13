<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\V1\Status;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class PaymentExpenseController
 * @package App\Http\Controllers
 */
class PaymentExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $expenses = Expense::where('created_by', $id_auth)->where('status', config('status.DED'))->paginate();

        return view('payment-expense.index', compact('expenses'))
            ->with('i', (request()->input('page', 1) - 1) * $expenses->perPage());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::find($id);

        return view('payment-expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::find($id);

        $users = User::pluck('firstname AS label', 'id as value');
        $statuses = Status::whereIn('id', [config('status.APR'), config('status.CANC'), config('status.REC'), config('status.DED')])
                ->pluck('name AS label', 'id as value');
        $categories = ExpensesCategory::pluck('name AS label', 'id as value');

        return view('expense.edit', compact('expense', 'users', 'categories', 'statuses'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expense = Expense::find($id)->delete();

        return redirect()->route('Pay payment-expenses.index')
            ->with('success', 'Expense deleted successfully');
    }
}
