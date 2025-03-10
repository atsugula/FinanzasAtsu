<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('category', __('Category')) }}
                    {{ Form::select('category', $categories, $expense->category, ['class' => 'form-control select2' . ($errors->has('category') ? ' is-invalid' : ''), 'placeholder' => __('Select the category')]) }}
                    {!! $errors->first('category', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('status', __('Status')) }}
                    {{ Form::select('status', $statuses, $expense->status, ['class' => 'form-control select2' . ($errors->has('status') ? ' is-invalid' : ''), 'placeholder' => __('Select the status')]) }}
                    {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> 
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount', __('Amount')) }}
                    {{ Form::text('amount', $expense->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => __('Amount')]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date', __('Date')) }}
                    {{ Form::date('date', $expense->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => __('Date')]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description')) }}
                    {{ Form::textArea('description', $expense->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => __('Description')]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')

</div>
