@extends('layouts.app')

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold m-0">Laporan Penjualan</h2>
            <p class="text-secondary m-0">Statistik bulan ini</p>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <form action="{{ route(auth()->user()->role . '.sales') }}" method="GET" class="d-flex align-items-center">
                <label for="date" class="me-2 fw-bold text-secondary text-nowrap">Pilih Tanggal:</label>
                <input type="date" name="date" id="date" class="form-control border-0 shadow-sm rounded-pill px-3"
                    value="{{ $inputDate }}" onchange="this.form.submit()" onclick="try{this.showPicker()}catch(e){}"
                    style="background-color: #fff; cursor: pointer; max-width: 200px;">
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">
        <!-- Daily Stats -->
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background-color: #EAE5D9;">
                <h6 class="text-secondary fw-bold mb-3">Penjualan Harian</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;"><i class="bi bi-currency-dollar"></i></div>
                    <h4 class="fw-bold m-0 text-break">Rp {{ number_format($dailySales, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background-color: #EAE5D9;">
                <h6 class="text-secondary fw-bold mb-3">Orderan Harian</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;"><i class="bi bi-receipt"></i></div>
                    <h4 class="fw-bold m-0">{{ $dailyOrders }}</h4>
                </div>
            </div>
        </div>

        <!-- Monthly Stats -->
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background-color: #B0A695;">
                <h6 class="text-white fw-bold mb-3">Penjualan Bulanan</h6>
                <div class="d-flex align-items-center gap-3 text-white">
                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;"><i class="bi bi-currency-dollar"></i></div>
                    <h4 class="fw-bold m-0 text-break">Rp {{ number_format($monthlySales, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 h-100" style="background-color: #B0A695;">
                <h6 class="text-white fw-bold mb-3">Orderan Bulanan</h6>
                <div class="d-flex align-items-center gap-3 text-white">
                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;"><i class="bi bi-receipt"></i></div>
                    <h4 class="fw-bold m-0">{{ $monthlyOrders }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row g-4">
        <!-- Hourly Chart (New) -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4" style="background-color: #EAE5D9;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-bar-chart-fill fs-5"></i>
                    </div>
                    <h3 class="fw-bold m-0" style="color: #212529;">Orderan Harian</h3>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Grafik Penjualan Bulanan</h5>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Customer Chart -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Grafik Pelanggan</h5>
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="customerChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Refund Chart -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4 text-danger">Grafik Refund</h5>
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="refundChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const dates = @json($dates);

            // 0. Hourly Chart (Today)
            new Chart(document.getElementById('hourlyChart'), {
                type: 'line',
                data: {
                    labels: @json($hourlyLabels),
                    datasets: [{
                        label: 'Orderan',
                        data: @json($hourlyData),
                        borderColor: '#3E3C38',
                        backgroundColor: 'rgba(62, 60, 56, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#BDAFA1', borderDash: [5, 5] }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 1. Sales Chart
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: @json($salesValues),
                        borderColor: '#B0A695',
                        backgroundColor: 'rgba(176, 166, 149, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value, index, values) {
                                    if (value === 0) return '0';
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // 2. Customer Chart
            new Chart(document.getElementById('customerChart'), {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Jumlah Pelanggan',
                        data: @json($customerValues),
                        backgroundColor: '#776B5D',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            });

            // 3. Refund Chart
            new Chart(document.getElementById('refundChart'), {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Total Refund (Rp)',
                        data: @json($refundValues),
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value, index, values) {
                                    if (value === 0) return '0';
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection