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

                $fileName = "photo_" . ($index + 1) . "_" . time() . ".png";
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

    public function saveFrame(Request $request, $orderId)
    {
        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
        $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

        $base64Image = $request->input('final_photo');

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);

            $extension = strtolower($type[1]);

            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                return redirect()->back()->with('error', 'Gagal memproses data gambar.');
            }
        } else {
            return redirect()->back()->with('error', 'Data gambar tidak ditemukan.');
        }
        $fileName = "final_" . $orderId . "_" . time() . '.' . $extension;

        $filePath = 'photos/' . $orderId . '/' . $fileName;

        $transaction->email = $request->email;
        $transaction->save();

        // $session->email = $request->email;
        $session->kode_download = $filePath;
        $session->save();

        $downloadLink = route('photo.view', $orderId);

        $customerEmail = $request->email;
        Mail::send('email.photo_link', ['downloadLink' => $downloadLink], function ($message) use ($customerEmail) {
            $message->to($customerEmail)
                ->subject('Hasil Foto Photobooth Kamu Sudah Jadi! 📸');
        });

        unset($imageData);
        unset($base64Image);

        return response('
            <script>
                window.location.href = "/halaman-tujuan";
            </script>
        ');
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