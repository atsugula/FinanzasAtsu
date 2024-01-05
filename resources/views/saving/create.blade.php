@extends('layouts.app')

@section('template_title')
    {{ __('Create Saving') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('User')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Create Saving') }} </span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('savings.index') }}"> {{__('Back')}}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>
                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('savings.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('saving.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection