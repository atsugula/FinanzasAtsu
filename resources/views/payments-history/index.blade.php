@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('template_title')
    {{ __('Payments History') }}
@endsection

@section('content')

    {{-- Navbar template --}}
    @include('layouts.navbars.auth.topnav', ['title' => __('Payments History')])

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Payments History') }}
                            </span>
                            
                        </div>
                    </div>

                    {{-- Separar card --}}
                    <span class="card-separator"></span>

                    {{-- Plantilla mensajes--}}
                    @include('layouts.message')

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Partner</th>
										<th>User</th>
										<th>Paid</th>
										<th>Payable</th>
										<th>Date</th>										
                                        <th>Status</th>

                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentsHistories as $paymentsHistory)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $paymentsHistory->partner?->company_name }}</td>
											<td>{{ $paymentsHistory->user?->firstname }}</td>
											<td>{{ $paymentsHistory->paid }}</td>
											<td>{{ $paymentsHistory->payable }}</td>
											<td>{{ $paymentsHistory->date }}</td>											
                                            <td>{{ $paymentsHistory->statuses?->name }}</td>

                                            <td>
                                                <form action="{{ route('payments-histories.destroy',$paymentsHistory->id) }}" method="POST" class="form-delete">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('payments-histories.show',$paymentsHistory->id) }}"><i class="fa fa-fw fa-eye"></i>{{--  {{ __('Show') }} --}}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('payments-histories.edit',$paymentsHistory->id) }}"><i class="fa fa-fw fa-edit"></i>{{--  {{ __('Edit') }} --}}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i>{{--  {{ __('Delete') }} --}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $paymentsHistories->links() !!}
            </div>
        </div>
    </div>
    
    {{-- Footer template --}}
    @include('layouts.footers.auth.footer')
    
@endsection

@section('js')

    <script src="{{ asset('assets/js/plugins/sweetalert.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/plugins/datatable.js') }}"></script> --}}

@endsection