<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('partner_id', __('Partner')) }}
                    {{ Form::select('partner_id', $partners, $income->partner_id, ['class' => 'form-control select2' . ($errors->has('partner_id') ? ' is-invalid' : ''), 'placeholder' => __('Select the partner')]) }}
                    {!! $errors->first('partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', $income->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => __('Date')]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('status', __('Status')) }}
                    {{ Form::select('status', $statuses, $income->status, ['class' => 'form-control select2' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => __('Select the status')]) }}
                    {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>   
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::number('amount', $income->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => __('Amount')]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('source', __('Source')) }}
                    {{ Form::text('source', $income->source, ['class' => 'form-control' . ($errors->has('source') ? ' is-invalid' : ''), 'placeholder' => __('Source')]) }}
                    {!! $errors->first('source', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>            
        </div>

    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')

</div>
