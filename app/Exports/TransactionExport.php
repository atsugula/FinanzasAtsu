<?php

namespace App\Exports;

use App\Models\V1\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TransactionExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        return [
            new TransactionDataSheet(),
            new TransactionSummarySheet()
        ];
    }
}
