<?php

namespace App\Http\Controllers\V1;

use App\Traits\Template;
use App\Models\V1\Status;
use App\Models\V1\Partner;
use Illuminate\Http\Request;
use App\Models\V1\PaymentsHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class PaymentsHistoryController
 * @package App\Http\Controllers
 */
class PaymentsHistoryController extends Controller
{

    use Template;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentsHistories = PaymentsHistory::paginate();

        return view('payments-history.index', compact('paymentsHistories'))
            ->with('i', (request()->input('page', 1) - 1) * $paymentsHistories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paymentsHistory = new PaymentsHistory();
        $partners = Partner::pluck('company_name AS label', 'id as value');
        $statuses = Status::pluck('name AS label', 'id as value');

        return view('payments-history.create', compact('paymentsHistory', 'statuses', 'partners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(PaymentsHistory::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $request['created_by'] = $id_auth;

        $paymentsHistory = PaymentsHistory::create($request->all());

        return redirect()->route('payments-histories.index')
            ->with('success', 'PaymentsHistory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentsHistory = PaymentsHistory::find($id);

        return view('payments-history.show', compact('paymentsHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paymentsHistory = PaymentsHistory::find($id);
        $partners = Partner::pluck('company_name AS label', 'id as value');
        $statuses = Status::pluck('name AS label', 'id as value');

        return view('payments-history.edit', compact('paymentsHistory', 'partners', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PaymentsHistory $paymentsHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentsHistory $paymentsHistory)
    {
        request()->validate(PaymentsHistory::$rules);

        $paymentsHistory->update($request->all());

        $data['expense_id'] = $paymentsHistory->expense_id;

        // Actualizamos los status
        $balance = $this->payment_update($data);

        // Volvemos a actualizar lo que se debe
        $paymentsHistory->payable = $balance['balance_due'] ?? '';
        $paymentsHistory->save();

        return redirect()->route('payments-histories.index')
            ->with('success', 'PaymentsHistory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $paymentsHistory = PaymentsHistory::find($id);

        $data['expense_id'] = $paymentsHistory->expense_id;

        // Actualizamos los status
        $balance = $this->payment_update($data);

        $paymentsHistory->delete();

        return redirect()->route('payments-histories.index')
            ->with('success', 'PaymentsHistory deleted successfully');
    }
}
