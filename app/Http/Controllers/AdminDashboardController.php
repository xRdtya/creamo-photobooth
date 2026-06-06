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
        $merchant     = Auth::guard('merchant')->user();
        $merchantId   = $merchant->id;

        $transactions = Transaction::with(['photoSessions:id,transaction_id,kode_download,status_cetak'])
            ->where('merchant_id', $merchantId)
            ->select('id', 'order_id', 'customer_name', 'email', 'gross_amount', 'payment_status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $reviews = Review::where('merchant_id', $merchantId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $activeDevices = PhotoSession::whereRaw('"is_active" = true')
            ->whereRaw('"last_ping_at" >= ?', [now()->subMinutes(5)])
            ->whereHas('transaction', fn($q) => $q->where('merchant_id', $merchantId))
            ->with(['transaction:id,order_id,merchant_id,created_at'])
            ->orderBy('last_ping_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('merchant', 'transactions', 'reviews', 'activeDevices'));
    }

    public function stats()
    {
        $merchant   = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;

        $today          = Carbon::today();
        $startOfWeek    = Carbon::now()->startOfWeek();
        $endOfWeek      = Carbon::now()->endOfWeek();
        $lastWeekStart  = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd    = Carbon::now()->subWeek()->endOfWeek();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $thisMonthStart = Carbon::now()->startOfMonth();

        $stats = Transaction::where('merchant_id', $merchantId)
            ->selectRaw("
            SUM(CASE WHEN payment_status = 'success' AND created_at BETWEEN ? AND ? THEN gross_amount ELSE 0 END) as revenue_this_week,
            SUM(CASE WHEN payment_status = 'success' AND created_at BETWEEN ? AND ? THEN gross_amount ELSE 0 END) as revenue_last_week,
            COUNT(CASE WHEN created_at BETWEEN ? AND ? THEN 1 END) as orders_this_week,
            COUNT(CASE WHEN created_at BETWEEN ? AND ? THEN 1 END) as orders_last_week,
            COUNT(CASE WHEN payment_status = 'success' THEN 1 END) as total_customers,
            COUNT(CASE WHEN payment_status = 'success' AND created_at BETWEEN ? AND ? THEN 1 END) as customers_last_month
        ", [
                $startOfWeek,
                $endOfWeek,
                $lastWeekStart,
                $lastWeekEnd,
                $startOfWeek,
                $endOfWeek,
                $lastWeekStart,
                $lastWeekEnd,
                $lastMonthStart,
                $thisMonthStart,
            ])->first();

        $photoStats = PhotoSession::join('transactions', 'photo_sessions.transaction_id', '=', 'transactions.id')
            ->where('transactions.merchant_id', $merchantId)
            ->selectRaw("
            COUNT(CASE WHEN DATE(photo_sessions.created_at) = ? THEN 1 END) as today,
            COUNT(CASE WHEN DATE(photo_sessions.created_at) = ? THEN 1 END) as yesterday
        ", [$today->toDateString(), Carbon::yesterday()->toDateString()])
            ->first();

        $revenueThisWeek     = $stats->revenue_this_week ?? 0;
        $revenueLastWeek     = $stats->revenue_last_week ?? 0;
        $totalOrdersThisWeek = $stats->orders_this_week ?? 0;
        $totalOrdersLastWeek = $stats->orders_last_week ?? 0;
        $totalCustomers      = $stats->total_customers ?? 0;
        $totalCustomersLastMonth = $stats->customers_last_month ?? 0;
        $totalPhotosToday    = $photoStats->today ?? 0;
        $totalPhotosYesterday = $photoStats->yesterday ?? 0;

        return response()->json([
            'revenueThisWeek'     => $revenueThisWeek,
            'revenueChangePercent' => $revenueLastWeek > 0
                ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
                : ($revenueThisWeek > 0 ? 100 : 0),
            'totalCustomers'      => $totalCustomers,
            'customerGrowth'      => $totalCustomersLastMonth > 0
                ? round((($totalCustomers - $totalCustomersLastMonth) / $totalCustomersLastMonth) * 100, 1)
                : 0,
            'totalPhotosToday'    => $totalPhotosToday,
            'photoGrowth'         => $totalPhotosYesterday > 0
                ? round((($totalPhotosToday - $totalPhotosYesterday) / $totalPhotosYesterday) * 100, 1)
                : 0,
            'totalOrdersThisWeek' => $totalOrdersThisWeek,
            'ordersChangePercent' => $totalOrdersLastWeek > 0
                ? round((($totalOrdersThisWeek - $totalOrdersLastWeek) / $totalOrdersLastWeek) * 100, 1)
                : 0,
        ]);
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
