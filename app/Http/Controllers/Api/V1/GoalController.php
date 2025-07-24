<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\V1\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class GoalController
 * @package App\Http\Controllers\V1
 */
class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $id_auth = Auth::id();
        $goals = Goal::where('created_by', $id_auth)
                    ->with('transactions', 'savings')
                    ->orderBy('id', 'DESC')
                    ->paginate();

        return response()->json($goals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate(Goal::$rules);

        $id_auth = Auth::id();
        $request['created_by'] = $id_auth;

        $goal = Goal::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $goal,
            'message' => 'Goal created successfully'
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
        $goal = Goal::find($id);

        if (!$goal) {
            return response()->json([
                'success' => false,
                'message' => 'Goal not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $goal
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return response()->json([
                'success' => false,
                'message' => 'Goal not found'
            ], 404);
        }

        $request->validate(Goal::$rules);

        $goal->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $goal,
            'message' => 'Goal updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return response()->json([
                'success' => false,
                'message' => 'Goal not found'
            ], 404);
        }

        $goal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Goal deleted successfully'
        ]);
    }
}