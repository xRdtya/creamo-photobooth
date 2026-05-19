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

        // ── Revenue 1 Minggu ──────────────────────────────────────────────
        $today        = Carbon::today();
        $startOfWeek  = Carbon::now()->startOfWeek();
        $endOfWeek    = Carbon::now()->endOfWeek();
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd   = Carbon::now()->subWeek()->endOfWeek();

        $revenueThisWeek = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('gross_amount');

        $revenueLastWeek = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('gross_amount');

        $revenueChangePercent = $revenueLastWeek > 0
            ? round((($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek) * 100, 1)
            : ($revenueThisWeek > 0 ? 100 : 0);

        // Revenue per hari selama 7 hari terakhir (untuk chart)
        $revenueChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $revenueChart[] = [
                'label' => $day->format('D'),
                'date'  => $day->format('d'),
                'value' => Transaction::where('merchant_id', $merchant->id)
                    ->where('payment_status', 'success')
                    ->whereDate('created_at', $day)
                    ->sum('gross_amount'),
            ];
        }

        // ── Total Customer & Total Foto (hari ini) ────────────────────────
        $totalCustomers = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->count();

        $totalCustomersLastMonth = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->count();

        $customerGrowth = $totalCustomersLastMonth > 0
            ? round((($totalCustomers - $totalCustomersLastMonth) / $totalCustomersLastMonth) * 100, 1)
            : 0;

        $totalPhotosToday = PhotoSession::whereHas('transaction', function ($q) use ($merchant) {
            $q->where('merchant_id', $merchant->id);
        })->whereDate('created_at', $today)->count();

        $totalPhotosYesterday = PhotoSession::whereHas('transaction', function ($q) use ($merchant) {
            $q->where('merchant_id', $merchant->id);
        })->whereDate('created_at', Carbon::yesterday())->count();

        $photoGrowth = $totalPhotosYesterday > 0
            ? round((($totalPhotosToday - $totalPhotosYesterday) / $totalPhotosYesterday) * 100, 1)
            : 0;

        // ── Deskripsi Customer (10 transaksi terbaru) ─────────────────────
        $transactions = Transaction::with('photoSessions')
            ->where('merchant_id', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ── Reviews ───────────────────────────────────────────────────────
        $reviews = Review::where('merchant_id', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ── Active Devices ────────────────────────────────────────────────
        // Device dianggap aktif jika is_active=true dan last_ping_at dalam 5 menit
        $activeDevices = PhotoSession::active()
            ->whereHas('transaction', fn($q) => $q->where('merchant_id', $merchant->id))
            ->with(['transaction:id,order_id,merchant_id,created_at'])
            ->orderBy('last_ping_at', 'desc')
            ->get();

        $activeDeviceCount = $activeDevices->count();

        // ── Statistik Bisnis 1 Bulan (per hari) ───────────────────────────
        $monthlyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $monthlyStats[] = [
                'date'     => $day->format('d M'),
                'revenue'  => Transaction::where('merchant_id', $merchant->id)
                    ->where('payment_status', 'success')
                    ->whereDate('created_at', $day)
                    ->sum('gross_amount'),
                'orders'   => Transaction::where('merchant_id', $merchant->id)
                    ->whereDate('created_at', $day)
                    ->count(),
            ];
        }

        $totalOrdersThisWeek  = Transaction::where('merchant_id', $merchant->id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();
        $totalOrdersLastWeek  = Transaction::where('merchant_id', $merchant->id)
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->count();
        $ordersChangePercent  = $totalOrdersLastWeek > 0
            ? round((($totalOrdersThisWeek - $totalOrdersLastWeek) / $totalOrdersLastWeek) * 100, 1)
            : 0;

        return view('admin.dashboard', compact(
            'merchant',
            'revenueThisWeek',
            'revenueLastWeek',
            'revenueChangePercent',
            'revenueChart',
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
