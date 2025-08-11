<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name') }}
                    {{ Form::text('name', $category->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('icon') }}
                    {{ Form::text('icon', $category->icon, ['class' => 'form-control' . ($errors->has('icon') ? ' is-invalid' : ''), 'placeholder' => 'Icon']) }}
                    {!! $errors->first('icon', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('type', __('Type')) }}
                    {{ Form::select('type', $types, old('type', $category->type), [
                        'class' => 'form-control select2' . ($errors->has('type') ? ' is-invalid' : ''),
                        'placeholder' => __('Select the type'),
                    ]) }}
                    {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
    </div>

    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')

</div>
