@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Accounts') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Accounts')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Accounts') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('accounts.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
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
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Initial Balance') }}</th>
                                        <th>{{ __('Current Balance') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td>{{ $account->id }}</td>

                                            <td>{{ $account->name }}</td>
                                            <td>{{ $account->initial_balance }}</td>
                                            <td>{{ $account->current_balance_formatted }}</td>
                                            <td>
                                                {{ $account->is_archived ? __('Archived') : __('Active') }}
                                            </td>

                                            <td>
                                                <form action="{{ route('accounts.archive', $account->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('accounts.edit', $account->id) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}
                                                    </a>

                                                    @csrf
                                                    @method('PUT')
                                                    @if (!$account->is_archived)
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-fw fa-archive"></i> {{ __('Archive') }}
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $accounts->appends(request()->except('page'))->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>
@endsection
