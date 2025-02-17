@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Expenses Category') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Income')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Expenses Category') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('expenses-categories.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expensesCategories as $expensesCategory)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $expensesCategory->name }}</td>
                                            <td>
                                                <form action="{{ route('expenses-categories.destroy', $expensesCategory->id) }}" method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('expenses-categories.show', $expensesCategory->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('expenses-categories.edit', $expensesCategory->id) }}">
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
                            {{ $expensesCategories->appends(request()->except('page'))->links('vendor.pagination.custom') }}
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
