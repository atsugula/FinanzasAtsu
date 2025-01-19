@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Partner') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Partner')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Partner') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('partners.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    {{-- Plantilla mensajes --}}
                    @include('layouts.message')

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Company Name') }}</th>
                                        <th>{{ __('Type Document') }}</th>
                                        <th>{{ __('Document Number') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partners as $partner)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $partner->user?->firstname }}</td>
                                            <td>{{ $partner->company_name }}</td>
                                            <td>{{ $partner->typeDocument?->name }}</td>
                                            <td>{{ $partner->document_number }}</td>
                                            <td>{{ $partner->phone }}</td>
                                            <td>{{ $partner->email }}</td>

                                            <td>
                                                <form action="{{ route('partners.destroy', $partner->id) }}" method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('partners.show', $partner->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('partners.edit', $partner->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $partners->links() !!}
            </div>
        </div>
    </div>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection

@section('js')

    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>

@endsection
