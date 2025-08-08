<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function create()
    {
        $categories = \App\Models\V1\Category::all();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'recurring_interval_days' => 'nullable|integer|min:1',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        Transaction::create($validated);

        return redirect()->route('dashboard')->with('success', 'Transacción guardada ✅');
    }
}
