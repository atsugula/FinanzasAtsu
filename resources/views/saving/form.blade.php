<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('date',__('Date')) }}
                    {{ Form::date('date', $saving->date, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'placeholder' => {{ __('Date') }}]) }}
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('amount',__('Amount')) }}
                    {{ Form::number('amount', $saving->amount, ['class' => 'form-control' . ($errors->has('amount') ? ' is-invalid' : ''), 'placeholder' => {{ __('Amount') }}]) }}
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('goal',__('Goal')) }}
                    {{ Form::text('goal', $saving->goal, ['class' => 'form-control' . ($errors->has('goal') ? ' is-invalid' : ''), 'placeholder' => {{ __('Goal') }}]) }}
                    {!! $errors->first('goal', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('description',__('Description')) }}
                    {{ Form::textArea('description', $saving->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => {{ __('Description') }}]) }}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

    </div>
    
    {{-- Boton para todo --}}
    @include('layouts.btn-submit')
    
</div>