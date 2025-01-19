<?php

namespace App\Http\Controllers\V1;

use App\Models\User;
use App\Models\V1\Goal;
use App\Models\V1\Saving;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class SavingController
 * @package App\Http\Controllers
 */
class SavingController extends Controller
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
        
        $savings = Saving::where('created_by', $id_auth)->paginate();

        return view('saving.index', compact('savings'))
            ->with('i', (request()->input('page', 1) - 1) * $savings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $saving = new Saving();

        $users = User::pluck('firstname AS label', 'id as value');

        $goals = Goal::pluck('name AS label', 'id as value');

        return view('saving.create', compact('saving', 'users', 'goals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Saving::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = \Auth::id();

        $request['created_by'] = $id_auth;

        $saving = Saving::create($request->all());

        return redirect()->route('savings.index')
            ->with('success', 'Saving created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $saving = Saving::find($id);

        return view('saving.show', compact('saving'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saving = Saving::find($id);

        $users = User::pluck('firstname AS label', 'id as value');

        $goals = Goal::pluck('name AS label', 'id as value');

        return view('saving.edit', compact('saving', 'users', 'goals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Saving $saving
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Saving $saving)
    {
        request()->validate(Saving::$rules);

        $saving->update($request->all());

        return redirect()->route('savings.index')
            ->with('success', 'Saving updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $saving = Saving::find($id)->delete();

        return redirect()->route('savings.index')
            ->with('success', 'Saving deleted successfully');
    }
}
