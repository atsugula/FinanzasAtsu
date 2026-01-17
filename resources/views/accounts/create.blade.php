@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Create Account') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Accounts')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Create Account') }} </span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('accounts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        <form method="POST" action="{{ route('accounts.store') }}" role="form"
                            enctype="multipart/form-data">
                            @csrf

                            @include('accounts.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection
