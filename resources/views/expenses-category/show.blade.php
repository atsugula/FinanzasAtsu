@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ $expensesCategory->name ?? __('Show Expenses Category') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Income')])

    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show Expenses Category') }}</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('expenses-categories.index') }}">{{ __('Back') }}</a>
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>{{ __('Name') }}:</strong>
                            {{ $expensesCategory->name }}
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expensesCategory->expenses as $key => $expense)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $expense->user?->firstname }}</td>
                                            <td>{{ $expense->amount }}</td>
                                            <td>{{ $expense->date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- {!! $expensesCategory->expenses->links() !!} --}}
            </div>
        </div>
    </section>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection
