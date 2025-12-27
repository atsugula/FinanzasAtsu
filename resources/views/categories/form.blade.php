<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

            {{-- Tipo (Ingreso/Gasto) --}}
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}

                    {{-- Toggle UX: Income / Expense --}}
                    @php($currentType = old('type', $category->type))
                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" role="group" aria-label="type">
                        <label class="btn btn-outline-success {{ $currentType === 'income' ? 'active' : '' }} w-50">
                            <input type="radio" name="type" value="income" autocomplete="off"
                                {{ $currentType === 'income' ? 'checked' : '' }}>
                            {{ __('Income') }}
                        </label>
                        <label class="btn btn-outline-danger {{ $currentType === 'expense' ? 'active' : '' }} w-50">
                            <input type="radio" name="type" value="expense" autocomplete="off"
                                {{ $currentType === 'expense' ? 'checked' : '' }}>
                            {{ __('Expense') }}
                        </label>
                    </div>

                    {{-- Fallback select (por si tu layout no usa bootstrap toggle) --}}
                    {{-- <div class="mt-2">
                        {{ Form::select('type_select_fallback', $types, $currentType, [
                            'class' => 'form-control select2 d-none', // pon d-none si NO lo quieres ver nunca
                            'id' => 'type_fallback',
                            'placeholder' => __('Select the type'),
                            'disabled' => true,
                        ]) }}
                    </div> --}}

                    {!! $errors->first('type', '<div class="invalid-feedback d-block">:message</div>') !!}
                </div>
            </div>

            {{-- Nombre --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name')) }}
                    {{ Form::text('name', old('name', $category->name), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => __('E.g. Mercado, Transporte, Salario'),
                        'autocomplete' => 'off',
                    ]) }}
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Icono --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('icon', __('Icon')) }}
                    {{ Form::text('icon', old('icon', $category->icon), [
                        'class' => 'form-control' . ($errors->has('icon') ? ' is-invalid' : ''),
                        'placeholder' => __('E.g. cart, bus, home, salary'),
                        'autocomplete' => 'off',
                        'id' => 'icon_input',
                    ]) }}
                    <small class="form-text text-muted">
                        {{ __('Use an internal icon key (same key used by the mobile app). Example: cart, bus, home, heart.') }}
                    </small>
                    {!! $errors->first('icon', '<div class="invalid-feedback">:message</div>') !!}

                    {{-- Preview (simple) --}}
                    {{-- <div class="mt-2">
                        <span class="badge badge-secondary">
                            {{ __('Preview:') }}
                            <i class="material-icons"
                                id="icon_preview_i">{{ old('icon', $category->icon) ?: 'help' }}</i>
                        </span>
                    </div> --}}
                </div>
            </div>

            {{-- Estado / Archivado (opcional) --}}
            @if (isset($category->is_archived))
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('is_archived', __('Status')) }}
                        <div class="custom-control custom-switch">
                            {{ Form::checkbox('is_archived', 1, (bool) old('is_archived', $category->is_archived), [
                                'class' => 'custom-control-input',
                                'id' => 'is_archived',
                            ]) }}
                            <label class="custom-control-label" for="is_archived">
                                {{ __('Archived') }}
                            </label>
                        </div>
                        <small class="form-text text-muted">
                            {{ __('Archived categories won’t appear in dropdowns, but keep historical movements intact.') }}
                        </small>
                        {!! $errors->first('is_archived', '<div class="invalid-feedback d-block">:message</div>') !!}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
</div>
