<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('user_id',__('User')) }}
                    {{ Form::select('user_id', $users, $saving->user_id, ['class' => 'form-control select2' . ($errors->has('user_id') ? ' is-invalid' : ''), 'placeholder' => __('Select the user')]) }}
                    {!! $errors->first('user_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date',__('Date')) }}
                    {{ Form::date('date', $saving->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => 'Date']) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount',__('Amount')) }}
                    {{ Form::number('amount', $saving->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => 'Amount']) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('goal',__('Goal')) }}
                    {{ Form::text('goal', $saving->goal, ['class' => 'form-control' . ($errors->has('goal') ? ' is-invalid' : ''), 'placeholder' => 'Goal']) }}
                    {!! $errors->first('goal', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description',__('Description')) }}
                    {{ Form::textArea('description', $saving->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'Description']) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

    </div>
    
    {{-- Boton para todo --}}
    @include('layouts.btn-submit')
    
</div>