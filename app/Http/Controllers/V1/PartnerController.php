<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\V1\TypeDocument;
use Illuminate\Support\Facades\Auth;

/**
 * Class PartnerController
 * @package App\Http\Controllers
 */
class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $partners = Partner::where('created_by', $id_auth)->paginate();

        return view('partner.index', compact('partners'))
            ->with('i', (request()->input('page', 1) - 1) * $partners->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partner = new Partner();
        $type_documents = TypeDocument::pluck('name AS label', 'id as value');

        return view('partner.create', compact('partner', 'type_documents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Partner::$rules);

        /* Capturamos el ID del usuario logeado */
        $id_auth = Auth::id();

        $request['created_by'] = $id_auth;

        $partner = Partner::create($request->all());

        return redirect()->route('partners.index')
            ->with('success', 'Partner created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $partner = Partner::find($id);

        return view('partner.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner = Partner::find($id);
        $type_documents = TypeDocument::pluck('name AS label', 'id as value');

        return view('partner.edit', compact('partner', 'type_documents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Partner $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        request()->validate(Partner::$rules);

        $partner->update($request->all());

        return redirect()->route('partners.index')
            ->with('success', 'Partner updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $partner = Partner::find($id)->delete();

        return redirect()->route('partners.index')
            ->with('success', 'Partner deleted successfully');
    }
}
