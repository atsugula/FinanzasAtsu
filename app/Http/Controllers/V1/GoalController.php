<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Goal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $id_auth = Auth::id();

        $goals = Goal::where('created_by', $id_auth)
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('goal.index', compact('goals'))
            ->with('i', (request()->input('page', 1) - 1) * $goals->perPage());
    }

    public function create()
    {
        $goal = new Goal();
        return view('goal.create', compact('goal'));
    }

    public function store(Request $request)
    {
        $request->validate(Goal::$rules);

        $request['created_by'] = Auth::id();
        
        $request['current_amount'] = $request['target_amount'];

        Goal::create($request->all());

        return redirect()->route('goals.index')
            ->with('success', 'Meta creada exitosamente.');
    }

    public function show($id)
    {
        $goal = Goal::findOrFail($id);

        return view('goal.show', compact('goal'));
    }

    public function edit($id)
    {
        $goal = Goal::findOrFail($id);

        return view('goal.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        $request->validate(Goal::$rules);

        $goal->update($request->all());

        return redirect()->route('goals.index')
            ->with('success', 'Meta actualizada exitosamente.');
    }

    public function destroy($id)
    {
        Goal::findOrFail($id)->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Meta eliminada exitosamente.');
    }
}
