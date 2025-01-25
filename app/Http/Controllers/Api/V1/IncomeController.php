<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\V1\Income;
use App\Models\V1\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V1\Partner;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $id_auth = Auth::id();
        $incomes = Income::/* where('created_by', $id_auth)-> */paginate();

        return response()->json([
            'success' => true,
            'data' => $incomes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(Income::$rules);

        $id_auth = Auth::id();
        $request['created_by'] = $id_auth;

        $income = Income::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Income created successfully.',
            'data' => $income
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json([
                'success' => false,
                'message' => 'Income not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $income
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Income $income
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Income $income)
    {
        $request->validate(Income::$rules);

        $income->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Income updated successfully.',
            'data' => $income
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $income = Income::find($id);

        if (!$income) {
            return response()->json([
                'success' => false,
                'message' => 'Income not found.'
            ], 404);
        }

        $income->delete();

        return response()->json([
            'success' => true,
            'message' => 'Income deleted successfully.'
        ]);
    }
}
