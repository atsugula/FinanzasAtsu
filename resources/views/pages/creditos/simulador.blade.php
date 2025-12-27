@extends('layouts.app')

@section('content')
    <div class="container py-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h3 class="mb-0">Simulador de Créditos</h3>
                <small class="text-muted">
                    Interés (%) editable · Abono fijo editable · Periodo opcional (vacío = calcula hasta saldo 0)
                </small>
            </div>

            <div class="d-flex gap-2">
                <a class="btn btn-outline-danger {{ empty($tabla) ? 'disabled' : '' }}"
                    href="{{ empty($tabla) ? '#' : route('creditos.export.pdf', request()->query()) }}">
                    Descargar PDF
                </a>

                <a class="btn btn-outline-success {{ empty($tabla) ? 'disabled' : '' }}"
                    href="{{ empty($tabla) ? '#' : route('creditos.export.excel', request()->query()) }}">
                    Descargar Excel
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Ojo:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('creditos.index') }}" class="row g-3 align-items-end"
                    id="formSimulador">

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">Préstamo</label>
                        <input type="number" class="form-control" name="prestamo" min="0" step="1"
                            value="{{ old('prestamo', request('prestamo', $prestamo ?? 25000000)) }}" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">Interés (%)</label>
                        <input type="number" class="form-control" name="interes" min="0" step="0.01"
                            value="{{ old('interes', request('interes', $interes ?? 2)) }}" required>
                        <div class="form-text">Ej: 2 = 2%</div>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">Abono / Cuota (fija)</label>
                        <input type="number" class="form-control" name="abono" min="0" step="1"
                            value="{{ old('abono', request('abono', $abono ?? 4000000)) }}" required>
                        <div class="form-text" id="hintInteres"></div>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">
                            Periodo (opcional)
                            <span class="text-muted fw-normal">· vacío = auto</span>
                        </label>
                        <input type="number" class="form-control" name="periodos" min="1" step="1"
                            value="{{ old('periodos', request('periodos', '')) }}" placeholder="Auto">
                        <div class="form-text">Si lo dejas vacío, calcula cuotas hasta saldo 0.</div>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            Simular
                        </button>

                        <a href="{{ route('creditos.index') }}" class="btn btn-light">
                            Limpiar
                        </a>

                        {{-- <span class="ms-auto badge text-bg-light border">
                            Cuotas calculadas: <span class="fw-semibold">{{ $periodos ?? '-' }}</span>
                        </span> --}}
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <div class="fw-semibold">Tabla</div>

                    @if (!empty($tabla))
                        <small class="text-muted">
                            Préstamo: <span class="fw-semibold">${{ number_format($prestamo, 0, ',', '.') }}</span>
                            · Interés: <span
                                class="fw-semibold">{{ rtrim(rtrim(number_format($interes, 2, '.', ''), '0'), '.') }}%</span>
                            · Abono: <span class="fw-semibold">${{ number_format($abono, 0, ',', '.') }}</span>
                            · Periodos: <span class="fw-semibold">{{ $periodos }}</span>
                        </small>
                    @endif
                </div>

                @include('pages.creditos._tabla', ['tabla' => $tabla ?? []])
            </div>
        </div>

    </div>

    <script>
        (function() {
            const prestamo = document.querySelector('input[name="prestamo"]');
            const interes = document.querySelector('input[name="interes"]');
            const abono = document.querySelector('input[name="abono"]');
            const hint = document.getElementById('hintInteres');

            function money(n) {
                return new Intl.NumberFormat('es-CO').format(Math.round(n || 0));
            }

            function updateHint() {
                const p = parseFloat(prestamo.value || 0);
                const r = (parseFloat(interes.value || 0) / 100);
                const i = p * r; // interés primera cuota (máximo)
                hint.textContent = `Interés de la 1ª cuota aprox: $${money(i)}. El abono no puede ser menor a eso.`;
            }

            [prestamo, interes].forEach(el => el.addEventListener('input', updateHint));
            updateHint();
        })();
    </script>
@endsection
