<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    public function edit(Request $request)
    {
        $settings = $request->user()->settings;
        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $settings = $user->settings;

        $data = $request->validate([
            'currency' => ['required', 'string', 'max:8'],
            'month_start_day' => ['required', 'integer', 'min:1', 'max:28'],
        ]);

        $settings->update($data);

        return back()->with('success', 'Ajustes guardados.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($user) {
            $out = fopen('php://output', 'w');

            // Header simple y estable
            fputcsv($out, ['date', 'type', 'amount', 'account', 'category', 'note']);

            Transaction::query()
                ->where('user_id', $user->id)
                ->with(['account:id,name', 'category:id,name'])
                ->orderBy('date')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $t) {
                        fputcsv($out, [
                            $t->date,
                            $t->type,
                            $t->amount,
                            $t->account?->name,
                            $t->category?->name,
                            $t->note,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importCsv(Request $request)
    {
        // MVP: lo dejamos simple. Si el CSV está mal, se rechaza. Cero magia.
        $user = $request->user();

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $path = $data['file']->getRealPath();
        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);
        $expected = ['date', 'type', 'amount', 'account', 'category', 'note'];
        if ($header !== $expected) {
            return back()->withErrors(['file' => 'El CSV debe tener estas columnas: date,type,amount,account,category,note']);
        }

        $accountsByName = Account::where('user_id', $user->id)->get()->keyBy('name');
        $categoriesByName = Category::where('user_id', $user->id)->get()->keyBy('name');

        $created = 0;

        while (($row = fgetcsv($handle)) !== false) {
            [$date, $type, $amount, $accountName, $categoryName, $note] = $row;

            if (!in_array($type, ['income', 'expense'], true))
                continue;
            if (!is_numeric($amount) || (float) $amount <= 0)
                continue;

            $acc = $accountsByName->get($accountName);
            $cat = $categoriesByName->get($categoryName);

            // Si no existe cuenta/categoría, no inventamos: lo saltamos.
            if (!$acc || !$cat)
                continue;
            if ($cat->type !== $type)
                continue;

            Transaction::create([
                'user_id' => $user->id,
                'date' => $date,
                'type' => $type,
                'amount' => (float) $amount,
                'account_id' => $acc->id,
                'category_id' => $cat->id,
                'note' => $note ?: null,
            ]);

            $created++;
        }

        fclose($handle);

        return back()->with('success', "Importación lista. Movimientos creados: {$created}");
    }
}
