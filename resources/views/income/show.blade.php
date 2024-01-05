@extends('layouts.app')

@section('template_title')
    {{ $income->name ?? __('Show Income') }}
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
                            <span class="card-title">{{ __('Show Income') }} </span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('incomes.index') }}"> {{__('Back')}}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User:</strong>
                            {{ $income->user }}
                        </div>
                        <div class="form-group">
                            <strong>Amount:</strong>
                            {{ $income->amount }}
                        </div>
                        <div class="form-group">
                            <strong>Source:</strong>
                            {{ $income->source }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $income->date }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
