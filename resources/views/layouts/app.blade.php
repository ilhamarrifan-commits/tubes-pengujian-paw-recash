<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReCash</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F6F2;
            overflow: hidden;
            /* App-like feel */
            height: 100vh;
        }

        .bg-app-bg {
            background-color: #F8F6F2;
        }

        .active-nav {
            background-color: #EAE5D9;
            color: #333 !important;
            font-weight: bold;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #bbb;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100 p-0">
        <div class="row h-100 g-0">
            <!-- Sidebar for Manager/Admin -->
            @if(auth()->check() && (auth()->user()->role === 'manager' || auth()->user()->role === 'admin'))
                <div class="col-md-2 p-4 d-flex flex-column h-100"
                    style="background-color: #F5F5EC; border-right: 1px solid #EAE5D9;">
                    <div class="mb-5 px-2">
                        <img src="{{ asset('images/logonama.png') }}" alt="ReCash" style="max-width: 150px; height: auto;">
                    </div>

                    <nav class="nav flex-column gap-2 flex-grow-1">
                        @php $role = auth()->user()->role; @endphp
                        <a class="nav-link {{ request()->routeIs($role . '.dashboard') ? 'active-nav' : '' }} text-secondary d-flex align-items-center gap-3 py-3 px-3 rounded-4"
                            href="{{ route($role . '.dashboard') }}">
                            <i class="bi bi-grid-fill fs-5"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs($role . '.history') ? 'active-nav' : '' }} text-secondary d-flex align-items-center gap-3 py-3 px-3 rounded-4"
                            href="{{ route($role . '.history') }}">
                            <i class="bi bi-clock-history fs-5"></i> History
                        </a>
                        <a class="nav-link {{ request()->routeIs($role . '.sales') ? 'active-nav' : '' }} text-secondary d-flex align-items-center gap-3 py-3 px-3 rounded-4"
                            href="{{ route($role . '.sales') }}">
                            <i class="bi bi-graph-up-arrow fs-5"></i> Sales
                        </a>

                        <a class="nav-link {{ request()->routeIs('manager.products*') ? 'active-nav' : '' }} text-secondary d-flex align-items-center gap-3 py-3 px-3 rounded-4"
                            href="{{ route('manager.products.index') }}">
                            <i class="bi bi-book fs-5"></i> Menu
                        </a>

                        @if($role === 'admin' || $role === 'manager')
                            <a class="nav-link {{ request()->routeIs($role . '.staff*') ? 'active-nav' : '' }} text-secondary d-flex align-items-center gap-3 py-3 px-3 rounded-4"
                                href="{{ route($role . '.staff.index') }}">
                                <i class="bi bi-people fs-5"></i> Staff
                            </a>
                        @endif
                    </nav>

                    <div class="mt-auto pt-4 border-top">
                        <a href="{{ route($role . '.profile') }}"
                            class="text-decoration-none d-flex align-items-center gap-3 mb-3">
                            <div class="bg-dark rounded-circle text-white d-flex align-items-center justify-content-center"
                                style="width:45px;height:45px; font-size: 1.2rem;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div style="line-height: 1.2;">
                                <div class="fw-bold text-dark">{{ Auth::user()->name }}</div>
                                <div class="small text-muted" style="font-size: 0.8rem;">+62xxxx789</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Main Content Area for Manager/Admin -->
                <div class="col-md-10 p-5 overflow-auto h-100" style="background-color: #F8F6F2;">
                    @yield('content')
                </div>

            @else
                <!-- Layout for Cashier / Public (Full Width) -->
                <div class="col-12 h-100 p-4" style="background-color: #F8F6F2; overflow-y: auto;">
                    @yield('content')
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>