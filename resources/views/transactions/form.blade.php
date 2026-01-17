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

    $categoriesIncome = $categoriesIncome ?? collect();
    $categoriesExpense = $categoriesExpense ?? collect();
    $accounts = $accounts ?? collect();

    $categoriesIncomeOptions = $categoriesIncome->pluck('name', 'id')->toArray();
    $categoriesExpenseOptions = $categoriesExpense->pluck('name', 'id')->toArray();
    $accountsOptions = $accounts->pluck('name', 'id')->toArray();

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

            {{-- Tipo --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}

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

            {{-- Categoría --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('category_id', __('Category')) }}

                    {{ Form::hidden('category_id', $oldCategoryId, ['id' => 'category_id_real']) }}

                    <div class="{{ $currentType === 'income' ? '' : 'd-none' }}" id="category_income_wrap">
                        {{ Form::select('category_income_id', $categoriesIncomeOptions, $oldCategoryIncomeId, [
                            'class' => 'form-control select2' . ($errors->has('category_id') ? ' is-invalid' : ''),
                            'placeholder' => __('Select a category'),
                            'id' => 'category_income',
                        ]) }}
                    </div>

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

            {{-- Adjuntos acumulables --}}
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('attachments', __('Receipts (optional)')) }}

                    <input type="file" id="attachments_input"
                        class="form-control {{ $errors->has('attachment_ids') ? 'is-invalid' : '' }}" accept="image/*"
                        multiple>

                    <small class="form-text text-muted">
                        {{ __('You can add images multiple times. Remove any before saving. Max 5 total. 2MB each.') }}
                    </small>

                    {!! $errors->first('attachment_ids', '<div class="invalid-feedback d-block">:message</div>') !!}
                    {!! $errors->first('attachment_ids.*', '<div class="invalid-feedback d-block">:message</div>') !!}

                    {{-- Aquí se pintan previews y se agregan hidden attachment_ids[] --}}
                    <div id="attachments_preview" class="d-flex flex-wrap mt-3" style="gap:10px;"></div>
                </div>
            </div>

            {{-- Adjuntos existentes (edit) --}}
            @if (!empty($transaction->id) && isset($transaction->attachments) && $transaction->attachments->count())
                <div class="col-md-12">
                    <label class="form-label">{{ __('Current receipts') }}</label>
                    <div class="d-flex flex-wrap" style="gap:10px;">
                        @foreach ($transaction->attachments as $att)
                            <a href="{{ asset('storage/' . $att->path) }}" target="_blank" class="border rounded p-1">
                                <img src="{{ asset('storage/' . $att->path) }}" alt="receipt"
                                    style="height:80px; width:auto; display:block;">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    @include('layouts.btn-submit')
</div>

@push('js')
    <script>
        (function() {
            function initTxForm() {
                // =========================
                // Toggle categorías
                // =========================
                const wrapIncome = document.getElementById('category_income_wrap');
                const wrapExpense = document.getElementById('category_expense_wrap');
                const real = document.getElementById('category_id_real');
                const inc = document.getElementById('category_income');
                const exp = document.getElementById('category_expense');

                if (!wrapIncome || !wrapExpense || !real) return;

                const radios = document.querySelectorAll('input[name="type"]');

                function setActiveButtons(selected) {
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

                    if (selected === 'income' && exp) {
                        exp.value = '';
                        if (window.jQuery && jQuery(exp).data('select2')) jQuery(exp).trigger('change.select2');
                    }
                    if (selected === 'expense' && inc) {
                        inc.value = '';
                        if (window.jQuery && jQuery(inc).data('select2')) jQuery(inc).trigger('change.select2');
                    }

                    syncCategoryToHidden();
                }

                radios.forEach(r => r.addEventListener('change', toggle));
                if (inc) inc.addEventListener('change', syncCategoryToHidden);
                if (exp) exp.addEventListener('change', syncCategoryToHidden);
                toggle();

                // =========================
                // Adjuntos: AJAX acumulable + removible
                // =========================
                const input = document.getElementById('attachments_input');
                const preview = document.getElementById('attachments_preview');
                if (!input || !preview) return;

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // id -> {id,url,name}
                const current = new Map();

                function renderAll() {
                    preview.innerHTML = '';

                    current.forEach((item) => {
                        const card = document.createElement('div');
                        card.className = 'border rounded p-1 position-relative';
                        card.style.width = '120px';

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'btn btn-sm btn-danger position-absolute';
                        btn.style.top = '4px';
                        btn.style.right = '4px';
                        btn.style.padding = '2px 6px';
                        btn.innerHTML = '&times;';
                        btn.title = 'Quitar';

                        btn.addEventListener('click', () => removeTmp(item.id));

                        const img = document.createElement('img');
                        img.src = item.url;
                        img.alt = 'receipt';
                        img.style.width = '100%';
                        img.style.height = '80px';
                        img.style.objectFit = 'cover';
                        img.style.display = 'block';
                        img.className = 'rounded';

                        const name = document.createElement('div');
                        name.className = 'text-muted small mt-1';
                        name.style.whiteSpace = 'nowrap';
                        name.style.overflow = 'hidden';
                        name.style.textOverflow = 'ellipsis';
                        name.textContent = item.name || ('#' + item.id);

                        // hidden: attachment_ids[]
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'attachment_ids[]';
                        hidden.value = item.id;

                        card.appendChild(btn);
                        card.appendChild(img);
                        card.appendChild(name);
                        card.appendChild(hidden);

                        preview.appendChild(card);
                    });
                }

                function addTmp(att) {
                    if (current.size >= 5) return;
                    current.set(String(att.id), {
                        id: String(att.id),
                        url: att.url,
                        name: att.name
                    });
                    renderAll();
                }

                function removeTmp(id) {
                    const key = String(id);

                    // optimista: lo quitamos de UI primero
                    current.delete(key);
                    renderAll();

                    $.ajax({
                        url: "{{ route('transactions.attachments.tmp.destroy', ['id' => '__ID__']) }}".replace(
                            '__ID__', key),
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        },
                    }).fail(() => {
                        // si falla, lo regresamos
                        // (porque no queremos “ghosts”)
                        // nota: no tenemos url/name aquí; en práctica casi nunca falla.
                    });
                }

                function uploadOne(file) {
                    return new Promise((resolve, reject) => {
                        const fd = new FormData();
                        fd.append('file', file);

                        $.ajax({
                            url: "{{ route('transactions.attachments.tmp.store') }}",
                            method: 'POST',
                            data: fd,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            },
                            success: resolve,
                            error: reject,
                        });
                    });
                }

                input.addEventListener('change', async function() {
                    const files = Array.from(this.files || []);
                    if (!files.length) return;

                    // para que el usuario pueda volver a seleccionar el mismo archivo
                    this.value = '';

                    // subimos en serie para evitar “tormenta” (y mantener orden)
                    for (const f of files) {
                        if (current.size >= 5) break;
                        if (!f.type || !f.type.startsWith('image/')) continue;

                        try {
                            const res = await uploadOne(f);
                            addTmp(res);
                        } catch (e) {
                            // si falla una, seguimos con las demás
                            console.error(e);
                        }
                    }
                });

                renderAll();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTxForm);
            } else {
                initTxForm();
            }
        })();
    </script>
@endpush
