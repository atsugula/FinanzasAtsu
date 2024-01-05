<div class="box box-info padding-1">
    <div class="box-body">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('username') }}
                    {{ Form::text('username', $user->username, ['class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'placeholder' => 'Username']) }}
                    {!! $errors->first('username', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('firstname') }}
                    {{ Form::text('firstname', $user->firstname, ['class' => 'form-control' . ($errors->has('firstname') ? ' is-invalid' : ''), 'placeholder' => 'Firstname']) }}
                    {!! $errors->first('firstname', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('lastname') }}
                    {{ Form::text('lastname', $user->lastname, ['class' => 'form-control' . ($errors->has('lastname') ? ' is-invalid' : ''), 'placeholder' => 'Lastname']) }}
                    {!! $errors->first('lastname', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('email') }}
                    {{ Form::text('email', $user->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'placeholder' => 'Email']) }}
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('address') }}
                    {{ Form::text('address', $user->address, ['class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''), 'placeholder' => 'Address']) }}
                    {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('city') }}
                    {{ Form::text('city', $user->city, ['class' => 'form-control' . ($errors->has('city') ? ' is-invalid' : ''), 'placeholder' => 'City']) }}
                    {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('country') }}
                    {{ Form::text('country', $user->country, ['class' => 'form-control' . ($errors->has('country') ? ' is-invalid' : ''), 'placeholder' => 'Country']) }}
                    {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-group">
                    {{ Form::label('postal') }}
                    {{ Form::text('postal', $user->postal, ['class' => 'form-control' . ($errors->has('postal') ? ' is-invalid' : ''), 'placeholder' => 'Postal']) }}
                    {!! $errors->first('postal', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="form-group">
                    {{ Form::label('about') }}
                    {{ Form::text('about', $user->about, ['class' => 'form-control' . ($errors->has('about') ? ' is-invalid' : ''), 'placeholder' => 'About']) }}
                    {!! $errors->first('about', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>

    </div>
    
    {{-- Boton para todo --}}
    @include('layouts.btn-submit')
</div>