@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $goal->name ?? __('Show Goal') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Goal')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show Goal') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('goals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        <div class="form-group">
                            <strong>{{ __('Name') }}:</strong>
                            {{ $goal->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Amount') }}:</strong>
                            {{ $goal->amount }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Description') }}:</strong>
                            {{ $goal->description }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Date') }}:</strong>
                            {{ $goal->date }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
