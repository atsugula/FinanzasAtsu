<?php

namespace App\Http\Controllers\Web;

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

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'initial_balance' => ['required', 'numeric'],
        ]);

        Account::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'initial_balance' => $data['initial_balance'],
            'is_archived' => false,
        ]);

        return redirect()->route('accounts.index')->with('success', 'Cuenta creada.');
    }

    public function edit(Request $request, Account $account)
    {
        $account = $this->ownedAccount($request->user()->id, $account->id);
        return view('accounts.edit', compact('account'));
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

        return redirect()->route('accounts.index')->with('success', 'Cuenta actualizada.');
    }

    public function archive(Request $request, $account_id)
    {
        $account = $this->ownedAccount($request->user()->id, $account_id);

        $account->update(['is_archived' => true]);

        return back()->with('success', 'Cuenta archivada.');
    }

    private function ownedAccount(int $userId, int $id): Account
    {
        return Account::query()
            ->where('user_id', $userId)
            ->findOrFail($id);
    }
}
