<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\V1\Goal;
use App\Models\V1\Status;
use Illuminate\Support\Str;
use App\Models\V1\Category;
use Illuminate\Http\Request;
use App\Models\V1\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    private $types;

    public function __construct()
    {
        $this->types = [
            'income' => 'Ingreso',
            'expense' => 'Gasto',
            'saving' => 'Ahorro',
            'debt_in' => 'Deuda (Préstamos realizados)',
            'debt_on' => 'Deuda (Préstamos recibidos)',
        ];
    }

    /**
     * GET /api/v1/transactions
     * Listar transacciones paginadas del usuario
     */
    public function index()
    {
        $transactions = Transaction::with(['category', 'goal', 'creator'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(15);

        return response()->json($transactions);
    }

    /**
     * POST /api/v1/transactions
     * Crear nueva transacción
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
            'is_recurring' => 'nullable|boolean',
            'recurring_interval_days' => 'nullable|integer|min:1',

            'files' => 'nullable',
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_recurring'] = $request->has('is_recurring');

        // Guardar archivos
        $paths = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $paths[] = $file->store('transactions', 'public');
            }
        }
        $validated['files'] = $paths;

        $transaction = Transaction::create($validated);

        // Actualizar metas
        $this->updateGoalAmount($transaction);

        return response()->json($transaction, 201);
    }

    /**
     * GET /api/v1/transactions/{id}
     */
    public function show($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('created_by', Auth::id())
            ->with(['category', 'goal', 'payments'])
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    /**
     * PUT/PATCH /api/v1/transactions/{id}
     * Actualizar transacción
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $validated = $request->validate([
            'type' => 'required|in:expense,income,saving,debt_in,debt_on',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'goal_id' => 'nullable|exists:goals,id',
            'status_id' => 'nullable|exists:statuses,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
            'is_recurring' => 'nullable|boolean',
            'recurring_interval_days' => 'nullable|integer|min:1',

            'files' => 'nullable',
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar',

            'delete_files' => 'nullable|array',
            'delete_files.*' => 'string',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        // Archivos actuales
        $existing = $transaction->files ?? [];

        // Eliminar archivos
        $toDelete = $request->input('delete_files', []);
        foreach ($toDelete as $file) {
            if (in_array($file, $existing) && Str::startsWith($file, 'transactions/')) {
                Storage::disk('public')->delete($file);
                $existing = array_values(array_diff($existing, [$file]));
            }
        }

        // Subir nuevos archivos
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $existing[] = $file->store('transactions', 'public');
            }
        }

        $validated['files'] = $existing;

        $transaction->update($validated);

        // Actualizar metas
        $this->updateGoalAmount($transaction);

        return response()->json($transaction);
    }

    /**
     * DELETE /api/v1/transactions/{id}
     */
    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('created_by', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Borrar archivos
        foreach ($transaction->files ?? [] as $file) {
            if (Str::startsWith($file, 'transactions/')) {
                Storage::disk('public')->delete($file);
            }
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    /**
     *  Lógica para actualizar metas
     */
    private function updateGoalAmount(Transaction $transaction)
    {
        if (!in_array($transaction->type, ['saving', 'debt_in', 'debt_on'])) {
            return;
        }

        if (!$transaction->goal_id) {
            return;
        }

        $goal = Goal::find($transaction->goal_id);
        if (!$goal)
            return;

        $total = Transaction::where('goal_id', $goal->id)
            ->whereIn('type', ['saving', 'debt_in', 'debt_on'])
            ->sum('amount');

        $goal->update(['current_amount' => $total]);
    }
}
