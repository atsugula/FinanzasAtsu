@php
    $rawAmount = old('amount', $transaction->amount ?? '');
    // Mostrar como $ 1.234.567 (sin decimales, típico COP). JS refrescará si hace falta.
    $displayAmount = $rawAmount !== '' ? '$ ' . number_format((float) $rawAmount, 0, ',', '.') : '';
    $is_recurrent = old('is_recurring', $transaction->is_recurring ?? false);
@endphp

<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            {{-- Tipo --}}
            <div class="col-md-6 mb-3">
                <label for="type" class="form-label">Tipo</label>
                <select name="type" id="type" class="form-select select2" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($types as $key => $value)
                        <option value="{{ $key }}"
                            {{ old('type', $transaction->type ?? '') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Monto (visible formateado + hidden real) --}}
            <div class="col-md-6 mb-3">
                <label for="amount_display" class="form-label">Monto</label>

                {{-- campo visible formateado --}}
                <input type="text" id="amount_display" class="form-control" autocomplete="off"
                    value="{{ $displayAmount }}" placeholder="$ 0" required>

                {{-- campo real enviado al servidor --}}
                <input type="hidden" name="amount" id="amount" value="{{ $rawAmount }}" min="0">
            </div>
        </div>

        <div class="row">
            {{-- Categoría --}}
            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select name="category_id" id="category_id" class="form-select select2">
                    <option value="">-- Ninguna --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Meta (opcional) --}}
            <div class="col-md-6 mb-3 {{ old('type', $transaction->type ?? '') === 'saving' ? '' : 'd-none' }}"
                id="goal_container">
                <label for="goal_id" class="form-label">Meta (opcional)</label>
                <select name="goal_id" id="goal_id" class="form-select select2">
                    <option value="">-- Ninguna --</option>
                    @foreach ($goals ?? [] as $goal)
                        <option value="{{ $goal->id }}"
                            {{ old('goal_id', $transaction->goal_id ?? '') == $goal->id ? 'selected' : '' }}>
                            {{ $goal->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Estado --}}
            <div class="col-md-6 mb-3">
                <label for="status_id" class="form-label">Estado</label>
                <select name="status_id" id="status_id" class="form-select select2" required>
                    <option value="">-- Seleccione --</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}"
                            {{ old('status_id', $transaction->status_id ?? '') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha --}}
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ old('date', isset($transaction->date) ? \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') : '') }}"
                    required>
            </div>

            {{-- Nota --}}
            <div class="col-md-6 mb-3">
                <label for="note" class="form-label">Nota</label>
                <textarea name="note" id="note" class="form-control" rows="1">{{ old('note', $transaction->note ?? '') }}</textarea>
            </div>
        </div>

        <div class="row">
            {{-- Es recurrente --}}
            <div class="col-md-6 mb-3">
                <div class="form-check mt-4">
                    <input type="checkbox" class="form-check-input" id="is_recurring" name="is_recurring"
                        {{ $is_recurrent ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_recurring">¿Es recurrente?</label>
                </div>
            </div>

            {{-- Intervalo de recurrencia --}}
            <div class="col-md-6 mb-3 {{ $is_recurrent ? '' : 'd-none' }}" id="recurrence_interval_container">
                <label for="recurring_interval_days" class="form-label">Intervalo de recurrencia (días)</label>
                <input type="number" min="1" name="recurring_interval_days" id="recurring_interval_days"
                    class="form-control"
                    value="{{ old('recurring_interval_days', $transaction->recurring_interval_days ?? '') }}">
            </div>
        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
</div>
