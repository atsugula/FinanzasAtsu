<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'type' => ['nullable', 'in:income,expense'],
            'account_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $q = Transaction::query()
            ->where('user_id', auth()->id())
            ->with(['account:id,name', 'category:id,name,type'])
            ->orderByDesc('date')
            ->orderByDesc('id');

        if ($request->filled('type')) {
            $q->where('type', $request->string('type'));
        }
        if ($request->filled('account_id')) {
            $q->where('account_id', $request->integer('account_id'));
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('month')) {
            [$y, $m] = explode('-', $request->string('month'));
            $from = sprintf('%04d-%02d-01', (int) $y, (int) $m);
            $to = date('Y-m-d', strtotime("$from +1 month"));
            $q->whereBetween('date', [$from, date('Y-m-d', strtotime("$to -1 day"))]);
        }

        $perPage = $request->integer('per_page', 20);
        return TransactionResource::collection($q->paginate($perPage));
    }

    public function store(StoreTransactionRequest $request)
    {
        $tx = Transaction::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        $tx->load(['account:id,name', 'category:id,name,type']);

        return (new TransactionResource($tx))->response()->setStatusCode(201);
    }

    public function show(int $id)
    {
        $tx = Transaction::where('user_id', auth()->id())
            ->with(['account:id,name', 'category:id,name,type'])
            ->findOrFail($id);

        return new TransactionResource($tx);
    }

    public function update(UpdateTransactionRequest $request, int $id)
    {
        $tx = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $tx->update($request->validated());

        $tx->load(['account:id,name', 'category:id,name,type']);

        return new TransactionResource($tx);
    }

    public function destroy(int $id)
    {
        $tx = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $tx->delete();

        return response()->json(['message' => 'Movimiento eliminado.']);
    }
}
