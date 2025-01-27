@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $transaction->name ??  __('Show Transaction') }}
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
                            <span class="card-title">{{ __('Show Transaction') }}</span>
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
                            {{ $transaction->user?->firstname }} {{ $transaction->user?->lastname }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Amount') }}:</strong>
                            {{ $transaction->amount }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Date') }}:</strong>
                            {{ $transaction->date }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Description') }}:</strong>
                            {{ $transaction->description }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Source') }}:</strong>
                            {{ $transaction->source }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Category') }}:</strong>
                            {{ $transaction->expensesCategory?->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Goal Relation') }}:</strong>
                            {{ $transaction->goalRelation?->name }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Goal') }}:</strong>
                            {{ $transaction->goal }}
                        </div>
                        <div class="form-group">
                            <strong>{{ __('Status') }}:</strong>
                            {{ $transaction->status?->name }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
