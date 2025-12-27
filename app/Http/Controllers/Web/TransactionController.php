<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
            'type' => ['nullable', 'in:income,expense'],
            'account_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
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
            $end = date('Y-m-d', strtotime("$to -1 day"));
            $q->whereBetween('date', [$from, $end]);
        }

        $transactions = $q->paginate(25)->withQueryString();

        $accounts = Account::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('type')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return view('transactions.index', compact('transactions', 'accounts', 'categories'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('type')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return view('transactions.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        $data = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'account_id' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id')->where(fn($q) => $q->where('user_id', $userId)),
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn($q) => $q->where('user_id', $userId)),
            ],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        // Asegura que la categoría coincide con el tipo del movimiento (simple y sano)
        $catType = Category::where('user_id', $userId)->where('id', $data['category_id'])->value('type');
        if ($catType !== $data['type']) {
            return back()->withInput()->withErrors([
                'category_id' => 'La categoría no coincide con el tipo (Ingreso/Gasto).'
            ]);
        }

        Transaction::create([
            'user_id' => $userId,
            ...$data,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Movimiento creado.');
    }

    public function show(int $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())
            ->with(['account:id,name', 'category:id,name,type'])
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    public function edit(int $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

        $accounts = Account::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::where('user_id', auth()->id())
            ->where('is_archived', false)
            ->orderBy('type')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return view('transactions.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $userId = auth()->id();

        $transaction = Transaction::where('user_id', $userId)->findOrFail($id);

        $data = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'account_id' => [
                'required',
                'integer',
                Rule::exists('accounts', 'id')->where(fn($q) => $q->where('user_id', $userId)),
            ],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn($q) => $q->where('user_id', $userId)),
            ],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $catType = Category::where('user_id', $userId)->where('id', $data['category_id'])->value('type');
        if ($catType !== $data['type']) {
            return back()->withInput()->withErrors([
                'category_id' => 'La categoría no coincide con el tipo (Ingreso/Gasto).'
            ]);
        }

        $transaction->update($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Movimiento actualizado.');
    }

    public function destroy(int $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Movimiento eliminado.');
    }

    /* =========================
     * Import / Export (MVP)
     * ========================= */

    public function importView()
    {
        // Vista con instrucciones + upload
        return view('transactions.import');
    }

    /**
     * CSV esperado (con headers):
     * date,type,amount,account,category,note
     * 2025-12-01,expense,25000,Efectivo,Mercado,Pan y huevos
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $userId = auth()->id();
        $path = $request->file('file')->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['file' => 'No se pudo leer el archivo.']);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['file' => 'CSV vacío o inválido.']);
        }

        $header = array_map(fn($h) => strtolower(trim($h)), $header);
        $required = ['date', 'type', 'amount', 'account', 'category', 'note'];
        foreach ($required as $col) {
            if (!in_array($col, $header, true)) {
                fclose($handle);
                return back()->withErrors(['file' => "Falta la columna: {$col}"]);
            }
        }

        $idx = array_flip($header);

        $created = 0;
        $errors = 0;

        DB::transaction(function () use ($handle, $idx, $userId, &$created, &$errors) {
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    $date = trim($row[$idx['date']] ?? '');
                    $type = trim($row[$idx['type']] ?? '');
                    $amount = (float) trim($row[$idx['amount']] ?? '0');
                    $accountName = trim($row[$idx['account']] ?? '');
                    $categoryName = trim($row[$idx['category']] ?? '');
                    $note = trim($row[$idx['note']] ?? '');

                    if (!$date || !in_array($type, ['income', 'expense'], true) || $amount <= 0 || !$accountName || !$categoryName) {
                        $errors++;
                        continue;
                    }

                    $account = Account::firstOrCreate(
                        ['user_id' => $userId, 'name' => $accountName],
                        ['initial_balance' => 0, 'is_archived' => false]
                    );

                    $category = Category::firstOrCreate(
                        ['user_id' => $userId, 'type' => $type, 'name' => $categoryName],
                        ['is_archived' => false]
                    );

                    Transaction::create([
                        'user_id' => $userId,
                        'date' => $date,
                        'type' => $type,
                        'amount' => $amount,
                        'account_id' => $account->id,
                        'category_id' => $category->id,
                        'note' => $note ?: null,
                    ]);

                    $created++;
                } catch (\Throwable $e) {
                    $errors++;
                    continue;
                }
            }
        });

        fclose($handle);

        return redirect()->route('transactions.index')
            ->with('success', "Importación lista. Creados: {$created}. Errores: {$errors}.");
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $userId = auth()->id();

        $q = Transaction::query()
            ->where('user_id', $userId)
            ->with(['account:id,name', 'category:id,name,type'])
            ->orderBy('date')
            ->orderBy('id');

        if ($request->filled('from')) {
            $q->whereDate('date', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $q->whereDate('date', '<=', $request->date('to'));
        }

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');

            fputcsv($out, ['date', 'type', 'amount', 'account', 'category', 'note']);

            $q->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $tx) {
                    fputcsv($out, [
                        optional($tx->date)->format('Y-m-d'),
                        $tx->type,
                        (float) $tx->amount,
                        $tx->account?->name,
                        $tx->category?->name,
                        $tx->note,
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
