<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::query()
            ->where('user_id', auth()->id())
            ->orderBy('is_archived')
            ->orderBy('name')
            ->paginate(20);

        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('accounts', 'name')->where('user_id', auth()->id()),
            ],
            'initial_balance' => ['nullable', 'numeric'],
        ]);

        Account::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'initial_balance' => $data['initial_balance'] ?? 0,
            'is_archived' => false,
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Cuenta creada.');
    }

    public function show(int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);
        return view('accounts.show', compact('account'));
    }

    public function edit(int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('accounts', 'name')
                    ->where('user_id', auth()->id())
                    ->ignore($account->id),
            ],
            'initial_balance' => ['nullable', 'numeric'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $account->update([
            'name' => $data['name'],
            'initial_balance' => $data['initial_balance'] ?? $account->initial_balance,
            'is_archived' => (bool) ($data['is_archived'] ?? $account->is_archived),
        ]);

        return redirect()->route('accounts.index')
            ->with('success', 'Cuenta actualizada.');
    }

    public function destroy(int $id)
    {
        $account = Account::where('user_id', auth()->id())->findOrFail($id);

        // MVP: archivamos, no borramos
        $account->update(['is_archived' => true]);

        return redirect()->route('accounts.index')
            ->with('success', 'Cuenta archivada.');
    }
}
