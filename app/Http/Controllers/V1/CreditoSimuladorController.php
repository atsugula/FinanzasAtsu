<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\CreditoSimulacionExport;

class CreditoSimuladorController extends Controller
{
    private int $periodos = 8; // tal cual el Excel (8 filas de cuota)

    public function index(Request $request)
    {
        [$prestamo, $interes, $abono, $periodos, $tabla] = $this->build($request);

        return view('creditos.simulador', compact('prestamo', 'interes', 'abono', 'periodos', 'tabla'));
    }

    public function exportPdf(Request $request)
    {
        [$prestamo, $interes, $abono, $periodos, $tabla] = $this->build($request);

        $pdf = Pdf::loadView('creditos.pdf', compact('prestamo', 'interes', 'abono', 'periodos', 'tabla'));
        return $pdf->download('simulacion_credito.pdf');
    }

    public function exportExcel(Request $request)
    {
        [$prestamo, $interes, $abono, $periodos, $tabla] = $this->build($request);

        return Excel::download(new CreditoSimulacionExport($prestamo, $interes, $abono, $periodos, $tabla), 'simulacion_credito.xlsx');
    }

    private function build(Request $request): array
    {
        $prestamo = (float) $request->query('prestamo', 25000000);
        $interes = (float) $request->query('interes', 2);
        $abono = (float) $request->query('abono', 4000000);
        $periodos = $this->periodos;

        // Si no han mandado nada, no calculamos tabla (pantalla limpia)
        $hasParams = $request->hasAny(['prestamo', 'interes', 'abono']);
        if (!$hasParams) {
            return [$prestamo, $interes, $abono, $periodos, []];
        }

        $request->validate([
            'prestamo' => ['required', 'numeric', 'min:0'],
            'interes' => ['required', 'numeric', 'min:0'],
            'abono' => ['required', 'numeric', 'min:0'],
        ]);

        $rate = $interes / 100;
        $saldo = $prestamo;

        // Regla: abono no puede ser menor al interés de la cuota
        $interesPrimera = $saldo * $rate;
        if ($abono < $interesPrimera) {
            abort(422, 'El abono no puede ser menor al interés de la cuota (en la 1ª cuota sería ' . number_format($interesPrimera, 0, ',', '.') . ').');
        }

        $tabla = [];
        $tabla[] = ['n' => 0, 'cuota' => 0, 'interes' => 0, 'capital' => 0, 'saldo' => $saldo];

        for ($i = 1; $i <= $periodos; $i++) {
            $interesCuota = $saldo * $rate;
            $capital = $abono - $interesCuota;
            $saldo = $saldo - $capital;

            $tabla[] = [
                'n' => $i,
                'cuota' => $abono,
                'interes' => $interesCuota,
                'capital' => $capital,
                'saldo' => $saldo,
            ];
        }

        return [$prestamo, $interes, $abono, $periodos, $tabla];
    }
}
