@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $user->name ?? __('Show User') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('User')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show User') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}">{{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>{{ __('Username') }}:</strong>
                            {{ $user->username }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Firstname') }}:</strong>
                            {{ $user->firstname }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Lastname') }}:</strong>
                            {{ $user->lastname }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Email') }}:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Address') }}:</strong>
                            {{ $user->address }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('City') }}:</strong>
                            {{ $user->city }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Country') }}:</strong>
                            {{ $user->country }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Postal Code') }}:</strong>
                            {{ $user->postal }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('About') }}:</strong>
                            {{ $user->about }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
