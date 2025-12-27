@extends('layouts.app')

@section('template_title')
    {{ $category->name ?? __('Show Category') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Category')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show Category') }} </span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('categories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>{{ __('Name') }}:</strong>
                            {{ $category->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Icon') }}:</strong>
                            {{ $category->icon }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Type') }}:</strong>
                            {{ $category->type }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Created') }}:</strong>
                            {{ $category->creator->firstname ?? 'N/A' }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection
