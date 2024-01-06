<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Models\V1\ExpensesCategory;
use App\Http\Controllers\Controller;

/**
 * Class ExpensesCategoryController
 * @package App\Http\Controllers
 */
class ExpensesCategoryController extends Controller
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
        
        $expensesCategories = ExpensesCategory::where('created_by', $id_auth)->paginate();

        return view('expenses-category.index', compact('expensesCategories'))
            ->with('i', (request()->input('page', 1) - 1) * $expensesCategories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expensesCategory = new ExpensesCategory();
        return view('expenses-category.create', compact('expensesCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(ExpensesCategory::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $request['created_by'] = $id_auth;

        $expensesCategory = ExpensesCategory::create($request->all());

        return redirect()->route('expenses-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expensesCategory = ExpensesCategory::find($id);

        return view('expenses-category.show', compact('expensesCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expensesCategory = ExpensesCategory::find($id);

        return view('expenses-category.edit', compact('expensesCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ExpensesCategory $expensesCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpensesCategory $expensesCategory)
    {
        request()->validate(ExpensesCategory::$rules);

        $expensesCategory->update($request->all());

        return redirect()->route('expenses-categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $expensesCategory = ExpensesCategory::find($id)->delete();

        return redirect()->route('expenses-categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
