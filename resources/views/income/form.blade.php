<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', $income->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::number('amount', $income->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('source', __('Source')) }}
                    {{ Form::text('source', $income->source, ['class' => 'form-control' . ($errors->has('source') ? ' is-invalid' : ''), 'placeholder' => 'Source']) }}
                    {!! $errors->first('source', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

    </div>

    {{-- Boton para todo --}}
    @include('layouts.btn-submit')

</div>