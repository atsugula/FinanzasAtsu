<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('username', __('Username')) }}
                    {{ Form::text('username', $user->username, ['class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'placeholder' => __('Username')]) }}
                    {!! $errors->first('username', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('firstname', __('Firstname')) }}
                    {{ Form::text('firstname', $user->firstname, ['class' => 'form-control' . ($errors->has('firstname') ? ' is-invalid' : ''), 'placeholder' => __('Firstname')]) }}
                    {!! $errors->first('firstname', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('lastname', __('Lastname')) }}
                    {{ Form::text('lastname', $user->lastname, ['class' => 'form-control' . ($errors->has('lastname') ? ' is-invalid' : ''), 'placeholder' => __('Lastname')]) }}
                    {!! $errors->first('lastname', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('email', __('Email')) }}
                    {{ Form::text('email', $user->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => __('Email')]) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('address', __('Address')) }}
                    {{ Form::text('address', $user->address, ['class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => __('Address')]) }}
                    {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('city', __('City')) }}
                    {{ Form::text('city', $user->city, ['class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''), 'placeholder' => __('City')]) }}
                    {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('country', __('Country')) }}
                    {{ Form::text('country', $user->country, ['class' => 'form-control' . ($errors->has('country') ? ' is-invalid' : ''), 'placeholder' => __('Country')]) }}
                    {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('postal', __('Postal')) }}
                    {{ Form::text('postal', $user->postal, ['class' => 'form-control' . ($errors->has('postal') ? ' is-invalid' : ''), 'placeholder' => __('Postal Code')]) }}
                    {!! $errors->first('postal', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('about', __('About')) }}
                    {{ Form::text('about', $user->about, ['class' => 'form-control' . ($errors->has('about') ? ' is-invalid' : ''), 'placeholder' => __('About User')]) }}
                    {!! $errors->first('about', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

    </div>
    
    {{-- Botón para enviar --}}
    @include('layouts.btn-submit')
    
</div>
