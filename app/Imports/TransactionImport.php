<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionImport implements WithMultipleSheets
{
    /**
     * Indica qué hojas se deben importar.
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            'importar' => new TransactionSheetImport(), // Importar solo la hoja llamada 'importar'
        ];
    }
}
