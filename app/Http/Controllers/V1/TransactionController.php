<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Goal;
use App\Models\V1\Status;
use Illuminate\Support\Str;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use App\Models\V1\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{

    private $types;

    public function __construct()
    {
        $this->types = [
            'income' => __('Ingreso'),
            'expense' => __('Gasto'),
            'saving' => __('Ahorro'),
            'debt_in' => __('Deuda (Préstamos realizados)'), // Ej: alguien me debe
            'debt_on' => __('Deuda (Préstamos recibidos)'), // Ej: yo debo
        ];
    }

    /**
     * Listar todas las transacciones
     */
    public function index()
    {
        $transactions = Transaction::with(['category', 'goal', 'creator'])
                                ->where('created_by', Auth::id())
                                ->latest()->paginate(10);
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

        $goals = Goal::where('created_by', auth()->id())->get()->all();

        return view('transaction.create', compact('categories', 'types', 'statuses', 'goals'));
    }

    /**
     * Guardar una nueva transacción
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'type' => 'required|in:expense,income,saving,debt_in,debt_on',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'status_id' => 'required|exists:statuses,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable',
            'recurring_interval_days' => 'nullable|integer|min:1',
            'files' => 'nullable',
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        $validated['created_by'] = auth()->id();

        // subir archivos (si hay)
        $filePaths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // guardamos en disco public/transactions
                $path = $file->store('transactions', 'public');
                $filePaths[] = $path;
            }
        }
        $validated['files'] = $filePaths;

        $transaction = Transaction::create($validated);

        if (in_array($transaction->type, ['saving', 'debt_in', 'debt_on']) && $transaction->goal_id) {
            // Buscar la meta
            $goal = Goal::find($transaction->goal_id);

            if ($goal) {
                // Sumar todas las transacciones tipo "saving" de esa meta
                $total = Transaction::where('goal_id', $transaction->goal_id)
                    ->whereIn('type', ['saving', 'debt_in', 'debt_on'])
                    ->sum('amount');

                // Actualizar el campo current_amount
                $goal->update([
                    'current_amount' => $total
                ]);
            }
        }

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

        $goals = Goal::where('created_by', auth()->id())->get()->all();

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

        $goals = Goal::where('created_by', auth()->id())->get()->all();

        return view('transaction.edit', compact('transaction', 'categories', 'types', 'statuses', 'goals'));
    }

    /**
     * Actualizar una transacción
     */
    public function update(Request $request, Transaction $transaction)
    {

        $validated = $request->validate([
            'type' => 'required|in:expense,income,saving,debt_in,debt_on',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'status_id' => 'nullable|exists:statuses,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable',
            'recurring_interval_days' => 'nullable|integer|min:1',
            'files' => 'nullable',
            'files.*' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar',
            'delete_files' => 'nullable|array',
            'delete_files.*' => 'nullable|string',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        // Archivos existentes (array)
        $existing = $transaction->files ?? [];

        // Eliminar archivos marcados en el formulario (delete_files[])
        $toDelete = $request->input('delete_files', []);
        if (!empty($toDelete)) {
            foreach ($toDelete as $del) {
                // seguridad: sólo borrar dentro de la carpeta transactions/
                if (in_array($del, $existing) && Str::startsWith($del, 'transactions/')) {
                    Storage::disk('public')->delete($del);
                    // quitar del array existente
                    $existing = array_values(array_diff($existing, [$del]));
                }
            }
        }

        // Subir nuevos archivos y anexarlos
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('transactions', 'public');
                $existing[] = $path;
            }
        }

        $validated['files'] = $existing;

        // Ajuste de ahorro
        if (in_array($transaction->type, ['saving', 'debt_in', 'debt_on']) && $transaction->goal_id) {
            // Buscar la meta
            $goal = Goal::find($transaction->goal_id);

            if ($goal) {
                // Sumar todas las transacciones tipo "saving" de esa meta
                $total = Transaction::where('goal_id', $transaction->goal_id)
                    ->whereIn('type', ['saving', 'debt_in', 'debt_on'])
                    ->sum('amount');

                // Actualizar el campo current_amount
                $goal->update([
                    'current_amount' => $total
                ]);
            }
        }

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

        // eliminar archivos del disco antes de borrar registro
        $files = $transaction->files ?? [];
        foreach ($files as $f) {
            if (Str::startsWith($f, 'transactions/')) {
                Storage::disk('public')->delete($f);
            }
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transacción eliminada correctamente');
    }
}
