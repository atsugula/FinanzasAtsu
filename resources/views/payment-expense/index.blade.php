@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Pay Expense') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Pay Expense')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Pay Expense') }}
                            </span>

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
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $expense->user?->firstname }}</td>
                                            <td>{{ $expense->expensesCategory?->name }}</td>
                                            <td>{{ $expense->amount }}</td>
                                            <td>{{ $expense->date }}</td>
                                            <td>{{ $expense->statuse?->name }}</td>

                                            <td>
                                                <form action="{{ route('payment-expenses.destroy', $expense->id) }}"
                                                    method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary"
                                                        href="{{ route('payment-expenses.show', $expense->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('payment-expenses.edit', $expense->id) }}">
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
                            <br>
                            {{ $expenses->appends(request()->except('page'))->links('vendor.pagination.custom') }}
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
