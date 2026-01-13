@extends('layouts.app')

@section('content')
    <h2 class="fw-bold mb-4">Dashboard</h2>

    <div class="row g-4">
        <!-- Left Column: Chart & Table -->
        <div class="col-lg-8">
            <!-- Chart Section -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background-color: #EAE5D9;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <h4 class="fw-bold m-0">Orderan Harian</h4>
                    </div>
                    <div style="position: relative; height: 180px; width: 100%;">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cashier Performance -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background-color: #EAE5D9;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 class="fw-bold m-0">Performa Kasir</h4>
                    </div>

                    <table class="table table-borderless text-dark">
                        <thead>
                            <tr class="text-secondary fw-bold" style="border-bottom: 1px solid #999;">
                                <th class="pb-3" style="width: 35%;">Nama</th>
                                <th class="pb-3" style="width: 20%;">Shift</th>
                                <th class="pb-3" style="width: 25%;">Status</th>
                                <th class="pb-3" style="width: 20%;">Total Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topCashiers as $cashier)
                                <tr style="border-bottom: 1px solid #ccc;">
                                    <td class="py-3">{{ $cashier->name }}</td>
                                    <td class="py-3 text-secondary">
                                        @php
                                            $loginTime = $cashier->last_login_at;
                                            $shift = '-';
                                            if($loginTime) {
                                                $hour = $loginTime->hour;
                                                if($hour >= 5 && $hour < 12) $shift = 'Pagi';
                                                elseif($hour >= 12 && $hour < 18) $shift = 'Siang';
                                                else $shift = 'Malam';
                                            }
                                        @endphp
                                        {{ $shift }}
                                    </td>
                                    <td class="py-3 text-secondary">
                                        {{ $cashier->last_login_at ? ($cashier->last_logout_at ? 'Selesai' : 'Masih Bertugas') : '-' }}
                                    </td>
                                    <td class="py-3 fw-bold">{{ $cashier->orders_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manager Attendance -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background-color: #EAE5D9;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h4 class="fw-bold m-0">Kehadiran Manager</h4>
                    </div>

                    <table class="table table-borderless text-dark">
                        <thead>
                            <tr class="text-secondary fw-bold" style="border-bottom: 1px solid #999;">
                                <th class="pb-3" style="width: 35%;">Nama</th>
                                <th class="pb-3" style="width: 20%;">Shift</th>
                                <th class="pb-3" style="width: 25%;">Online</th>
                                <th class="pb-3" style="width: 20%;">Offline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($managerStats as $manager)
                                <tr style="border-bottom: 1px solid #ccc;">
                                    <td class="py-3">{{ $manager->name }}</td>
                                    <td class="py-3 text-secondary">
                                        @php
                                            $loginTime = $manager->last_login_at;
                                            $shift = '-';
                                            if($loginTime) {
                                                $hour = $loginTime->hour;
                                                if($hour >= 5 && $hour < 12) $shift = 'Pagi';
                                                elseif($hour >= 12 && $hour < 18) $shift = 'Siang';
                                                else $shift = 'Malam';
                                            }
                                        @endphp
                                        {{ $shift }}
                                    </td>
                                    <td class="py-3 text-secondary">
                                        {{ $manager->last_login_at ? $manager->last_login_at->format('H:i') : '-' }}
                                    </td>
                                    <td class="py-3 text-secondary">
                                        {{ $manager->last_logout_at ? $manager->last_logout_at->format('H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">No managers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Section (Top Staff Banner) -->
            <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #B0A695; color: #4A4A35;">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <i class="bi bi-graph-up-arrow fs-3"></i>
                    <h4 class="fw-bold m-0">Top Staff Bulanan</h4>
                </div>
                <div class="row">
                    <div class="col-md-4 d-flex align-items-center gap-3">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"><i class="bi bi-person fw-bold fs-4"></i></div>
                        <div>
                            <div class="fw-bold">Staff 1</div>
                            <div class="small">522 Order</div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center gap-3">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"><i class="bi bi-person fw-bold fs-4"></i></div>
                        <div>
                            <div class="fw-bold">Staff 2</div>
                            <div class="small">512 Order</div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center gap-3">
                        <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"><i class="bi bi-person fw-bold fs-4"></i></div>
                        <div>
                            <div class="fw-bold">Staff 3</div>
                            <div class="small">416 Order</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Stats Cards -->
        <div class="col-lg-4">
            <!-- Date Picker Dummy-->
            <!-- Date Picker Form -->
            <form action="{{ route(auth()->user()->role . '.dashboard') }}" method="GET" class="mb-4">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                    <span class="input-group-text border-0 bg-white ps-4 text-secondary"><i
                            class="bi bi-calendar"></i></span>
                    <input type="date" name="date" class="form-control border-0 text-secondary fw-bold"
                        value="{{ isset($today) ? $today->format('Y-m-d') : date('Y-m-d') }}" onchange="this.form.submit()"
                        style="outline: none; box-shadow: none;">
                </div>
            </form>

            <div class="d-flex flex-column gap-3">
                <!-- Sales Daily -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #EAE5D9;">
                    <h6 class="text-secondary fw-bold mb-2">Total Penjualan Harian</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">$</div>
                        <h3 class="fw-bold m-0">Rp{{ number_format($totalSalesToday, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Sales Monthly -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #B0A695;">
                    <h6 class="text-dark fw-bold mb-2">Total Penjualan Bulanan</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;">$</div>
                        <h3 class="fw-bold m-0">Rp{{ number_format($totalSalesMonth, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Orders Daily -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #EAE5D9;">
                    <h6 class="text-secondary fw-bold mb-2">Total Orderan Harian</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;"><i class="bi bi-receipt"></i></div>
                        <h3 class="fw-bold m-0">{{ $totalOrdersToday }}</h3>
                    </div>
                </div>

                <!-- Orders Monthly -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #B0A695;">
                    <h6 class="text-dark fw-bold mb-2">Total Orderan Bulanan</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;"><i class="bi bi-receipt"></i></div>
                        <h3 class="fw-bold m-0">{{ $totalOrdersMonth }}</h3>
                    </div>
                </div>

                <!-- Refund Daily -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #EAE5D9;">
                    <h6 class="text-secondary fw-bold mb-2">Total Refund Harian</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;"><i class="bi bi-arrow-return-left"></i></div>
                        <h3 class="fw-bold m-0">Rp{{ number_format($totalRefundsToday, 0, ',', '.') }}</h3>
                    </div>
                </div>

                <!-- Refund Monthly -->
                <div class="card border-0 rounded-4 shadow-sm p-4" style="background-color: #B0A695;">
                    <h6 class="text-dark fw-bold mb-2">Total Refund Bulanan</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                            style="width:40px;height:40px;"><i class="bi bi-arrow-return-left"></i></div>
                        <h3 class="fw-bold m-0">Rp{{ number_format($totalRefundsMonth, 0, ',', '.') }}</h3>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Orderan',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#3E3C38',
                    backgroundColor: 'rgba(62, 60, 56, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                animation: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#BDAFA1', borderDash: [5, 5] }
                    },
                    x: { grid: { display: false } }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection