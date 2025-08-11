@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Goals') }}
@endsection

@section('content')
    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Goals')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span id="card_title">
                            {{ __('Goals') }}
                        </span>

                        <a href="{{ route('goals.create') }}" class="btn btn-primary btn-sm">
                            {{ __('Create New') }}
                        </a>
                    </div>

                    {{-- Separador --}}
                    <span class="card-separator"></span>

                    {{-- Mensajes de sesión --}}
                    @include('layouts.message')

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>{{ __('No') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Target Amount') }}</th>
                                        <th>{{ __('Current Amount') }}</th>
                                        <th>{{ __('Target Date') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($goals as $goal)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $goal->name }}</td>
                                            <td>{{ number_format($goal->target_amount, 2) }}</td>
                                            <td>{{ number_format($goal->current_amount, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($goal->target_date)->format('Y-m-d') }}</td>
                                            <td>
                                                <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" class="form-delete d-inline">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('goals.show', $goal->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('goals.edit', $goal->id) }}">
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
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('No records found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <br>
                            {{ $goals->appends(request()->except('page'))->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.footers.auth.footer')
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>
@endsection
