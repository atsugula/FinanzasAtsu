<div class="table-responsive">
    <table class="table table-sm table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:70px">#</th>
                <th class="text-end">Cuota</th>
                <th class="text-end">Interés</th>
                <th class="text-end">Capital</th>
                <th class="text-end">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tabla as $row)
                <tr>
                    <td class="text-muted">{{ $row['n'] }}</td>
                    <td class="text-end">${{ number_format($row['cuota'], 0, ',', '.') }}</td>
                    <td class="text-end">${{ number_format($row['interes'], 0, ',', '.') }}</td>
                    <td class="text-end">${{ number_format($row['capital'], 0, ',', '.') }}</td>
                    <td class="text-end">${{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Ingresa valores y dale <span class="fw-semibold">Simular</span>.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
