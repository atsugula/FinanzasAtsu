<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class SelectsController extends Controller
{
    private const ALLOWED_FIELDS = ['accounts', 'categories'];

    public function getData(Request $request)
    {
        $user = $request->user();

        $fields = $this->parseFields($request->query('fields'));

        if (empty($fields)) {
            throw ValidationException::withMessages([
                'fields' => ['El parámetro fields es requerido y debe incluir al menos uno válido: accounts, categories.'],
            ]);
        }

        $data = [];

        if (in_array('accounts', $fields, true)) {
            // Balance = initial_balance + SUM(transactions.amount)
            $accounts = Account::query()
                ->where('user_id', $user->id)
                ->where('is_archived', false)
                ->withSum('transactions as transactions_sum_amount', 'amount')
                ->orderBy('name')
                ->get(['id', 'name', 'initial_balance']);

            $data['accounts'] = $accounts->map(function (Account $a) {
                $balance = (float) $a->initial_balance + (float) ($a->transactions_sum_amount ?? 0);

                return [
                    'id' => $a->id,
                    'name' => $a->name,
                    'current_balance' => $balance,
                    'current_balance_formatted' => number_format($balance, 2, '.', ','),
                ];
            })->values();
        }

        if (in_array('categories', $fields, true)) {
            $data['categories'] = Category::query()
                ->where('user_id', $user->id)
                ->where('is_archived', false)
                ->orderBy('name')
                ->get(['id', 'name', 'type', 'icon']);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    private function parseFields(mixed $fieldsRaw): array
    {
        if (is_null($fieldsRaw)) {
            return [];
        }

        // fields[]=accounts&fields[]=categories
        if (is_array($fieldsRaw)) {
            return $this->sanitizeFields($fieldsRaw);
        }

        if (is_string($fieldsRaw)) {
            $fieldsRaw = trim($fieldsRaw);

            // JSON string: ["accounts","categories"]
            if ($fieldsRaw !== '' && str_starts_with($fieldsRaw, '[')) {
                $decoded = json_decode($fieldsRaw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $this->sanitizeFields($decoded);
                }
            }

            // CSV: accounts,categories
            if (str_contains($fieldsRaw, ',')) {
                return $this->sanitizeFields(explode(',', $fieldsRaw));
            }

            // single
            return $this->sanitizeFields([$fieldsRaw]);
        }

        return [];
    }

    private function sanitizeFields(array $fields): array
    {
        $fields = array_map(fn($f) => trim(mb_strtolower((string) $f)), $fields);

        $fields = array_filter($fields, fn($f) => in_array($f, self::ALLOWED_FIELDS, true));

        return array_values(array_unique($fields));
    }
}
