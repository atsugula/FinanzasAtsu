@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $paymentsHistory->name ?? __('Show Payments History') }}
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
                            <span class="card-title">{{ __('Show Payments History') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('payments-histories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>{{ __('Show Paid') }}:</strong>
                            {{ $paymentsHistory->paid }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Payable') }}:</strong>
                            {{ $paymentsHistory->payable }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Date') }}:</strong>
                            {{ $paymentsHistory->date }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Description') }}:</strong>
                            {{ $paymentsHistory->description }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Status') }}:</strong>
                            {{ $paymentsHistory->status }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Partner Id') }}:</strong>
                            {{ $paymentsHistory->partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Show Created By') }}:</strong>
                            {{ $paymentsHistory->created_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
