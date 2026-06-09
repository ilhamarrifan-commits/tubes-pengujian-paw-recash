<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date');
        $today = $date ? Carbon::parse($date) : Carbon::today();

        $totalSalesToday = Order::whereDate('created_at', $today)->sum('total_amount');
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $totalRefundsToday = Order::whereDate('created_at', $today)->where('payment_status', 'refunded')->sum('total_amount');

        $totalSalesMonth = Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->sum('total_amount');
        $totalOrdersMonth = Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->count();
        $totalRefundsMonth = Order::whereMonth('created_at', $today->month)->whereYear('created_at', $today->year)->where('payment_status', 'refunded')->sum('total_amount');

        // Separated Staff Performance
        $topCashiers = User::where('role', 'cashier')
            ->select('id', 'name', 'last_login_at', 'last_logout_at')
            ->withCount([
                'orders' => function ($query) use ($today) {
                    $query->whereMonth('created_at', $today->month)->whereYear('created_at', $today->year);
                }
            ])
            ->orderByDesc('orders_count')
            ->take(3)
            ->get();

        $managerStats = User::where('role', 'manager')
            ->select('id', 'name', 'last_login_at', 'last_logout_at')
            ->get();


        // Chart Data: Orders per hour for selected date
        $isSqlite = config('database.default') === 'sqlite';
        $hourExpression = $isSqlite ? "strftime('%H', created_at)" : "HOUR(created_at)";

        $ordersPerHour = Order::selectRaw("$hourExpression as hour, count(*) as count")
            ->whereDate('created_at', $today)
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour');

        // Cast keys to integer to ensure matching with loop below
        $ordersPerHour = $ordersPerHour->mapWithKeys(function ($item, $key) {
            return [(int) $key => $item];
        });

        // Initialize array for hours 08:00 to 23:00
        $chartLabels = [];
        $chartData = [];

        for ($i = 8; $i <= 23; $i++) {
            $chartLabels[] = sprintf('%02d:00', $i);
            $chartData[] = $ordersPerHour->get($i, 0);
        }

        // Current Date for display
        Carbon::setLocale('id'); // Attempt to set ID, fallback to EN if not installed
        $dateLabel = $today->translatedFormat('l, d F Y');

        // Determine view based on role
        $role = auth()->user()->role;
        $view = $role === 'admin' ? 'admin.dashboard' : 'manager.dashboard';

        return view($view, compact(
            'totalSalesToday',
            'totalOrdersToday',
            'totalRefundsToday',
            'totalSalesMonth',
            'totalOrdersMonth',
            'totalRefundsMonth',
            'topCashiers',
            'managerStats',
            'chartLabels',
            'chartData',
            'dateLabel',
            'today'
        ));
    }
}
