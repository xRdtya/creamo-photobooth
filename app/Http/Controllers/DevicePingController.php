<?php

namespace App\Http\Controllers;

use App\Models\PhotoSession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DevicePingController extends Controller
{
    /**
     * Dipanggil oleh device photobooth setiap ~60 detik.
     * Menandai sesi sebagai aktif dan memperbarui last_ping_at.
     *
     * POST /photo/device/ping
     * Body JSON: { "session_id": 1, "device_name": "Booth A" }
     *   ATAU
     *             { "order_id": "CRM-123", "device_name": "Booth A" }
     */
    public function ping(Request $request)
    {
        $session = $this->resolveSession($request);

        if (!$session) {
            return response()->json(['message' => 'Sesi tidak ditemukan.'], 404);
        }

        $session->update([
            'is_active'    => true,
            'last_ping_at' => Carbon::now(),
            'device_name'  => $request->input('device_name', $session->device_name ?? 'Device #' . $session->id),
        ]);

        return response()->json([
            'message'      => 'Ping diterima.',
            'session_id'   => $session->id,
            'device_name'  => $session->device_name,
            'last_ping_at' => $session->fresh()->last_ping_at,
        ]);
    }

    /**
     * Dipanggil saat sesi foto selesai / device tidak aktif lagi.
     *
     * POST /photo/device/ping-off
     * Body JSON: { "session_id": 1 }   ATAU   { "order_id": "CRM-123" }
     */
    public function pingOff(Request $request)
    {
        $session = $this->resolveSession($request);

        if (!$session) {
            return response()->json(['message' => 'Sesi tidak ditemukan.'], 404);
        }

        $session->update([
            'is_active'    => false,
            'waktu_selesai' => Carbon::now(),
        ]);

        return response()->json([
            'message'    => 'Sesi diakhiri.',
            'session_id' => $session->id,
        ]);
    }

    /**
     * ── DEVELOPMENT ONLY ────────────────────────────────────────────────────
     * Buat PhotoSession dummy langsung aktif untuk keperluan testing.
     * GET /photo/device/test-ping?device=Booth+A&order_id=CRM-xxx
     *
     * Hapus route ini sebelum production!
     */
    public function testPing(Request $request)
    {
        // Cari transaksi pertama yang tersedia (atau pakai order_id dari query string)
        $orderId = $request->query('order_id');
        $transaction = $orderId
            ? Transaction::where('order_id', $orderId)->first()
            : Transaction::latest()->first();

        if (!$transaction) {
            return response()->json(['message' => 'Tidak ada transaksi. Buat transaksi dulu lewat /photo/payment.'], 404);
        }

        // Cari atau buat PhotoSession untuk transaksi ini
        $session = PhotoSession::firstOrCreate(
            ['transaction_id' => $transaction->id],
            [
                'email'        => $transaction->email ?? 'test@creamo.com',
                'kode_download' => 'TEST-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'waktu_mulai'  => Carbon::now(),
                'status_cetak' => 'printing',
            ]
        );

        $deviceName = $request->query('device', 'Booth Test');

        $session->update([
            'is_active'    => true,
            'last_ping_at' => Carbon::now(),
            'device_name'  => $deviceName,
            'status_cetak' => $request->query('status', $session->status_cetak),
        ]);

        return response()->json([
            'message'      => '✅ Device sekarang terlihat AKTIF di dashboard!',
            'session_id'   => $session->id,
            'device_name'  => $session->device_name,
            'order_id'     => $transaction->order_id,
            'last_ping_at' => $session->last_ping_at,
            'tip'          => 'Buka /dashboard dan klik kartu Active Now untuk melihatnya.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function resolveSession(Request $request): ?PhotoSession
    {
        if ($request->filled('session_id')) {
            return PhotoSession::find($request->input('session_id'));
        }

        if ($request->filled('order_id')) {
            $tx = Transaction::where('order_id', $request->input('order_id'))->first();
            return $tx ? PhotoSession::where('transaction_id', $tx->id)->first() : null;
        }

        return null;
    }
}
