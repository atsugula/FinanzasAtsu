@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => __('Dashboard')])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __("Today's Expense") }}</p>
                                    <h5 class="font-weight-bolder">
                                        ${{ number_format($count_expense, 1) }}
                                    </h5>
                                    {{-- <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+55%</span>
                                        {{ __('since yesterday') }}
                                    </p> --}}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __("Today's Income") }}</p>
                                    <h5 class="font-weight-bolder">
                                        ${{ number_format($count_incomes, 1) }}
                                    </h5>
                                    {{-- <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder">+3%</span>
                                        {{ __('since last week') }}
                                    </p> --}}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">{{ __("Today's Saving") }}</p>
                                    <h5 class="font-weight-bolder">
                                        ${{ number_format($count_saving, 1) }}
                                    </h5>
                                    {{-- <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                        {{ __('since last quarter') }}
                                    </p> --}}
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card ">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">{{ __('Total balance') }}</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center ">
                            <tbody>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ asset('img/icons/flags/balance.png') }}" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ __('Reason') }}:</p>
                                                <h6 class="text-sm mb-0">{{ __('Accounting') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('Income') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_incomes, 1) }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('Expense') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_expense, 1) }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('Balance') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_incomes - $count_expense, 1) }}</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ asset('img/icons/flags/saving.png') }}" alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ __('Reason') }}:</p>
                                                <h6 class="text-sm mb-0">{{ __('Savings') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('Owe me') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_incomes_am_owed, 1) }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('I must') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_expense_must, 1) }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ __('Balance') }}:</p>
                                            <h6 class="text-sm mb-0">${{ number_format($count_incomes_am_owed - $count_expense_must, 1) }}</h6>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('Categories') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @forelse ($categories as $category)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                            <i class="ni ni-tag text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                            <span class="text-xs">
                                                <span class="font-weight-bold">
                                                    {{ count($category->expenses) }} {{ __('Expenses') }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <a class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto" href="{{ route('expenses-categories.show',$category->id) }}">
                                            <i class="ni ni-bold-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <p>{{ __('No data available.') }}</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('Goals') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            <p>{{ __('No data available.') }}</p>
                            {{-- @forelse ($categories as $category)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                            <i class="ni ni-tag text-white opacity-10"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $category->name }}</h6>
                                            <span class="text-xs">
                                                <span class="font-weight-bold">
                                                    {{ count($category->expenses) }} {{ __('Expenses') }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <a class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto" href="{{ route('expenses-categories.show',$category->id) }}">
                                            <i class="ni ni-bold-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <p>{{ __('No data available.') }}</p>
                            @endforelse --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row mt-4">
            <div class="col-lg-7 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">{{ __('Sales overview') }}</h6>
                        <p class="text-sm mb-0">
                            <i class="fa fa-arrow-up text-success"></i>
                            <span class="font-weight-bold">4% more</span> in 2021
                        </p>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                        <div class="carousel-inner border-radius-lg h-100">
                            <div class="carousel-item h-100 active" style="background-image: url('./img/carousel-1.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">{{ __('Get started with Argon') }}</h5>
                                    <p>{{ __("There’s nothing I really wanted to do in life that I wasn’t able to get good at.") }}</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/carousel-2.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">{{ __('Faster way to create web pages') }}</h5>
                                    <p>{{__('That’s my skill. I’m not really specifically talented at anything except for the
                                        ability to learn.')}}</p>
                                </div>
                            </div>
                            <div class="carousel-item h-100" style="background-image: url('./img/carousel-3.jpg');
            background-size: cover;">
                                <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                        <i class="ni ni-trophy text-dark opacity-10"></i>
                                    </div>
                                    <h5 class="text-white mb-1">{{ __('Share with us your design tips!') }}</h5>
                                    <p>{{ __('Don’t be afraid to be wrong because you can’t learn anything from a compliment.') }}</p>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('Previous') }}</span>
                        </button>
                        <button class="carousel-control-next w-5 me-3" type="button"
                            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">{{ __('Next') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx1 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx1.createLinearGradient(0, 130, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
        gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#438CE8",
                    backgroundColor: gradientStroke1,
                    borderWidth: 3,
                    fill: true,
                    data: [50, 40, 300, 120, 500, 150, 400, 130, 500],
                    maxBarThickness: 6

                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#fbfbfb',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#ccc',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
@endpush
