<?php

namespace App\Exports;

use App\Models\V1\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TransactionDataSheet implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    public function query()
    {
        $year = now()->year;
        return Transaction::query()
            ->whereYear('date', $year)
            ->latest('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'creado_por',
            'tipo',
            'monto',
            'fecha',
            'descripcion',
            'fuente',
            'id_de_socio',
            'categoria',
            'id_de_meta',
            'meta',
            'id_de_estado',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->created_by,
            $transaction->type,
            $transaction->amount,
            $transaction->date,
            $transaction->description,
            $transaction->source,
            $transaction->partner_id,
            $transaction->category,
            $transaction->goal_id,
            $transaction->goal,
            $transaction->status_id,
        ];
    }

    public function title(): string
    {
        return 'Transacciones';
    }
}
