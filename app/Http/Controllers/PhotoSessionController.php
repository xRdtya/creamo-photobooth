<?php

namespace App\Http\Controllers;

use App\Models\PhotoSession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PhotoSessionController extends Controller
{
    public function index(Request $request, $orderId) 
    {
        $order = Transaction::where('order_id', $orderId)->firstOrFail();

        $session = PhotoSession::where('transaction_id', $order->id)->firstOrFail();

        $photos = explode(',', $session->link_file_foto);

        $frames = [
            ['id' => 1, 'name' => 'Classic White', 'image' => '1.png', 'color' => '#FFFFFF'],
            ['id' => 2, 'name' => 'Retro Film', 'image' => '2.png', 'color' => '#1A1A1A'],
            ['id' => 3, 'name' => 'Soft Pastel', 'image' => '3.png', 'color' => '#FFD1DC'],
        ];
    
        return view('Customer.frame', compact('frames', 'photos', 'order', 'session'));
    }

    public function upload(Request $request)
    {
        try {
            $photos = $request->photos;
            $orderId = $request->order_id;

            if (!$photos || count($photos) < 4) {
                return response()->json(['success' => false, 'message' => 'Data foto tidak lengkap!'], 400);
            }

            $savedPaths = [];
            $folderPath = "photos/" . $orderId;

            foreach ($photos as $index => $base64Data) {
                $img = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
                $img = str_replace(' ', '+', $img);

                $data = base64_decode($img);

                if ($data === false) {
                    return response()->json(['success' => false, 'message' => 'Gagal membaca data foto ke-' . ($index + 1)], 500);
                }

                $fileName = "photo_" . ($index + 1) . "_" . ($orderId) . ".png";
                $fullPath = $folderPath . "/" . $fileName;

                Storage::disk('s3')->put($fullPath, $data);

                $savedPaths[] = $fullPath;
            }

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $session = PhotoSession::firstOrNew(['transaction_id' => $transaction->id]);

            if (!$session->exists) {
                $session->email = $transaction->email;
                $session->kode_download = \Illuminate\Support\Str::random(10);
                $session->waktu_mulai = now();
                $session->status_cetak = 'pending';
            }

            $session->link_file_foto = implode(',', $savedPaths);
            $session->waktu_selesai = now();
            $session->save();

            return response()->json([
                'success' => true,
                'message' => 'Semua foto berhasil disimpan ke sesi.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveFrame(Request $request, $Order_id)
    {
        try {  
            $transaction = Transaction::where('order_id', $Order_id)->firstOrFail();
    
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal: Order ID {$Order_id} tidak ditemukan di tabel transactions!"
                ], 404);
            }
    
            $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();
    
            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => "Gagal: Session untuk transaksi ID {$transaction->id} tidak ditemukan!"
                ], 404);
            }
    
            $session->kode_download = $request->image_url;
            $session->save();
    
            if ($request->has('email') && $request->email != null) {
                $transaction->email = $request->email;
                $transaction->save();
            }
    
            $customerEmail = $request->email ?? $transaction->email;
    
            if ($customerEmail) {
                try {
                    $downloadLink = route('photo.view', $Order_id);
    
                    Mail::send('email.photo_link', ['downloadLink' => $downloadLink], function ($message) use ($customerEmail) {
                        $message->to($customerEmail)
                            ->subject('Hasil Foto Photobooth Kamu Sudah Jadi! 📸');
                    });
                } catch (\Exception $mailException) {
                    // Kalau kirim email gagal (misal SMTP Vercel belum disetting), 
                    // biarkan saja agar tidak bikin aplikasi crash. Yang penting DB & Supabase aman!
                }
            }
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error internal di Laravel bray!',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    public function viewPhoto($orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
        $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

        return view('Customer.view_customer', compact('session', 'orderId'));
    }

    public function downloadPhoto($orderId)
    {
        try {
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

            $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

            $path = $session->kode_download;

            if ($path && Storage::disk('s3')->exists($path)) {

                $customFileName = 'Creamo_' . $orderId . '.png';

                return Storage::disk('s3')->download($path, $customFileName);
            }

            return redirect()->back()->withErrors(['error' => 'File foto tidak ditemukan di server.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mendownload: ' . $e->getMessage()]);
        }
    }
}