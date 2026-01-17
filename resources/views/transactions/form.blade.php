@php
    $transaction =
        $transaction ??
        (object) [
            'type' => 'expense',
            'amount' => null,
            'category_id' => null,
            'account_id' => null,
            'date' => now()->toDateString(),
            'note' => null,
        ];

    $currentType = old('type', $transaction->type ?? 'expense');

    // Listas vienen del controller:
    // $accounts, $categoriesIncome, $categoriesExpense
    $categoriesIncome = $categoriesIncome ?? collect();
    $categoriesExpense = $categoriesExpense ?? collect();
    $accounts = $accounts ?? collect();

    $categoriesIncomeOptions = $categoriesIncome->pluck('name', 'id')->toArray();
    $categoriesExpenseOptions = $categoriesExpense->pluck('name', 'id')->toArray();
    $accountsOptions = $accounts->pluck('name', 'id')->toArray();

    // Para preseleccionar en edit: enviamos el valor al select del tipo correcto
    $oldCategoryId = old('category_id', $transaction->category_id);
    $oldCategoryIncomeId = old('category_income_id', $currentType === 'income' ? $oldCategoryId : null);
    $oldCategoryExpenseId = old('category_expense_id', $currentType === 'expense' ? $oldCategoryId : null);
@endphp

<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

            {{-- Monto --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::number('amount', old('amount', $transaction->amount), [
                        'class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''),
                        'placeholder' => __('E.g. 25000'),
                        'step' => '0.01',
                        'min' => '0.01',
                    ]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Tipo (Ingreso/Gasto) --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}

                    {{-- Toggle UX: Income / Expense --}}
                    <div class="btn-group d-flex" role="group" aria-label="type">
                        <label class="btn btn-outline-success {{ $currentType === 'income' ? 'active' : '' }} w-50"
                            style="cursor:pointer;">
                            <input type="radio" name="type" value="income" autocomplete="off"
                                {{ $currentType === 'income' ? 'checked' : '' }}>
                            {{ __('Income') }}
                        </label>
                        <label class="btn btn-outline-danger {{ $currentType === 'expense' ? 'active' : '' }} w-50"
                            style="cursor:pointer;">
                            <input type="radio" name="type" value="expense" autocomplete="off"
                                {{ $currentType === 'expense' ? 'checked' : '' }}>
                            {{ __('Expense') }}
                        </label>
                    </div>

                    {!! $errors->first('type', '<div class="invalid-feedback d-block">:message</div>') !!}
                </div>
            </div>

            {{-- Categoría (filtrada por tipo) --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('category_id', __('Category')) }}

                    {{-- ESTE ES EL ÚNICO CAMPO REAL QUE SE ENVÍA --}}
                    {{ Form::hidden('category_id', $oldCategoryId, ['id' => 'category_id_real']) }}

                    {{-- Select Income (NO se envía directo, solo alimenta el hidden) --}}
                    <div class="{{ $currentType === 'income' ? '' : 'd-none' }}" id="category_income_wrap">
                        {{ Form::select('category_income_id', $categoriesIncomeOptions, $oldCategoryIncomeId, [
                            'class' => 'form-control select2' . ($errors->has('category_id') ? ' is-invalid' : ''),
                            'placeholder' => __('Select a category'),
                            'id' => 'category_income',
                        ]) }}
                    </div>

                    {{-- Select Expense (NO se envía directo, solo alimenta el hidden) --}}
                    <div class="{{ $currentType === 'expense' ? '' : 'd-none' }}" id="category_expense_wrap">
                        {{ Form::select('category_expense_id', $categoriesExpenseOptions, $oldCategoryExpenseId, [
                            'class' => 'form-control select2' . ($errors->has('category_id') ? ' is-invalid' : ''),
                            'placeholder' => __('Select a category'),
                            'id' => 'category_expense',
                        ]) }}
                    </div>

                    {!! $errors->first('category_id', '<div class="invalid-feedback d-block">:message</div>') !!}
                </div>
            </div>

            {{-- Cuenta --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('account_id', __('Account')) }}
                    {{ Form::select('account_id', $accountsOptions, old('account_id', $transaction->account_id), [
                        'class' => 'form-control select2' . ($errors->has('account_id') ? ' is-invalid' : ''),
                        'placeholder' => __('Select an account'),
                    ]) }}
                    {!! $errors->first('account_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Fecha --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', old('date', optional($transaction->date)->format('Y-m-d') ?? now()->toDateString()), [
                        'class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''),
                    ]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Nota --}}
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('note', __('Note')) }}
                    {{ Form::text('note', old('note', $transaction->note), [
                        'class' => 'form-control' . ($errors->has('note') ? ' is-invalid' : ''),
                        'placeholder' => __('Optional'),
                        'autocomplete' => 'off',
                    ]) }}
                    {!! $errors->first('note', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
</div>

@push('js')
    <script>
        (function() {
            function initTxForm() {
                const wrapIncome = document.getElementById('category_income_wrap');
                const wrapExpense = document.getElementById('category_expense_wrap');
                const real = document.getElementById('category_id_real');

                const inc = document.getElementById('category_income');
                const exp = document.getElementById('category_expense');

                if (!wrapIncome || !wrapExpense || !real) return;

                const radios = document.querySelectorAll('input[name="type"]');

                function setActiveButtons(selected) {
                    // Mantener el estilo "active" sin depender del plugin
                    radios.forEach(r => {
                        const label = r.closest('label');
                        if (!label) return;
                        label.classList.toggle('active', r.value === selected);
                    });
                }

                function syncCategoryToHidden() {
                    const selectedType = document.querySelector('input[name="type"]:checked')?.value || 'expense';
                    const value = (selectedType === 'income' ? inc?.value : exp?.value) || '';
                    real.value = value;
                }

                function toggle() {
                    const selected = document.querySelector('input[name="type"]:checked')?.value || 'expense';

                    wrapIncome.classList.toggle('d-none', selected !== 'income');
                    wrapExpense.classList.toggle('d-none', selected !== 'expense');

                    setActiveButtons(selected);

                    // Si el usuario cambia tipo, limpiamos el select del otro tipo para evitar valores “fantasma”
                    if (selected === 'income' && exp) {
                        exp.value = '';
                        if (window.jQuery && jQuery(exp).data('select2')) jQuery(exp).trigger('change.select2');
                    }
                    if (selected === 'expense' && inc) {
                        inc.value = '';
                        if (window.jQuery && jQuery(inc).data('select2')) jQuery(inc).trigger('change.select2');
                    }

                    // y sincronizamos el hidden
                    syncCategoryToHidden();
                }

                // listeners
                radios.forEach(r => r.addEventListener('change', toggle));
                if (inc) inc.addEventListener('change', syncCategoryToHidden);
                if (exp) exp.addEventListener('change', syncCategoryToHidden);

                // init
                toggle();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTxForm);
            } else {
                initTxForm();
            }
        })();
    </script>
@endpush
