<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CreditoSimulacionExport implements FromView
{
    public function __construct(
        public float $prestamo,
        public float $interes,
        public float $abono,
        public int $periodos,
        public array $tabla
    ) {
    }

    public function view(): View
    {
        return view('creditos.excel', [
            'prestamo' => $this->prestamo,
            'interes' => $this->interes,
            'abono' => $this->abono,
            'periodos' => $this->periodos,
            'tabla' => $this->tabla,
        ]);
    }
}
