@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => __('Cuentas')])

    <div class="container-fluid py-4">

        @if (session('success'))
            <div class="alert alert-success text-white">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger text-white">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">{{ __('Mis cuentas') }}</h6>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle me-1"></i> {{ __('Nueva cuenta') }}
            </a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                {{ __('Nombre') }}</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                {{ __('Saldo inicial') }}</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                {{ __('Estado') }}</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                {{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $acc)
                            <tr>
                                <td class="text-sm">{{ $acc->name }}</td>
                                <td class="text-sm text-end">${{ number_format($acc->initial_balance, 2) }}</td>
                                <td class="text-center">
                                    @if ($acc->is_archived)
                                        <span class="badge bg-secondary">{{ __('Archivada') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('Activa') }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('accounts.edit', $acc->id) }}"
                                        class="btn btn-link text-dark btn-sm mb-0">
                                        <i class="fa fa-edit me-1"></i>{{ __('Editar') }}
                                    </a>

                                    @if (!$acc->is_archived)
                                        <form action="{{ route('accounts.destroy', $acc->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('{{ __('¿Archivar esta cuenta?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link text-danger btn-sm mb-0">
                                                <i class="fa fa-archive me-1"></i>{{ __('Archivar') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-sm py-4">{{ __('No tienes cuentas aún.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $accounts->links() }}
        </div>
    </div>

    @include('layouts.footers.auth.footer')
@endsection
