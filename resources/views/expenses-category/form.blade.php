<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group">
            {{ Form::label('name', __('Name')) }}
            {{ Form::text('name', $expensesCategory->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => __('Name')]) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
    
</div>
