<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\V1\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class IncomeController
 * @package App\Http\Controllers
 */
class IncomeController extends Controller
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
        
        $incomes = Income::where('created_by', $id_auth)->paginate();

        return view('income.index', compact('incomes'))
            ->with('i', (request()->input('page', 1) - 1) * $incomes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $income = new Income();

        $users = User::pluck('firstname AS label', 'id as value');

        return view('income.create', compact('income', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Income::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $request['created_by'] = $id_auth;

        $income = Income::create($request->all());

        return redirect()->route('incomes.index')
            ->with('success', 'Income created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $income = Income::find($id);

        return view('income.show', compact('income'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $income = Income::find($id);

        $users = User::pluck('firstname AS label', 'id as value');

        return view('income.edit', compact('income', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Income $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        request()->validate(Income::$rules);

        $income->update($request->all());

        return redirect()->route('incomes.index')
            ->with('success', 'Income updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $income = Income::find($id)->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Income deleted successfully');
    }
}
