<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            {{-- Tipo --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}
                    {{ Form::select(
                        'type',
                        [
                            'saving' => __('Ahorro'),
                            'debt_in' => __('Deuda (Préstamos realizados)'), // Ej: alguien me debe
                            'debt_on' => __('Deuda (Préstamos recibidos)'), // Ej: yo debo
                        ],
                        $goal->type,
                        ['class' => 'form-control select2' . ($errors->has('type') ? ' is-invalid' : '')],
                    ) }}
                    {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Nombre --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('name', __('Name')) }}
                    {{ Form::text('name', $goal->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => __('Name')]) }}
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Monto objetivo --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('target_amount', __('Target Amount')) }}
                    {{ Form::number('target_amount', $goal->target_amount, ['class' => 'form-control' . ($errors->has('target_amount') ? ' is-invalid' : ''), 'placeholder' => __('Target Amount'), 'step' => '0.01']) }}
                    {!! $errors->first('target_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Monto actual --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('current_amount', __('Current Amount')) }}
                    {{ Form::number('current_amount', $goal->current_amount, ['class' => 'form-control' . ($errors->has('current_amount') ? ' is-invalid' : ''), 'placeholder' => __('Current Amount'), 'step' => '0.01', 'disabled' => 'disabled']) }}
                    {!! $errors->first('current_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            {{-- Fecha objetivo --}}
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('target_date', __('Target Date')) }}
                    {{ Form::date('target_date', $goal->target_date, ['class' => 'form-control' . ($errors->has('target_date') ? ' is-invalid' : '')]) }}
                    {!! $errors->first('target_date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
</div>
