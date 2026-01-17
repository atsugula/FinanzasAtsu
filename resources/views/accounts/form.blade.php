@php
    $account =
        $account ??
        (object) [
            'name' => null,
            'initial_balance' => 0,
            'is_archived' => false,
        ];
@endphp

<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

            {{-- Nombre --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name')) }}
                    {{ Form::text('name', old('name', $account->name), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => __('E.g. Banco, Efectivo'),
                        'autocomplete' => 'off',
                    ]) }}
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Saldo inicial --}}
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('initial_balance', __('Initial Balance')) }}
                    {{ Form::number('initial_balance', old('initial_balance', $account->initial_balance), [
                        'class' => 'form-control' . ($errors->has('initial_balance') ? ' is-invalid' : ''),
                        'placeholder' => __('E.g. 0'),
                        'step' => '0.01',
                    ]) }}
                    {!! $errors->first('initial_balance', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Estado / Archivado (solo en edit) --}}
            @if (isset($account->is_archived))
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('is_archived', __('Status')) }}
                        <div class="custom-control custom-switch">
                            {{ Form::checkbox('is_archived', 1, (bool) old('is_archived', $account->is_archived), [
                                'class' => 'custom-control-input',
                                'id' => 'is_archived',
                            ]) }}
                            <label class="custom-control-label" for="is_archived">
                                {{ __('Archived') }}
                            </label>
                        </div>
                        {!! $errors->first('is_archived', '<div class="invalid-feedback d-block">:message</div>') !!}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
</div>
