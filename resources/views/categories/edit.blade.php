@extends('layouts.app')

@section('template_title')
    {{ __('Update Category') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Category')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Update Category') }} </span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('categories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.update', $category->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('categories.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection

{{-- @section('js')
    <script>
        (function() {
            const input = document.getElementById('icon_input');
            const iconPreview = document.getElementById('icon_preview_i');

            if (input && iconPreview) {
                const sync = () => iconPreview.textContent = (input.value || 'help').trim() || 'help';
                input.addEventListener('input', sync);
                sync();
            }
        })();
    </script>
@endsection --}}
