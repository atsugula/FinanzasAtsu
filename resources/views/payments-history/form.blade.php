<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('partner_id', __('Partner')) }}
                    {{ Form::select('partner_id', $partners, $paymentsHistory->partner_id, ['class' => 'form-control select2' . ($errors->has('partner_id') ? ' is-invalid' : ''), 'placeholder' => __('Select the partner')]) }}
                    {!! $errors->first('partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('status', __('Status')) }}
                    {{ Form::select('status', $statuses, $paymentsHistory->status, ['class' => 'form-control select2' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => __('Select the status')]) }}
                    {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 

            <div class="col-12 col-md-4">
                <div class="form-group">
                    {{ Form::label('paid', __('Paid')) }}
                    {{ Form::number('paid', $paymentsHistory->paid, ['class' => 'form-control' . ($errors->has('paid') ? ' is-invalid' : ''), 'id' => 'paid', 'placeholder' => __('Paid')]) }}
                    {!! $errors->first('paid', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            
            <div class="col-12 col-md-2">
                <div class="form-group">
                    {{ Form::label('payable', __('Payable')) }}
                    {{ Form::number('payable', $paymentsHistory->payable, ['class' => 'form-control' . ($errors->has('payable') ? ' is-invalid' : ''), 'id' => 'payable', 'disabled' => 'disabled', 'placeholder' => __('Payable')]) }}
                    {!! $errors->first('payable', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 

            <div class="col-12 col-md-2">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::text('amount', $paymentsHistory->expense?->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'id' => 'amount', 'disabled' => 'disabled', 'placeholder' => __('Amount')]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', $paymentsHistory->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => __('Date')]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description')) }}
                    {{ Form::textArea('description', $paymentsHistory->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => __('Description')]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
    
</div>
