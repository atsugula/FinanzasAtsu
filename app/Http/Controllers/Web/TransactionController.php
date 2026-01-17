<?php

namespace App\Http\Controllers\Web;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TransactionAttachment;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $month = $request->get('month', now()->format('Y-m'));

        [$start, $end] = $this->monthRangeFromString($month, $user->settings->month_start_day);

        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->with(['account:id,name', 'category:id,name,type'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'month', 'start', 'end'));
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $accounts = Account::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        // Por defecto mostramos categorías de gasto (puedes cambiarlo en la vista con el toggle)
        $categoriesIncome = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', 'income')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        $categoriesExpense = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', 'expense')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return view('transactions.create', compact('accounts', 'categoriesIncome', 'categoriesExpense'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],

            // opcional, varias imágenes
            'attachment_ids' => ['nullable', 'array', 'max:5'],
            'attachment_ids.*' => ['integer'],
        ]);

        $account = Account::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->findOrFail($data['account_id']);

        $category = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', $data['type'])
            ->findOrFail($data['category_id']);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'date' => $data['date'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'account_id' => $account->id,
            'category_id' => $category->id,
            'note' => $data['note'] ?? null,
        ]);

        // Guardar adjuntos (si vienen)
        // IDs de adjuntos temporales
        $attachmentIds = $request->input('attachment_ids', []);
        if (!is_array($attachmentIds))
            $attachmentIds = [];

        $attachments = TransactionAttachment::query()
            ->where('user_id', $user->id)
            ->whereIn('id', $attachmentIds)
            ->where('is_temp', true)
            ->whereNull('transaction_id')
            ->get();

        foreach ($attachments as $att) {
            // mover a carpeta final
            $newPath = "transactions/{$user->id}/{$transaction->id}/" . basename($att->path);
            if (Storage::disk('public')->exists($att->path)) {
                Storage::disk('public')->move($att->path, $newPath);
                $att->update([
                    'path' => $newPath,
                    'transaction_id' => $transaction->id,
                    'is_temp' => false,
                ]);
            } else {
                // si por alguna razón el archivo no existe, lo eliminamos del registro
                $att->delete();
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Movimiento guardado.');
    }

    public function edit(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        $accounts = Account::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categoriesIncome = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', 'income')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        $categoriesExpense = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', 'expense')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return view('transactions.edit', compact('transaction', 'accounts', 'categoriesIncome', 'categoriesExpense'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'integer'],
            'account_id' => ['required', 'integer'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],

            // opcional, varias imágenes
            'attachment_ids' => ['nullable', 'array', 'max:5'],
            'attachment_ids.*' => ['integer'],
        ]);

        $account = Account::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->findOrFail($data['account_id']);

        $category = Category::query()
            ->where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('type', $data['type'])
            ->findOrFail($data['category_id']);

        $transaction->update([
            'date' => $data['date'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'account_id' => $account->id,
            'category_id' => $category->id,
            'note' => $data['note'] ?? null,
        ]);

        // Guardar adjuntos (si vienen)
        // IDs de adjuntos temporales
        $attachmentIds = $request->input('attachment_ids', []);
        if (!is_array($attachmentIds))
            $attachmentIds = [];

        $attachments = TransactionAttachment::query()
            ->where('user_id', $user->id)
            ->whereIn('id', $attachmentIds)
            ->where('is_temp', true)
            ->whereNull('transaction_id')
            ->get();

        foreach ($attachments as $att) {
            // mover a carpeta final
            $newPath = "transactions/{$user->id}/{$transaction->id}/" . basename($att->path);
            if (Storage::disk('public')->exists($att->path)) {
                Storage::disk('public')->move($att->path, $newPath);
                $att->update([
                    'path' => $newPath,
                    'transaction_id' => $transaction->id,
                    'is_temp' => false,
                ]);
            } else {
                // si por alguna razón el archivo no existe, lo eliminamos del registro
                $att->delete();
            }
        }

        return redirect()->route('transactions.index')->with('success', 'Movimiento actualizado.');
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        $transaction->delete();

        return back()->with('success', 'Movimiento eliminado.');
    }

    private function ownedTransaction(int $userId, int $id): Transaction
    {
        return Transaction::query()
            ->where('user_id', $userId)
            ->with([
                'account:id,name',
                'category:id,name,type',
                'attachments:id,user_id,transaction_id,path',
            ])
            ->findOrFail($id);
    }

    private function monthRangeFromString(string $yyyyMm, int $monthStartDay): array
    {
        // yyyy-mm
        [$y, $m] = explode('-', $yyyyMm);
        $base = now()->setDate((int) $y, (int) $m, 1)->startOfMonth();

        $start = $base->copy()->day($monthStartDay);
        $end = $start->copy()->addMonthNoOverflow()->subDay();

        return [$start->toDateString(), $end->toDateString()];
    }
}
