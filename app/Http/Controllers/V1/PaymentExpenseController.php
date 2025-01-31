<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\V1\Status;
use App\Models\V1\Expense;
use App\Models\V1\Partner;
use Illuminate\Http\Request;
use App\Models\V1\PaymentsHistory;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;
use App\Models\V1\Transaction;
use App\Traits\Template;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentExpenseController
 * @package App\Http\Controllers
 */
class PaymentExpenseController extends Controller
{

    use Template;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $expenses = Transaction::where('created_by', $id_auth)
                        ->where('type', 'E')
                        ->whereIn('status_id', [config('status.DED'), config('status.ENPROC')])
                        ->with('payments', 'expensesCategory')
                        ->orderBy('id', 'DESC')
                        ->paginate();

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
        $expense = Transaction::find($id);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $partners = Partner::where('created_by', $id_auth)->pluck('company_name AS label', 'id as value');

        // Cargamos la data
        $data['transaction_id'] = $id;

        // Actualizamos los status
        $balance = $this->payment_transaction_update($data);

        $paymentsHistory = new PaymentsHistory();
        $users = User::pluck('firstname AS label', 'id as value');
        $categories = ExpensesCategory::pluck('name AS label', 'id as value');
        $statuses = Status::whereIn('id', [
            config('status.APR'),
            config('status.PROC')
        ])->pluck('name AS label', 'id as value');

        return view('payment-expense.edit', compact('expense', 'users', 'categories', 'statuses', 'partners', 'paymentsHistory', 'balance'));
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

        request()->validate(PaymentsHistory::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        /* Guardamos toda la data en una variable */
        $data = $request->all();

        // Actualizamos los status
        $balance = $this->payment_transaction_update($data);

        // Verificamos que no se vaya a pagar de mas
        if ($data['paid'] > $balance['count_payment'] && $balance['count_payment'] != 0) {
            return redirect()->route('payment-expenses.index')
                ->with('error', 'The amount deposited is greater than the outstanding balance, you owe ' . $balance['balance_due'] . ' and income ' . $data['paid']);
        }

        $payment_history = new PaymentsHistory();
        $payment_history->paid = $data['paid'] ?? '';
        $payment_history->payable = $balance['balance_due'] ?? '';
        $payment_history->date = $data['date'] ?? '';
        $payment_history->description = $data['description'] ?? '';
        $payment_history->status = $data['status'] ?? '';
        $payment_history->partner_id = $data['partner_id'] ?? '';
        $payment_history->transaction_id = $data['transaction_id'] ?? '';
        $payment_history->created_by = $id_auth;

        $payment_history->save();

        // Actualizamos los status
        $balance = $this->payment_transaction_update($data);

        // Volvemos a actualizar lo que se debe
        $payment_history->payable = $balance['balance_due'] ?? '';
        $payment_history->save();

        return redirect()->route('payments-histories.index')
            ->with('success', 'Payments created successfully.');

    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        $data['transaction_id'] = $id;

        // Actualizamos los status
        $balance = $this->payment_transaction_update($data);

        return redirect()->route('payment-expenses.index')
            ->with('success', 'Expense changed successfully');
    }

}
