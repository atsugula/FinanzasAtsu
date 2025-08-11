<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Status;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use App\Models\V1\Transaction;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    private $types = [
        'income' => 'Ingreso',
        'expense' => 'Gasto',
        'saving' => 'Ahorro',
        'debt' => 'Deuda'
    ];

    /**
     * Listar todas las transacciones
     */
    public function index()
    {
        $transactions = Transaction::with(['category', 'goal', 'creator'])->latest()->paginate(10);
        return view('transaction.index', compact('transactions'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categories = Category::where('created_by', auth()->id())->get()->all();

        $types = $this->types;

        $statuses = Status::all();

        return view('transaction.create', compact('categories', 'types', 'statuses'));
    }

    /**
     * Guardar una nueva transacción
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required|in:expense,income,saving,debt',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'status_id' => 'required|exists:statuses,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable',
            'recurring_interval_days' => 'nullable|integer|min:1',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        $validated['created_by'] = auth()->id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción creada correctamente');
    }

    /**
     * Mostrar una transacción
     */
    public function show(Transaction $transaction)
    {
        $this->authorizeByCreator($transaction);
        $transaction->load(['category', 'goal', 'payments']);

        $types = $this->types;

        $statuses = Status::all();

        return view('transaction.show', compact('transaction', 'types', 'statuses'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Transaction $transaction)
    {
        $this->authorizeByCreator($transaction);
        $categories = Category::all();

        $types = $this->types;

        $statuses = Status::all();

        return view('transaction.edit', compact('transaction', 'categories', 'types', 'statuses'));
    }

    /**
     * Actualizar una transacción
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'type' => 'required|in:expense,income',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'status_id' => 'nullable|exists:statuses,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable',
            'recurring_interval_days' => 'nullable|integer|min:1',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción actualizada correctamente');
    }

    /**
     * Eliminar una transacción
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorizeByCreator($transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción eliminada correctamente');
    }
}
