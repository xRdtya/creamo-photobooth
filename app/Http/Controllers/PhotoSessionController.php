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
                // FIX: Pakai REGEX biar semua format header (png, jpeg, jpg) otomatis terhapus dengan bersih!
                $img = preg_replace('#^data:image/\w+;base64,#i', '', $base64Data);
                $img = str_replace(' ', '+', $img);

                $data = base64_decode($img);

                // Validasi tambahan: Cek apakah hasil decode menghasilkan data kosong
                if ($data === false) {
                    return response()->json(['success' => false, 'message' => 'Gagal membaca data foto ke-' . ($index + 1)], 500);
                }

                $fileName = "photo_" . ($index + 1) . "_" . time() . ".png";
                $fullPath = $folderPath . "/" . $fileName;

                // Upload ke Supabase S3
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
        $request->validate([
            'selected_frame' => 'required',
            'email'          => 'required|email',
        ]);

        try {
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

            $frameName = $request->selected_frame;
            $framePath = public_path('/assets/img/frames/' . $frameName . '.png');

            if (!file_exists($framePath)) {
                return redirect('/photo')->withErrors(['error' => 'File frame tidak ditemukan']);
            }

            $frameImg = imagecreatefrompng($framePath);
            $frameWidth = imagesx($frameImg);
            $frameHeight = imagesy($frameImg);

            $canvas = imagecreatetruecolor($frameWidth, $frameHeight);
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $white);

            imagealphablending($canvas, true);
            imagesavealpha($canvas, true);

            $widthPercent  = 0.80;
            $heightPercent = 0.175;

            $topPercentages = [0.08, 0.267, 0.455, 0.64];

            $photoWidth  = (int)($frameWidth * $widthPercent);
            $photoHeight = (int)($frameHeight * $heightPercent);
            $xOffset     = (int)(($frameWidth - $photoWidth) / 2);

            $yOffsets = [];
            foreach ($topPercentages as $pct) {
                $yOffsets[] = (int)($frameHeight * $pct);
            }

            $userPhotos = explode(',', $session->link_file_foto);

            foreach ($userPhotos as $index => $photoPath) {
                if (isset($yOffsets[$index]) && !empty(trim($photoPath))) {

                    $cleanedPath = trim(str_replace(['public/', 'storage/'], '', $photoPath));
                    $fullPath = Storage::disk('public')->path($cleanedPath);

                    if (!file_exists($fullPath)) {
                        return redirect('/photo')->withErrors([
                            'error' => 'Foto ke-' . ($index + 1) . ' tidak ada di: ' . $fullPath
                        ]);
                    }

                    $info = getimagesize($fullPath);
                    $srcImg = null;

                    if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg') {
                        $srcImg = imagecreatefromjpeg($fullPath);
                    } elseif ($info['mime'] == 'image/png') {
                        $srcImg = imagecreatefrompng($fullPath);
                    }

                    if ($srcImg) {
                        $srcW = imagesx($srcImg);
                        $srcH = imagesy($srcImg);

                        $srcRatio = $srcW / $srcH;
                        $dstRatio = $photoWidth / $photoHeight;

                        if ($srcRatio > $dstRatio) {
                            $cropH = $srcH;
                            $cropW = (int)($srcH * $dstRatio);
                            $srcX = (int)(($srcW - $cropW) / 2);
                            $srcY = 0;
                        } else {
                            $cropW = $srcW;
                            $cropH = (int)($srcW / $dstRatio);
                            $srcX = 0;
                            $srcY = (int)(($srcH - $cropH) / 2);
                        }

                        imagecopyresampled(
                            $canvas,
                            $srcImg,
                            $xOffset,
                            $yOffsets[$index],
                            $srcX,
                            $srcY,
                            $photoWidth,
                            $photoHeight,
                            $cropW,
                            $cropH
                        );
                        imagedestroy($srcImg);
                    }
                }
            }

            imagecopy($canvas, $frameImg, 0, 0, 0, 0, $frameWidth, $frameHeight);
            imagedestroy($frameImg);

            ob_start();
            imagepng($canvas);
            $encodedImage = ob_get_clean();
            imagedestroy($canvas);

            $finalFileName = 'final_' . time() . '.png';
            $finalFolder = 'photos/' . $orderId;
            $finalFullPath = $finalFolder . '/' . $finalFileName;

            if (!Storage::disk('public')->exists($finalFolder)) {
                Storage::disk('public')->makeDirectory($finalFolder);
            }

            Storage::disk('public')->put($finalFullPath, $encodedImage);


            $transaction->email = $request->email;
            $transaction->save();

            // $session->email = $request->email;
            $session->kode_download = $finalFullPath;
            $session->save();

            $downloadLink = route('photo.view', $orderId);

            $customerEmail = $request->email;
            Mail::send('email.photo_link', ['downloadLink' => $downloadLink], function ($message) use ($customerEmail) {
                $message->to($customerEmail)
                    ->subject('Hasil Foto Photobooth Kamu Sudah Jadi! 📸');
            });

            return redirect()->route('photo')->with('success', 'Foto berhasil digabungkan!');
        } catch (\Exception $e) {
            dd($e);
            return redirect('/photo')->withErrors(['error' => 'Gagal menggabungkan: ' . $e->getMessage()]);
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