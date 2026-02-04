<?php

namespace App\Http\Controllers\Api\V1;

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
        // $month = $request->query('month', now()->format('Y-m'));

        $monthStartDay = (int) ($user->settings->month_start_day ?? 1);
        // [$start, $end] = $this->monthRangeFromString($month, $monthStartDay);

        $transactions = Transaction::query()
            ->where('user_id', $user->id)
            // ->whereBetween('date', [$start, $end])
            ->with(['account:id,name', 'category:id,name,type', 'attachments:id,user_id,transaction_id,path'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return response()->json([
            'success' => true,
            // 'filters' => [
            //     'month' => $month,
            //     'start' => $start,
            //     'end' => $end,
            // ],
            'data' => $transactions,
        ]);
    }

    // Opcional: reemplazo de create/edit (datos para pintar forms)
    public function meta(Request $request)
    {
        $user = $request->user();

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

        return response()->json([
            'success' => true,
            'data' => [
                'accounts' => $accounts,
                'categories' => [
                    'income' => $categoriesIncome,
                    'expense' => $categoriesExpense,
                ],
            ],
        ]);
    }

    public function show(Request $request, Transaction $transaction)
    {
        $user = $request->user();

        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
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

        $this->attachTempAttachments($request, $user->id, $transaction->id);

        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        return response()->json([
            'success' => true,
            'message' => 'Movimiento guardado.',
            'data' => $transaction,
        ], 200);
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

        $this->attachTempAttachments($request, $user->id, $transaction->id);

        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        return response()->json([
            'success' => true,
            'message' => 'Movimiento actualizado.',
            'data' => $transaction,
        ]);
    }

    public function destroy(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        $transaction = $this->ownedTransaction($user->id, $transaction->id);

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Movimiento eliminado.',
        ]);
    }

    private function attachTempAttachments(Request $request, int $userId, int $transactionId): void
    {
        $attachmentIds = $request->input('attachment_ids', []);
        if (!is_array($attachmentIds))
            $attachmentIds = [];

        $attachments = TransactionAttachment::query()
            ->where('user_id', $userId)
            ->whereIn('id', $attachmentIds)
            ->where('is_temp', true)
            ->whereNull('transaction_id')
            ->get();

        foreach ($attachments as $att) {
            $newPath = "transactions/{$userId}/{$transactionId}/" . basename($att->path);

            if (Storage::disk('public')->exists($att->path)) {
                Storage::disk('public')->move($att->path, $newPath);

                $att->update([
                    'path' => $newPath,
                    'transaction_id' => $transactionId,
                    'is_temp' => false,
                ]);
            } else {
                $att->delete();
            }
        }
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
        [$y, $m] = explode('-', $yyyyMm);
        $base = now()->setDate((int) $y, (int) $m, 1)->startOfMonth();

        $start = $base->copy()->day($monthStartDay);
        $end = $start->copy()->addMonthNoOverflow()->subDay();

        return [$start->toDateString(), $end->toDateString()];
    }
}
