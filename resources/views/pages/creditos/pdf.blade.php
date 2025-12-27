<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Simulación de Crédito</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            margin: 0 0 6px;
        }

        .muted {
            color: #666;
            margin: 0 0 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f3f3f3;
            text-align: left;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2>Simulación de Crédito</h2>

    <p class="muted">
        Préstamo: ${{ number_format($prestamo, 0, ',', '.') }} ·
        Interés: {{ rtrim(rtrim(number_format($interes, 2, '.', ''), '0'), '.') }}% ·
        Abono: ${{ number_format($abono, 0, ',', '.') }} ·
        Periodos: {{ $periodos }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="right">Cuota</th>
                <th class="right">Interés</th>
                <th class="right">Capital</th>
                <th class="right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tabla as $row)
                <tr>
                    <td>{{ $row['n'] }}</td>
                    <td class="right">${{ number_format($row['cuota'], 0, ',', '.') }}</td>
                    <td class="right">${{ number_format($row['interes'], 0, ',', '.') }}</td>
                    <td class="right">${{ number_format($row['capital'], 0, ',', '.') }}</td>
                    <td class="right">${{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
