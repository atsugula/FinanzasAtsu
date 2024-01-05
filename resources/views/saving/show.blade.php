@extends('layouts.app')

@section('template_title')
    {{ $saving->name ?? __('Show Saving') }}
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
                            <span class="card-title">{{ __('Show') }} Saving</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('savings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User:</strong>
                            {{ $saving->user }}
                        </div>
                        <div class="form-group">
                            <strong>Amount:</strong>
                            {{ $saving->amount }}
                        </div>
                        <div class="form-group">
                            <strong>Goal:</strong>
                            {{ $saving->goal }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $saving->date }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $saving->description }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
