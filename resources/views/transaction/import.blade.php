@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Import transaction') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('User')])

    <section class="content container-fluid">

        @includeif('partials.errors')

        <div class="card card-default">
            <div class="card-header">
                <div class="float-left">
                    <span class="card-title">{{ __('Import transaction') }} </span>
                </div>
                <div class="float-right">
                    <a class="btn btn-primary" href="{{ route('transactions.index') }}"> {{ __('Back') }}</a>
                </div>
            </div>

            {{-- Separar card --}}
            <span class="card-separator"></span>

            <div class="card-body">
                <div class="box box-info padding-1">
                    <div class="box-body">
                        <form action="{{ route('transactions.importform') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 justify-content-center">
                                <div class="form-group col-md-6">
                                    {{ Form::label('file', 'Seleccionar Archivo', ['class' => 'form-label']) }}
                                    <div class="custom-file">
                                        {{ Form::file('file', [
                                            'class' => 'custom-file-input form-control',
                                            'id' => 'fileInput',
                                            'required',
                                        ]) }}
                                        <label class="custom-file-label" for="fileInput">Elige un
                                            archivo...</label>
                                    </div>
                                    {!! $errors->first('file', '<div class="invalid-feedback">:message</div>') !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <button type="submit" class="btn btn-warning btn-block">
                                        <i class="fas fa-upload"></i> {{ __('Import transaction') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection
