<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('user_id',__('User')) }}
                    {{ Form::select('user_id', $users, $expense->user_id, ['class' => 'form-control select2' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => __('Select the user')]) }}
                    {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('category',__('Category')) }}
                    {{ Form::select('category', $categories, $expense->category, ['class' => 'form-control select2' . ($errors->has('category') ? ' is-invalid' : ''), 'placeholder' => __('Select the category')]) }}
                    {!! $errors->first('category', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount') }}
                    {{ Form::text('amount', $expense->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date') }}
                    {{ Form::date('date', $expense->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description') }}
                    {{ Form::textArea('description', $expense->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        
    </div>

    {{-- Boton para todo --}}
    @include('layouts.btn-submit')

</div>