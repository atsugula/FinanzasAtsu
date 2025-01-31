@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $expense->name ?? __('Show Expense') }}
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
                            <span class="card-title">{{ __('Show') }} {{ __('Expense') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('transactions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>{{ __('User') }}:</strong>
                            {{ $expense->user }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Category') }}:</strong>
                            {{ $expense->expensesCategory }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Amount') }}:</strong>
                            {{ $expense->amount }}
                        </div>                        
                        <div class="form-group">
                            <strong>{{ __('Date') }}:</strong>
                            {{ $expense->date }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Status') }}:</strong>
                            {{ $expense->statuse?->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Description') }}:</strong>
                            {{ $expense->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
