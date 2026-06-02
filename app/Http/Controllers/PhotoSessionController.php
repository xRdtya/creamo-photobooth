<?php

namespace App\Http\Controllers;

use App\Models\PhotoSession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class PhotoSessionController extends Controller
{
    public function index(Request $request, $orderId) 
    {
        $order = Transaction::where('order_id', $orderId)->firstOrFail();

        $session = PhotoSession::where('transaction_id', $order->id)->firstOrFail();

        $photos = explode(',', $session->link_file_foto);

        $frames = [
            ['id' => 1, 'name' => 'Classic White', 'image' => '7.png', 'color' => '#FFFFFF'],
            ['id' => 2, 'name' => 'Retro Film', 'image' => '8.png', 'color' => '#1A1A1A'],
            ['id' => 3, 'name' => 'Soft Pastel', 'image' => '9.png', 'color' => '#FFD1DC'],
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

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            foreach ($photos as $index => $base64Data) {
                $img = str_replace('data:image/png;base64,', '', $base64Data);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);

                $fileName = "photo_" . ($index + 1) . "_" . time() . ".png";
                $fullPath = $folderPath . "/" . $fileName;

                Storage::disk('public')->put($fullPath, $data);

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
        $request->validate([
            'selected_frame' => 'required',
            'email'          => 'required|email',
        ]);

        try {
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

            $session->email = $request->email;

            $manager = new ImageManager(new Driver());

            $frameName = $request->selected_frame;
            if (!str_contains($frameName, '.png')) {
                $frameName .= '.png';
            }
            $framePath = public_path('assets/img/frames/' . $frameName);

            if (!file_exists($framePath)) {
                return back()->withErrors(['error' => 'File frame tidak ditemukan di: ' . $framePath]);
            }

            $frameImage = $manager->read($framePath);
            $frameWidth = $frameImage->width();
            $frameHeight = $frameImage->height();

            $canvas = $manager->create($frameWidth, $frameHeight)->fill('ffffff');

            $userPhotos = explode(',', $session->link_file_foto);

            $photoWidth  = 864;  // Contoh lebar lubang foto (sesuaikan pixel asli)
            $photoHeight = 580;  // Contoh tinggi lubang foto (sesuaikan pixel asli)
            $xOffset     = 108;  // Jarak dari kiri frame ke lubang foto

            $yOffsets = [
                180,  // Batas atas kotak ke-1
                820,  // Batas atas kotak ke-2
                1460  // Batas atas kotak ke-3
            ];

            foreach ($userPhotos as $index => $photoPath) {
                if (isset($yOffsets[$index])) {
                    $fullPath = storage_path('app/public/' . $photoPath);

                    if (file_exists($fullPath)) {
                        $photo = $manager->read($fullPath);

                        $photo->cover($photoWidth, $photoHeight);

                        $canvas->place($photo, 'top-left', $xOffset, $yOffsets[$index]);
                    }
                }
            }

            $canvas->place($frameImage, 'top-left', 0, 0);

            $finalFileName = 'final_' . time() . '.png';
            $finalFolder = 'photos/' . $orderId;
            $finalFullPath = $finalFolder . '/' . $finalFileName;

            if (!Storage::disk('public')->exists($finalFolder)) {
                Storage::disk('public')->makeDirectory($finalFolder);
            }

            Storage::disk('public')->put($finalFullPath, $canvas->toPng());

            $session->link_hasil_final = $finalFullPath;
            $session->save();
            dd('ERROR');
            return redirect()->route('photo.success', $orderId)->with('success', 'Foto berhasil digabungkan!');
        } catch (\Exception $e) {
            dd('ERROR');
            return back()->withErrors(['error' => 'Gagal memproses gambar: ' . $e->getMessage()]);
        }
    }
}