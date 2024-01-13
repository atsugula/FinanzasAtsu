@extends('layouts.app')

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
                            <span class="card-title">{{ __('Show') }} Expense</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('expenses.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User:</strong>
                            {{ $expense->user }}
                        </div>
                        <div class="form-group">
                            <strong>Category:</strong>
                            {{ $expense->expensesCategory }}
                        </div>
                        <div class="form-group">
                            <strong>Amount:</strong>
                            {{ $expense->amount }}
                        </div>                        
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $expense->date }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $expense->statuse?->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
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
