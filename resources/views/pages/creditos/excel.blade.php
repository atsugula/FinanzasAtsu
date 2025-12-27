<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Cuota</th>
            <th>Interés</th>
            <th>Capital</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tabla as $row)
            <tr>
                <td>{{ $row['n'] }}</td>
                <td>{{ $row['cuota'] }}</td>
                <td>{{ $row['interes'] }}</td>
                <td>{{ $row['capital'] }}</td>
                <td>{{ $row['saldo'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
