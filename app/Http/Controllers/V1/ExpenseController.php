<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\V1\Expense;
use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;

/**
 * Class ExpenseController
 * @package App\Http\Controllers
 */
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $expenses = Expense::where('created_by', $id_auth)->paginate();

        return view('expense.index', compact('expenses'))
            ->with('i', (request()->input('page', 1) - 1) * $expenses->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense = new Expense();

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $users = User::where('id', $id_auth)->pluck('firstname AS label', 'id as value');
        $categories = ExpensesCategory::where('created_by', $id_auth)->pluck('name AS label', 'id as value');

        return view('expense.create', compact('expense', 'users', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Expense::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $request['created_by'] = $id_auth;

        $expense = Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
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

        return view('expense.show', compact('expense'));
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
        $categories = ExpensesCategory::pluck('name AS label', 'id as value');

        return view('expense.edit', compact('expense', 'users', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        request()->validate(Expense::$rules);

        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expense = Expense::find($id)->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully');
    }
}
