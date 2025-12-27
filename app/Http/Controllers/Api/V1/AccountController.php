<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::query()
            ->where('user_id', auth()->id())
            ->orderBy('is_archived')
            ->orderBy('name')
            ->get();

        return AccountResource::collection($accounts);
    }

    public function store(StoreAccountRequest $request)
    {
        $account = Account::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return (new AccountResource($account))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);
        return new AccountResource($account);
    }

    public function update(UpdateAccountRequest $request, int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);
        $account->update($request->validated());

        return new AccountResource($account);
    }

    public function destroy(int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);

        // MVP: archivar, no borrar
        $account->update(['is_archived' => true]);

        return response()->json(['message' => 'Cuenta archivada.']);
    }
}
