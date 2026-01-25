<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('is_archived')
            ->orderBy('name')
            ->paginate(25);

        return response()->json([
            'success' => true,
            'data' => $accounts,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'initial_balance' => ['required', 'numeric'],
        ]);

        $account = Account::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'initial_balance' => $data['initial_balance'],
            'is_archived' => false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $account,
        ], 201);
    }

    public function show(Request $request, Account $account)
    {
        $account = $this->ownedAccount($request->user()->id, $account->id);

        return response()->json([
            'success' => true,
            'data' => $account,
        ]);
    }

    public function update(Request $request, Account $account)
    {
        $account = $this->ownedAccount($request->user()->id, $account->id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'initial_balance' => ['required', 'numeric'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $account->update([
            'name' => $data['name'],
            'initial_balance' => $data['initial_balance'],
            'is_archived' => (bool) ($data['is_archived'] ?? $account->is_archived),
        ]);

        return response()->json([
            'success' => true,
            'data' => $account->fresh(),
        ]);
    }

    public function archive(Request $request, Account $account)
    {
        $account = $this->ownedAccount($request->user()->id, $account->id);

        $account->update(['is_archived' => true]);

        return response()->json([
            'success' => true,
            'data' => $account->fresh(),
        ]);
    }

    private function ownedAccount(int $userId, int $id): Account
    {
        return Account::query()
            ->where('user_id', $userId)
            ->findOrFail($id);
    }
}
