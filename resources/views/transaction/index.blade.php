@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Transaction') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Transaction')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Transaction') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm float-right"
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

                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Source') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)

                                        @php
                                            $type = $transaction->type == 'I' ? __('Income') : ($transaction->type == 'E' ? __('Expense') : __('Saving'));
                                        @endphp

                                        <tr>
                                            <td>{{ $transaction->id }}</td>

                                            <td>{{ $transaction->user?->firstname }}</td>
                                            <td>{{ $type }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->date }}</td>
                                            <td>{{ $transaction->source ?? 'N/A' }}</td>
                                            <td>{{ $transaction->status?->name }}</td>

                                            <td>
                                                <form action="{{ route('transactions.destroy', $transaction->id) }}"
                                                    method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('transactions.show', $transaction->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('transactions.edit', $transaction->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $transactions->appends(request()->except('page'))->links('vendor.pagination.custom') }}
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
