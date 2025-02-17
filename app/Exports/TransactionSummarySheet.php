<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;

class TransactionSummarySheet implements WithHeadings, WithTitle, FromArray
{
    public function headings(): array
    {
        return [
            'Tipo',
            'Total',
        ];
    }

    public function title(): string
    {
        return 'Resumen';
    }

    // Para exportar las fórmulas como fórmula
    public function array(): array
    {
        return [
            ['Ahorro', '=SUMAR.SI.CONJUNTO(Transacciones!D:D, Transacciones!C:C, "A", Transacciones!L:L, 2)'],
            ['Egreso', '=SUMAR.SI.CONJUNTO(Transacciones!D:D, Transacciones!C:C, "E", Transacciones!L:L, 2)'],
            ['Ingreso', '=SUMAR.SI.CONJUNTO(Transacciones!D:D, Transacciones!C:C, "I", Transacciones!L:L, 2)'],
            ['Balance', '=B4-(B2+B3)'],
        ];
    }
}
