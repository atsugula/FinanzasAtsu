@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Update Transaction') }}
@endsection

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => __('Transactions')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Update Transaction') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('transactions.index') }}">{{ __('Back') }}</a>
                        </div>
                    </div>

                    <span class="card-separator"></span>

                    <div class="card-body">
                        <form method="POST" action="{{ route('transactions.update', $transaction->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('transactions.form')
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('layouts.footers.auth.footer')
@endsection
