@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $partner->name ?? __('Show Partner') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Income')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show Partner') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('partners.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>{{ __('Company Name') }}:</strong>
                            {{ $partner->company_name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Type Document') }}:</strong>
                            {{ $partner->typeDocument?->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Document Number') }}:</strong>
                            {{ $partner->document_number }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Phone') }}:</strong>
                            {{ $partner->phone }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Email') }}:</strong>
                            {{ $partner->email }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Created By') }}:</strong>
                            {{ $partner->user?->firstname }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
