<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PhotoSession;
use App\Models\Review;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $start = microtime(true);
        $merchant     = Auth::guard('merchant')->user();
        $merchantId   = $merchant->id;

        $t1 = microtime(true);
        $transactions = Transaction::where('merchant_id', $merchantId)
            ->select('id', 'order_id', 'customer_name', 'email', 'phone_number', 'gross_amount', 'payment_status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        error_log('transactions: ' . round((microtime(true) - $t1) * 1000) . 'ms');

        $t2 = microtime(true);
        $reviews = Review::where('merchant_id', $merchantId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        error_log('reviews: ' . round((microtime(true) - $t2) * 1000) . 'ms');

        $t3 = microtime(true);
        $activeDevices = PhotoSession::join('transactions', 'photo_sessions.transaction_id', '=', 'transactions.id')
            ->where('transactions.merchant_id', $merchantId)
            ->where('photo_sessions.is_active', true)
            ->where('photo_sessions.last_ping_at', '>=', now()->subMinutes(5))
            ->select(
                'photo_sessions.*',
                'transactions.order_id as trx_order_id',
                'transactions.created_at as trx_created_at'
            )
            ->orderBy('photo_sessions.last_ping_at', 'desc')
            ->get();

        $activeDeviceCount = $activeDevices->count();
        error_log('activeDevices: ' . round((microtime(true) - $t3) * 1000) . 'ms');

        error_log('TOTAL: ' . round((microtime(true) - $start) * 1000) . 'ms');

        return view('admin.dashboard', compact('merchant', 'transactions', 'reviews', 'activeDeviceCount'));
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

        $devices = PhotoSession::join('transactions', 'photo_sessions.transaction_id', '=', 'transactions.id')
            ->where('transactions.merchant_id', $merchant->id)
            ->where('photo_sessions.is_active', true)
            ->where('photo_sessions.last_ping_at', '>=', now()->subMinutes(5))
            ->select(
                'photo_sessions.*',
                'transactions.order_id as trx_order_id',
                'transactions.created_at as trx_created_at'
            )
            ->orderBy('photo_sessions.last_ping_at', 'desc')
            ->get()
            ->map(fn($s) => [
                'id'           => $s->id,
                'device_name'  => $s->device_name ?? 'Device #' . $s->id,
                'order_id'     => $s->trx_order_id ?? '-',
                'email'        => $s->email,
                'status_cetak' => $s->status_cetak,
                'waktu_mulai'  => $s->waktu_mulai ? \Carbon\Carbon::parse($s->waktu_mulai)->format('H:i:s') : null,
                'last_ping_at' => $s->last_ping_at ? \Carbon\Carbon::parse($s->last_ping_at)->diffForHumans() : null,
            ]);

        return response()->json([
            'count'   => $devices->count(),
            'devices' => $devices,
        ]);
    }

    /**
     * Ganti password merchant.
     */
    public function changePassword(Request $request)
    {
        $merchant = Auth::guard('merchant')->user();

        // Merchant yang login via OAuth tanpa password
        if (empty($merchant->password) || !str_starts_with($merchant->password, '$')) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda terdaftar melalui Google/Apple. Silakan set password baru.',
                'needs_set' => true,
            ], 422);
        }

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $merchant->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password lama tidak cocok.',
            ], 422);
        }

        $merchant->password = Hash::make($request->new_password);
        $merchant->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!',
        ]);
    }

    /**
     * Set password untuk merchant OAuth (belum punya password).
     */
    public function setPassword(Request $request)
    {
        $merchant = Auth::guard('merchant')->user();

        // Hanya boleh dipakai jika merchant belum punya password (akun OAuth)
        if (!empty($merchant->password) && str_starts_with($merchant->password, '$')) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sudah memiliki password. Gunakan fitur Ubah Password.',
            ], 422);
        }

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $merchant->password = Hash::make($request->new_password);
        $merchant->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diset! Sekarang Anda bisa login dengan email & password.',
        ]);
    }

    /**
     * Request penarikan dana (withdraw).
     */
    public function requestWithdraw(Request $request)
    {
        $merchant = Auth::guard('merchant')->user();

        $request->validate([
            'amount'         => 'required|numeric|min:10000',
            'method'         => 'required|in:bank_transfer,ewallet',
            'bank_name'      => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ]);

        // Hitung saldo tersedia
        $totalRevenue = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->sum('gross_amount');

        $totalWithdrawn = Withdrawal::where('merchant_id', $merchant->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        $available = $totalRevenue - $totalWithdrawn;

        if ($request->amount > $available) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah penarikan melebihi saldo tersedia (IDR ' . number_format($available, 0, ',', '.') . ').',
            ], 422);
        }

        $withdrawal = Withdrawal::create([
            'merchant_id'    => $merchant->id,
            'amount'         => $request->amount,
            'method'         => $request->method,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'pending',
            'notes'          => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Request penarikan dana berhasil dikirim! Menunggu proses.',
            'withdrawal' => $withdrawal,
        ]);
    }

    /**
     * Riwayat penarikan dana.
     */
    public function withdrawHistory()
    {
        $merchant = Auth::guard('merchant')->user();

        $totalRevenue = Transaction::where('merchant_id', $merchant->id)
            ->where('payment_status', 'success')
            ->sum('gross_amount');

        $totalWithdrawn = Withdrawal::where('merchant_id', $merchant->id)
            ->where('status', 'approved')
            ->sum('amount');

        $totalPending = Withdrawal::where('merchant_id', $merchant->id)
            ->where('status', 'pending')
            ->sum('amount');

        $withdrawals = Withdrawal::where('merchant_id', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($w) => [
                'id'             => $w->id,
                'amount'         => $w->amount,
                'method'         => $w->method,
                'bank_name'      => $w->bank_name,
                'account_number' => $w->account_number,
                'account_holder' => $w->account_holder,
                'status'         => $w->status,
                'notes'          => $w->notes,
                'created_at'     => $w->created_at->format('d M Y H:i'),
            ]);

        return response()->json([
            'totalRevenue'   => $totalRevenue,
            'totalWithdrawn' => $totalWithdrawn,
            'totalPending'   => $totalPending,
            'available'      => $totalRevenue - $totalWithdrawn - $totalPending,
            'withdrawals'    => $withdrawals,
        ]);
    }
}
