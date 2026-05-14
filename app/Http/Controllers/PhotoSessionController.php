<?php

namespace App\Http\Controllers;

use App\Models\PhotoSession;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoSessionController extends Controller
{
    public function index(Request $request, $orderId) 
    {
        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

        $session = PhotoSession::where('transaction_id', $transaction->id)->firstOrFail();

        $photos = explode(',', $session->link_file_foto);

        $frames = [
            ['id' => 1, 'name' => 'Classic White', 'image' => '7.png', 'color' => '#FFFFFF'],
            ['id' => 2, 'name' => 'Retro Film', 'image' => '8.png', 'color' => '#1A1A1A'],
            ['id' => 3, 'name' => 'Soft Pastel', 'image' => '9.png', 'color' => '#FFD1DC'],
        ];
    
        return view('Customer.frame', compact('frames', 'photos', 'orderId'));
    }

    public function upload(Request $request)
    {
        try {
            $img = $request->image;
            $orderId = $request->order_id;
            $index = $request->photo_index;

            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);

            $fileName = "photo_" . $index . "_" . time() . ".png";
            $fullPath = "photos/" . $orderId . "/" . $fileName;
            Storage::disk('public')->put($fullPath, $data);

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

            $session = PhotoSession::firstOrNew(['transaction_id' => $transaction->id]);

            if (!$session->exists) {
                $session->email = $transaction->email;
                $session->kode_download = Str::random(10);
                $session->waktu_mulai = now();
                $session->status_cetak = 'pending';
            }

            $currentPhotos = $session->link_file_foto ? explode(',', $session->link_file_foto) : [];

            $currentPhotos[$index - 1] = $fullPath;

            $session->link_file_foto = implode(',', $currentPhotos);

            if ($index == 3) {
                $session->waktu_selesai = now();
            }

            $session->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto ke-' . $index . ' berhasil ditambahkan ke sesi.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}