@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => __('Dashboard')])

    <div class="container-fluid py-4">

        {{-- Header acciones --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">{{ __('Resumen') }}</h6>
                    <p class="text-sm text-muted mb-0">
                        {{ __('Mes') }}: <strong>{{ $month }}</strong>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus-circle me-1"></i> {{ __('Agregar movimiento') }}
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-list me-1"></i> {{ __('Ver movimientos') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Cards resumen --}}
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Saldo total') }}</p>
                                <h5 class="font-weight-bolder">
                                    ${{ number_format($totalBalance, 2) }}
                                </h5>
                                <p class="mb-0 text-sm text-muted">
                                    {{ __('inicial + ingresos - gastos') }}
                                </p>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-dark shadow text-center rounded-circle">
                                    <i class="ni ni-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Ingresos mes') }}</p>
                                <h5 class="font-weight-bolder">
                                    ${{ number_format($incomeMonth, 2) }}
                                </h5>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-bold-up text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Gastos mes') }}</p>
                                <h5 class="font-weight-bolder">
                                    ${{ number_format($expenseMonth, 2) }}
                                </h5>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-bold-down text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __('Ahorro mes') }}</p>
                                <h5 class="font-weight-bolder">
                                    ${{ number_format($savingsMonth, 2) }}
                                </h5>
                                <p class="mb-0 text-sm text-muted">
                                    {{ __('Categoría') }}: <strong>{{ __('Ahorro') }}</strong>
                                </p>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="ni ni-piggy-bank text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top categorías + Recientes --}}
        <div class="row mt-4">

            <div class="col-lg-5 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('Top categorías del mes') }}</h6>
                        <p class="text-sm text-muted mb-0">{{ __('Solo gastos') }}</p>
                    </div>
                    <div class="card-body p-3">
                        @if ($topExpenseCategories->isEmpty())
                            <p class="text-sm mb-0">{{ __('No hay datos para este mes.') }}</p>
                        @else
                            <ul class="list-group">
                                @foreach ($topExpenseCategories as $row)
                                    <li
                                        class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-tag text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">{{ $row->name }}</h6>
                                                <span class="text-xs text-muted">{{ __('Total') }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="text-sm font-weight-bolder">${{ number_format($row->total, 2) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('categories.index', ['type' => 'expense']) }}"
                                class="btn btn-outline-secondary btn-sm">
                                {{ __('Ver categorías') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ __('Movimientos recientes') }}</h6>
                            <p class="text-sm text-muted mb-0">{{ __('Últimos 10') }}</p>
                        </div>
                        <a href="{{ route('transactions.index', ['month' => $month]) }}"
                            class="btn btn-outline-secondary btn-sm">
                            {{ __('Ver todo') }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('Fecha') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('Categoría') }}</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{ __('Cuenta') }}</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                        {{ __('Monto') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $tx)
                                    <tr>
                                        <td class="text-sm">{{ optional($tx->date)->format('Y-m-d') }}</td>
                                        <td class="text-sm">
                                            {{ $tx->category?->name ?? '-' }}
                                            <div class="text-xs text-muted">
                                                {{ $tx->note ?? '' }}
                                            </div>
                                        </td>
                                        <td class="text-sm">{{ $tx->account?->name ?? '-' }}</td>
                                        <td class="text-sm text-end font-weight-bolder">
                                            @if ($tx->type === 'income')
                                                <span class="text-success">+${{ number_format($tx->amount, 2) }}</span>
                                            @else
                                                <span class="text-danger">-${{ number_format($tx->amount, 2) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-sm text-center py-4">
                                            {{ __('No hay movimientos aún. Ve y mete aunque sea un tinto.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>

    @include('layouts.footers.auth.footer')
@endsection
