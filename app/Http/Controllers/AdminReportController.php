<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    public function sales(Request $request)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager')
            abort(403);

        // Date Filter Logic
        $selectedDate = $request->input('date') ? Carbon::parse($request->date) : Carbon::today();

        // Month range based on selected date
        $startDate = $selectedDate->copy()->startOfMonth();
        $endDate = $selectedDate->copy()->endOfMonth();

        // 1. Sales Chart Data (Daily Sales this month)
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Customer Chart Data (Daily Unique Customers this month)
        $customerData = Order::selectRaw('DATE(created_at) as date, COUNT(DISTINCT customer_name) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Refund Chart Data (Daily Refunds this month)
        $refundData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'refunded')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare labels (dates) and data arrays for Chart.js
        $dates = [];
        $salesValues = [];
        $customerValues = [];
        $refundValues = [];

        // Fill gaps with 0 if needed, or just push existing data. 
        // For simplicity, let's map existing data.

        // Helper to format data
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $dates[] = $date->format('d M');

            $salesValues[] = $salesData->firstWhere('date', $dateString)->total ?? 0;
            $customerValues[] = $customerData->firstWhere('date', $dateString)->count ?? 0;
            $refundValues[] = $refundData->firstWhere('date', $dateString)->total ?? 0;
        }

        // Summary Stats (Total for the month)
        $monthlySales = array_sum($salesValues);
        $monthlyOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Daily Stats (for selected date)
        $dailySales = Order::whereDate('created_at', $selectedDate)->where('payment_status', 'paid')->sum('total_amount');
        $dailyOrders = Order::whereDate('created_at', $selectedDate)->count();


        // 4. Hourly Sales Chart (Selected Date 08:00 - 23:00)
        $isSqlite = config('database.default') === 'sqlite';
        $hourExpression = $isSqlite ? "strftime('%H', created_at)" : "HOUR(created_at)";

        $hourlyOrders = Order::selectRaw("$hourExpression as hour, count(*) as count")
            ->whereDate('created_at', $selectedDate)
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $hourlyOrders = $hourlyOrders->mapWithKeys(function ($item, $key) {
            return [(int) $key => $item];
        });

        $hourlyLabels = [];
        $hourlyData = [];
        for ($i = 8; $i <= 23; $i++) {
            $hourlyLabels[] = sprintf('%02d:00', $i);
            $hourlyData[] = $hourlyOrders->get($i, 0);
        }

        Carbon::setLocale('id');
        $dateLabel = $selectedDate->translatedFormat('l, d F Y');
        $inputDate = $selectedDate->format('Y-m-d');


        return view('admin.reports.sales', compact(
            'dailySales',
            'dailyOrders',
            'monthlySales',
            'monthlyOrders',
            'dates',
            'salesValues',
            'customerValues',
            'refundValues',
            'hourlyLabels',
            'hourlyData',
            'dateLabel',
            'inputDate'
        ));
    }

    public function history(Request $request)
    {
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager')
            abort(403);

        $query = Order::with('user', 'items.product');

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('table_number', 'like', "%{$search}%")
                    ->orWhereHas('items.product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Date Filter (Year & Month)
        if ($request->filled('filter_month')) {
            $date = Carbon::parse($request->filter_month);
            $query->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.reports.history', compact('orders'));
    }

    public function bulkDelete(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $ids = $request->input('ids');
        if (is_array($ids) && count($ids) > 0) {
            Order::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', count($ids) . ' history records deleted successfully.');
        }

        return redirect()->back()->with('error', 'No records selected for deletion.');
    }
}
