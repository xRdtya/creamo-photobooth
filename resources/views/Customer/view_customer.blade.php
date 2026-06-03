<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>CREAMO | Create a Moment</title>
    <link rel="shortcut icon" href="/assets/img/logo.svg" type="image/x-icon">
</head>
    <body style="background-image: url(/assets/img/bg.png)" class="min-h-screen bg-no-repeat bg-cover flex flex-col items-center justify-center p-4">
        
        <section style="background-image: url(/assets/img/bg2.png)" 
                 class="min-h-[80vh] py-8 px-6 my-auto w-full max-w-md rounded-[40px] md:rounded-[50px] shadow-2xl bg-no-repeat bg-cover flex flex-col justify-center items-center text-center">
            
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Foto Kamu Sudah Siap! ✨</h1>
            <p class="text-gray-500 text-xs md:text-sm mb-6">Order ID: #{{ $orderId }}</p>

            <div class="mb-6 border-4 border-white rounded-2xl overflow-hidden shadow-lg max-w-[200px] md:max-w-[220px] w-full transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('storage/' . $session->kode_download) }}" alt="Hasil Foto Photobooth" class="w-full h-auto block">
            </div>

            <div class="w-full max-w-[280px] mb-3">
                <a href="{{ route('photo.download', $orderId) }}" 
                   class="flex items-center justify-center gap-2 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-md transition duration-200 text-base md:text-lg">
                    📥 Download Ke HP / Perangkat
                </a>
            </div>
            
            <p class="text-[11px] md:text-xs text-gray-400 max-w-[280px] leading-relaxed">
                Tips: Jika download tidak berjalan otomatis, tahan lama pada gambar lalu pilih "Simpan Gambar".
            </p>
        </section>
    </body>
</html>