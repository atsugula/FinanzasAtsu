@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('User') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('User')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('User') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>{{ __('Username') }}</th>
                                        <th>{{ __('Firstname') }}</th>
                                        <th>{{ __('Lastname') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Address') }}</th>
                                        <th>{{ __('City') }}</th>
                                        <th>{{ __('Country') }}</th>
                                        <th>{{ __('Postal Code') }}</th>
                                        <th>{{ __('About') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->firstname }}</td>
                                            <td>{{ $user->lastname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->address }}</td>
                                            <td>{{ $user->city }}</td>
                                            <td>{{ $user->country }}</td>
                                            <td>{{ $user->postal }}</td>
                                            <td>{{ $user->about }}</td>

                                            <td>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('users.show', $user->id) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Show') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('users.edit', $user->id) }}">
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
                {!! $users->links() !!}
            </div>
        </div>
    </div>

    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')

@endsection

@section('js')

    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>

@endsection
