<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Goal;
use App\Models\V1\Status;
use App\Models\V1\Partner;
use Illuminate\Http\Request;
use App\Models\V1\Transaction;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
class TransactionController extends Controller
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

        $transactions = Transaction::where('created_by', $id_auth)->with('expensesCategory', 'goalRelation', 'status', 'user', 'payments')->orderBy('id', 'DESC')->paginate();

        return view('transaction.index', compact('transactions'))
            ->with('i', (request()->input('page', 1) - 1) * $transactions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $partners = Partner::where('created_by', $id_auth)->pluck('company_name AS label', 'id as value');
        $categories = ExpensesCategory::where('created_by', $id_auth)->pluck('name AS label', 'id as value');
        $statuses = Status::pluck('name AS label', 'id as value');
        $goals = Goal::where('created_by', $id_auth)->pluck('name AS label', 'id as value');

        $types = [
            'I' => __('Income'),
            'E' => __('Expense'),
            'A' => __('Saving')
        ];

        $transaction = new Transaction();
        return view('transaction.create', compact('transaction', 'goals', 'statuses', 'partners', 'categories', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Transaction::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $request['created_by'] = $id_auth;

        $transaction = Transaction::create($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        return view('transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::find($id);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $partners = Partner::where('created_by', $id_auth)->pluck('company_name AS label', 'id as value');
        $categories = ExpensesCategory::where('created_by', $id_auth)->pluck('name AS label', 'id as value');
        $statuses = Status::pluck('name AS label', 'id as value');
        $goals = Goal::where('created_by', $id_auth)->pluck('name AS label', 'id as value');

        $types = [
            'I' => __('Income'),
            'E' => __('Expense'),
            'A' => __('Saving')
        ];

        return view('transaction.edit', compact('transaction', 'goals', 'statuses', 'partners', 'categories', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        request()->validate(Transaction::$rules);

        $transaction->update($request->all());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id)->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }

}
