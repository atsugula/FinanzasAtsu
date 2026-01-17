@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Settings') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Settings')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <div style="display:flex; justify-content: space-between; align-items:center;">
                            <div class="float-left">
                                <span class="card-title">{{ __('Preferences') }}</span>
                            </div>
                            <div class="float-right d-flex" style="gap:8px;">
                                <a class="btn btn-secondary btn-sm" href="{{ route('settings.export.csv') }}">
                                    <i class="fa fa-file-export"></i> {{ __('Export CSV') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    {{-- Plantilla mensajes --}}
                    @include('layouts.message')

                    <div class="card-body">
                        {{-- FORM AJUSTES --}}
                        <form method="POST" action="{{ route('settings.update') }}" role="form">
                            @csrf
                            @method('PUT')

                            <div class="box box-info padding-1">
                                <div class="box-body">
                                    <div class="row">

                                        {{-- Moneda --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('currency', __('Currency')) }}
                                                {{ Form::text('currency', old('currency', $settings->currency ?? 'COP'), [
                                                    'class' => 'form-control' . ($errors->has('currency') ? ' is-invalid' : ''),
                                                    'placeholder' => __('E.g. COP, USD'),
                                                    'autocomplete' => 'off',
                                                ]) }}
                                                <small class="form-text text-muted">
                                                    {{ __('Example: COP, USD. Keep it short.') }}
                                                </small>
                                                {!! $errors->first('currency', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        </div>

                                        {{-- Día de inicio del mes --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('month_start_day', __('Month start day')) }}
                                                {{ Form::number('month_start_day', old('month_start_day', $settings->month_start_day ?? 1), [
                                                    'class' => 'form-control' . ($errors->has('month_start_day') ? ' is-invalid' : ''),
                                                    'min' => 1,
                                                    'max' => 28,
                                                    'step' => 1,
                                                ]) }}
                                                <small class="form-text text-muted">
                                                    {{ __('From 1 to 28. Example: 1 means the month starts on day 1.') }}
                                                </small>
                                                {!! $errors->first('month_start_day', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Botón para enviar --}}
                                @include('layouts.btn-submit')
                            </div>
                        </form>

                        {{-- Separación --}}
                        <hr>

                        {{-- IMPORT CSV --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex" style="justify-content: space-between; align-items:center;">
                                    <h6 class="mb-0">{{ __('Import CSV') }}</h6>
                                </div>
                                <small class="text-muted d-block mb-3">
                                    {{ __('CSV columns must be exactly: date,type,amount,account,category,note') }}
                                </small>

                                <form method="POST" action="{{ route('settings.import.csv') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                {{ Form::label('file', __('CSV file')) }}
                                                <input type="file" name="file"
                                                    class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                                                    accept=".csv,.txt">
                                                {!! $errors->first('file', '<div class="invalid-feedback">:message</div>') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-4 d-flex" style="align-items:end;">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fa fa-file-import"></i> {{ __('Import') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>
@endsection
