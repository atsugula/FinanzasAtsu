<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\CreditoSimulacionExport;
use Illuminate\Validation\ValidationException;

class CreditoSimuladorController extends Controller
{
    private int $periodos = 8;

    public function index(Request $request)
    {
        [$prestamo, $interes, $abono, $periodos, $tabla] = $this->build($request);
        return view('pages.creditos.simulador', compact('prestamo', 'interes', 'abono', 'periodos', 'tabla'));
    }

    public function exportPdf(Request $request)
    {
        [$prestamo, $interes, $abono, $periodos, $tabla] = $this->build($request);
        $pdf = Pdf::loadView('pages.creditos.pdf', compact('prestamo', 'interes', 'abono', 'periodos', 'tabla'));
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

        // periodos opcional por request
        $periodosReq = $request->query('periodos', null);
        $periodosReq = ($periodosReq === null || $periodosReq === '') ? null : (int) $periodosReq;

        // Pantalla limpia si no mandan nada (incluye periodos)
        $hasParams = $request->hasAny(['prestamo', 'interes', 'abono', 'periodos']);
        if (!$hasParams) {
            return [$prestamo, $interes, $abono, ($periodosReq ?? $this->periodos), []];
        }

        $request->validate([
            'prestamo' => ['required', 'numeric', 'min:0'],
            'interes' => ['required', 'numeric', 'min:0'],
            'abono' => ['required', 'numeric', 'min:0'],
            'periodos' => ['nullable', 'numeric', 'min:1'],
        ]);

        // Si hay préstamo y abono 0, no hay forma de pagar (loop infinito)
        if ($prestamo > 0 && $abono <= 0) {
            throw ValidationException::withMessages([
                'abono' => 'El abono debe ser mayor a 0 para poder amortizar el préstamo.',
            ]);
        }

        $rate = $interes / 100;
        $saldo = $prestamo;

        // Regla: abono no puede ser menor al interés de la 1ª cuota (la más alta)
        $interesPrimera = $saldo * $rate;
        if ($abono < $interesPrimera) {
            throw ValidationException::withMessages([
                'abono' => 'El abono no puede ser menor al interés de la cuota (en la 1ª cuota sería ' . number_format($interesPrimera, 0, ',', '.') . ').',
            ]);
        }

        $tabla = [];
        $tabla[] = ['n' => 0, 'cuota' => 0, 'interes' => 0, 'capital' => 0, 'saldo' => $saldo];

        // Caso A: periodos viene -> calculo fijo
        if (!empty($periodosReq)) {
            $periodos = (int) $periodosReq;

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

        // Caso B: periodos NO viene -> calculo dinámico hasta saldo 0 (ajusta última cuota)
        $i = 0;
        $maxIter = 2000; // protección anti-loop eterno

        while ($saldo > 0 && $i < $maxIter) {
            $i++;

            $interesCuota = $saldo * $rate;
            $capital = $abono - $interesCuota;

            // Si el capital paga TODO el saldo restante, ajustamos última cuota para cerrar en 0
            if ($capital >= $saldo) {
                $capitalFinal = $saldo;
                $cuotaFinal = $interesCuota + $capitalFinal;
                $saldo = 0;

                $tabla[] = [
                    'n' => $i,
                    'cuota' => $cuotaFinal,
                    'interes' => $interesCuota,
                    'capital' => $capitalFinal,
                    'saldo' => $saldo,
                ];
                break;
            }

            // normal
            $saldo = $saldo - $capital;

            $tabla[] = [
                'n' => $i,
                'cuota' => $abono,
                'interes' => $interesCuota,
                'capital' => $capital,
                'saldo' => $saldo,
            ];
        }

        if ($i >= $maxIter) {
            throw ValidationException::withMessages([
                'abono' => 'No se pudo calcular porque se alcanzó el límite de iteraciones. Revisa abono e interés.',
            ]);
        }

        $periodos = $i;

        return [$prestamo, $interes, $abono, $periodos, $tabla];
    }
}
