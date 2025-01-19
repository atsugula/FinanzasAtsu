<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('category',__('Category')) }}
                    {{ Form::text('category', $expense->expensesCategory?->name, ['class' => 'form-control' . ($errors->has('category') ? ' is-invalid' : ''), 'disabled' => 'disabled', 'placeholder' => {{ __('Category') }}]) }}
                    {!! $errors->first('category', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('status_expense',__('Status')) }}
                    {{ Form::text('status_expense', $expense->statuse?->name, ['class' => 'form-control' . ($errors->has('status_expense') ? ' is-invalid' : ''), 'disabled' => 'disabled', 'placeholder' => {{ __('Status') }}]) }}
                    {!! $errors->first('status_expense', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount',__('Amount')) }}
                    {{ Form::text('amount', $expense->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'id' => 'amount', 'disabled' => 'disabled', 'placeholder' => {{ __('Amount') }}]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date_expense',__('Date')) }}
                    {{ Form::date('date_expense', $expense->date, ['class' => 'form-control' . ($errors->has('date_expense') ? ' is-invalid' : ''), 'disabled' => 'disabled', 'placeholder' => {{ __('Date') }}]) }}
                    {!! $errors->first('date_expense', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description',__('Description')) }}
                    {{ Form::textArea('description', $expense->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'disabled' => 'disabled', 'placeholder' => {{ __('Description') }}]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                {{-- Separar card --}}
                <span class="card-separator-form"></span>
            </div>
            <div class="col-12 col-md-12">
                <div class="float-left">
                    <span class="card-title">{{ __('Create Payment') }} </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('partner_id',__('Partner')) }}
                    {{ Form::select('partner_id', $partners, $paymentsHistory->partner_id, ['class' => 'form-control select2' . ($errors->has('partner_id') ? ' is-invalid' : ''), 'placeholder' => _{{ __('Select the partner') }}]) }}
                    {!! $errors->first('partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('status',__('Status')) }}
                    {{ Form::select('status', $statuses, $paymentsHistory->status, ['class' => 'form-control select2' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => {{ __('Select the statu') }}]) }}
                    {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 

            <div class="col-12 col-md-4">
                <div class="form-group">
                    {{ Form::label('paid', __('Paid')) }}
                    {{ Form::number('paid', $paymentsHistory->paid, ['class' => 'form-control' . ($errors->has('paid') ? ' is-invalid' : ''), 'id' => 'paid', 'placeholder' => {{ __('Paid') }}]) }}
                    {!! $errors->first('paid', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 
            
            <div class="col-12 col-md-4">
                <div class="form-group">
                    {{ Form::label('payable', __('Payable')) }}
                    {{ Form::text('payable', $balance['balance_due'], ['class' => 'form-control' . ($errors->has('payable') ? ' is-invalid' : ''), 'id' => 'payable', 'disabled' => 'disabled', 'placeholder' => {{ __('Payable') }}]) }}
                    {!! $errors->first('payable', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 
            
            <div class="col-12 col-md-4">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', $paymentsHistory->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => {{ __('Date') }}]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description')) }}
                    {{ Form::textArea('description', $paymentsHistory->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => {{ __('Description') }}]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
    </div>

    {{ Form::text('expense_id', $expense->id, ['class' => 'form-control' . ($errors->has('payable') ? ' is-invalid' : ''), 'hidden' => 'hidden', 'placeholder' => {{ __('Payable') }}]) }}

    {{-- Boton para todo --}}
    @include('layouts.btn-submit')

</div>