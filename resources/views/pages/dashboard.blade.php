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
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('Goals') }}</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            @forelse ($goals as $key => $goal)
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-header pb-0 p-3">
                                            <h6 class="mb-0">{{ $goal->name }}</h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <canvas id="goal_chart_{{ $key }}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>{{ __('No data available.') }}</p>
                            @endforelse
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
                                            <h6 class="text-sm mb-0">
                                                ${{ number_format($count_incomes - $count_expense, 1) }}</h6>
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
                                            <h6 class="text-sm mb-0">
                                                ${{ number_format($count_incomes_am_owed - $count_expense_must, 1) }}</h6>
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
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
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
                                        <a class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"
                                            href="{{ route('expenses-categories.show', $category->id) }}">
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
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')
@endsection

@section('js')
    <script src="./assets/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pass PHP data to JavaScript
        const goalsData = @json($goals);
    
        // Custom Plugin to Add Styled Text in Center
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart) {
                const { width } = chart;
                const { height } = chart;
                const ctx = chart.ctx;
    
                // Calculate the total as goal.amount - goal.total_savings
                const total = chart.data.datasets[0].data[0] - chart.data.datasets[0].data[1];
    
                // Format the total as currency
                const formattedTotal = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2,
                }).format(total);
    
                // Adjust font size and style
                const fontSize = (height / 20).toFixed(2); // Adjust font size dynamically
                ctx.save();
                ctx.font = `bold ${fontSize}px sans-serif`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#333'; // Dark gray color for better visibility
    
                // Calculate X and Y positions for the text
                const x = width / 2;
                const y = height / 2;
    
                // Draw the formatted total in the center
                ctx.fillText(formattedTotal, x, y - 10); // Slightly move up (-10) for better positioning
    
                ctx.restore();
            }
        };
    
        // Register the plugin globally
        Chart.register(centerTextPlugin);
    
        // Loop through each goal and create a chart
        goalsData.forEach((goal, index) => {
            const ctx = document.getElementById(`goal_chart_${index}`).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Meta', 'Logrado'],
                    datasets: [{
                        label: goal.name,
                        data: [goal.amount, goal.total_savings],
                        backgroundColor: ['#36A2EB', '#FFCE56']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    
    
@endsection
