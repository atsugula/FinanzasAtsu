<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\V1\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class TransactionSheetImport implements ToModel, WithHeadingRow, WithCalculatedFormulas
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $data = [
            'created_by'   => $row['creado_por'] ?? null,
            'type'         => $row['tipo'] ?? null,
            'amount'       => $row['monto'] ?? null,
            'date'         => $this->parseDate($row['fecha'] ?? null),
            'description'  => $row['descripcion'] ?? null,
            'source'       => $row['fuente'] ?? null,
            'partner_id'   => $row['id_de_socio'] ?? null,
            'category'     => $row['categoria'] ?? null,
            'goal_id'      => $row['id_de_meta'] ?? null,
            'goal'         => $row['meta'] ?? null,
            'status_id'    => $row['id_de_estado'] ?? null,
        ];

        if (!empty($row['id'])) {
            $transaction = Transaction::find($row['id']);
            if ($transaction) {
                $transaction->update($data);
                return $transaction;
            }
        }

        return new Transaction($data);
    }

    /**
     * Convierte la fecha a un formato válido para la base de datos (YYYY-MM-DD).
     *
     * @param string|float|null $date
     * @return string|null
     */
    protected function parseDate($date)
    {
        if (is_numeric($date)) {
            // Convierte la fecha en formato Excel a formato 'Y-m-d'
            return Carbon::createFromTimestamp(($date - 25569) * 86400)->format('Y-m-d');
        }

        // Si no es un número, intenta convertirla directamente
        return $date ? Carbon::parse($date)->format('Y-m-d') : null;
    }
}
