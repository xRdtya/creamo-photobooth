<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PhotoSession;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $merchant = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;

        $today         = Carbon::today();
        $startOfWeek   = Carbon::now()->startOfWeek();
        $endOfWeek     = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd   = Carbon::now()->subWeek()->endOfWeek();
        $thirtyDaysAgo = Carbon::today()->subDays(29);

        $allTransactions    = Transaction::where('merchant_id', $merchantId)
            ->where('created_at', '>=', $thirtyDaysAgo)->get();
        $successTransactions = $allTransactions->where('payment_status', 'success');

        $revenueThisWeek = $successTransactions
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('gross_amount');
        $revenueLastWeek = $successTransactions
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->sum('gross_amount');
        $revenueChangePercent = $revenueLastWeek > 0
            ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
            : ($revenueThisWeek > 0 ? 100 : 0);

        $totalOrdersThisWeek = $allTransactions
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $totalOrdersLastWeek = $allTransactions
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $ordersChangePercent = $totalOrdersLastWeek > 0
            ? round((($totalOrdersThisWeek - $totalOrdersLastWeek) / $totalOrdersLastWeek) * 100, 1)
            : 0;

        $totalCustomers = Transaction::where('merchant_id', $merchantId)
            ->where('payment_status', 'success')->count();
        $totalCustomersLastMonth = Transaction::where('merchant_id', $merchantId)
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->startOfMonth()
            ])->count();
        $customerGrowth = $totalCustomersLastMonth > 0
            ? round((($totalCustomers - $totalCustomersLastMonth) / $totalCustomersLastMonth) * 100, 1)
            : 0;

        $photoBase = PhotoSession::whereHas(
            'transaction',
            fn($q) =>
            $q->where('merchant_id', $merchantId)
        );
        $totalPhotosToday     = (clone $photoBase)->whereDate('created_at', $today)->count();
        $totalPhotosYesterday = (clone $photoBase)->whereDate('created_at', Carbon::yesterday())->count();
        $photoGrowth = $totalPhotosYesterday > 0
            ? round((($totalPhotosToday - $totalPhotosYesterday) / $totalPhotosYesterday) * 100, 1)
            : 0;

        $transactions = Transaction::with('photoSessions')
            ->where('merchant_id', $merchantId)
            ->orderBy('created_at', 'desc')
            ->take(10)->get();

        $reviews = Review::where('merchant_id', $merchantId)
            ->orderBy('created_at', 'desc')
            ->take(10)->get();

        $activeDevices = PhotoSession::whereRaw('"is_active" = true')
            ->whereRaw('"last_ping_at" >= ?', [now()->subMinutes(5)])
            ->whereHas('transaction', fn($q) => $q->where('merchant_id', $merchantId))
            ->with(['transaction:id,order_id,merchant_id,created_at'])
            ->orderBy('last_ping_at', 'desc')->get();
        $activeDeviceCount = $activeDevices->count();

        // Chart di-load via AJAX
        $revenueChart = [];
        $revenueChartLastWeek = [];
        $monthlyStats = [];

        return view('admin.dashboard', compact(
            'merchant',
            'revenueThisWeek',
            'revenueLastWeek',
            'revenueChangePercent',
            'revenueChart',
            'revenueChartLastWeek',
            'totalCustomers',
            'customerGrowth',
            'totalPhotosToday',
            'photoGrowth',
            'transactions',
            'reviews',
            'monthlyStats',
            'totalOrdersThisWeek',
            'ordersChangePercent',
            'activeDevices',
            'activeDeviceCount',
        ));
    }

    public function chartData()
    {
        $merchant = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;
        $thirtyDaysAgo = Carbon::today()->subDays(29);

        $allTransactions = Transaction::where('merchant_id', $merchantId)
            ->where('payment_status', 'success')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->get();

        // Revenue chart 7 hari
        $revenueChart = [];
        $revenueChartLastWeek = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $revenueChart[] = [
                'label' => $day->format('D'),
                'date'  => $day->format('d'),
                'value' => $allTransactions
                    ->filter(fn($t) => Carbon::parse($t->created_at)->isSameDay($day))
                    ->sum('gross_amount'),
            ];

            $dayLast = Carbon::today()->subDays($i + 7);
            $revenueChartLastWeek[] = [
                'label' => $dayLast->format('D'),
                'date'  => $dayLast->format('d'),
                'value' => $allTransactions
                    ->filter(fn($t) => Carbon::parse($t->created_at)->isSameDay($dayLast))
                    ->sum('gross_amount'),
            ];
        }

        // Monthly stats 30 hari
        $monthlyStats = [];
        $allTransactionsWithFailed = Transaction::where('merchant_id', $merchantId)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->get();

        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dayTransactions = $allTransactionsWithFailed->filter(
                fn($t) => Carbon::parse($t->created_at)->isSameDay($day)
            );
            $monthlyStats[] = [
                'date'    => $day->format('d M'),
                'revenue' => $dayTransactions->where('payment_status', 'success')->sum('gross_amount'),
                'orders'  => $dayTransactions->count(),
            ];
        }

        return response()->json([
            'revenueChart'         => $revenueChart,
            'revenueChartLastWeek' => $revenueChartLastWeek,
            'monthlyStats'         => $monthlyStats,
        ]);
    }

    /**
     * API endpoint: kembalikan daftar active devices sebagai JSON
     * (untuk polling live dari frontend).
     */
    public function activeDevices()
    {
        $merchant = Auth::guard('merchant')->user();

        $devices = PhotoSession::active()
            ->whereHas('transaction', fn($q) => $q->where('merchant_id', $merchant->id))
            ->with(['transaction:id,order_id,created_at'])
            ->orderBy('last_ping_at', 'desc')
            ->get()
            ->map(fn($s) => [
                'id'            => $s->id,
                'device_name'   => $s->device_name ?? 'Device #' . $s->id,
                'order_id'      => $s->transaction->order_id ?? '-',
                'email'         => $s->email,
                'status_cetak'  => $s->status_cetak,
                'waktu_mulai'   => $s->waktu_mulai?->format('H:i:s'),
                'last_ping_at'  => $s->last_ping_at?->diffForHumans(),
            ]);

        return response()->json([
            'count'   => $devices->count(),
            'devices' => $devices,
        ]);
    }
}
