<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhotoSession;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClearOldPhotos extends Command
{
    // 🔥 Ini perintah yang bakal dipanggil nanti
    protected $signature = 'photos:clear-old';

    // Deskripsi perintah
    protected $description = 'Menghapus folder foto customer yang sudah lebih dari 15 hari';

    public function handle()
    {
        // Tetapkan batas waktu (15 hari yang lalu)
        $batasWaktu = Carbon::now()->subDays(15);

        // Ambil data photo session yang umurnya di bawah batas waktu dan fotonya belum dihapus
        $sesiLama = PhotoSession::where('created_at', '<', $batasWaktu)
            ->whereNotNull('kode_download')
            ->get();

        if ($sesiLama->isEmpty()) {
            $this->info('Mantap! Tidak ada file foto lama yang perlu dihapus saat ini.');
            return Command::SUCCESS;
        }

        $jumlahTerhapus = 0;

        foreach ($sesiLama as $session) {
            // Ambil relasi transaksi untuk mendapatkan order_id
            $transaction = Transaction::find($session->transaction_id);

            if ($transaction) {
                // Sesuai struktur lu: photos/CRM-xxxxxxxxx
                $folderPath = 'photos/' . $transaction->order_id;

                // Cek apakah folder fisiknya ada di storage public
                if (Storage::disk('public')->exists($folderPath)) {
                    // 🔥 Hapus total folder beserta seluruh file foto di dalamnya
                    Storage::disk('public')->deleteDirectory($folderPath);
                    $jumlahTerhapus++;
                }
            }

            // Set kolom kode_download jadi null di database agar sistem tahu fotonya sudah hangus
            $session->kode_download = null;
            $session->save();
        }

        $this->info("Selesai! Berhasil membersihkan {$jumlahTerhapus} folder foto lama dari server.");
        return Command::SUCCESS;
    }
}