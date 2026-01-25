<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Account;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SelectsController extends Controller
{
    public function getData(Request $request)
    {
        $user = $request->user();

        // fields puede venir como:
        // 1) JSON string: ["accounts","categories"]
        // 2) CSV: accounts,categories
        // 3) array: fields[]=accounts&fields[]=categories
        $fieldsRaw = $request->query('fields', []);
        $fields = $this->parseFields($fieldsRaw);

        if (empty($fields)) {
            return response()->json([
                'success' => false,
                'message' => 'fields es requerido',
                'data' => new \stdClass(),
            ], 422);
        }

        $data = [];

        if (in_array('accounts', $fields, true)) {
            $data['accounts'] = Account::with('transactions')
                ->where('user_id', $user->id)
                ->where('is_archived', false)
                ->orderBy('name')
                ->get(['id', 'name', 'current_balance', 'current_balance_formatted']);
        }

        if (in_array('categories', $fields, true)) {
            $data['categories'] = Category::with('transactions')
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
        // array directo
        if (is_array($fieldsRaw)) {
            return $this->sanitizeFields($fieldsRaw);
        }

        // string
        if (is_string($fieldsRaw)) {
            $fieldsRaw = trim($fieldsRaw);

            // JSON string
            if (str_starts_with($fieldsRaw, '[')) {
                $decoded = json_decode($fieldsRaw, true);
                if (is_array($decoded)) {
                    return $this->sanitizeFields($decoded);
                }
            }

            // CSV "accounts,categories"
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
        $allowed = ['accounts', 'categories'];
        return array_values(array_unique(array_filter(array_map(
            fn($f) => in_array(($f = trim((string) $f)), $allowed, true) ? $f : null,
            $fields
        ))));
    }
}
