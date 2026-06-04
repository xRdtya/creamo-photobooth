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
<body style="background-image: url(/assets/img/bg.png)" class="relative h-screen bg-no-repeat bg-cover flex justify-center items-center">
    <section style="background-image: url(/assets/img/bg2.png)" class="relative w-11/12 h-11/12 max-w-4xl backdrop-blur-md rounded-[2.5rem] shadow-2xl p-10 pb-20 border border-white/60">
        <div class="absolute top-8 left-10">
            <img src="/assets/img/logocreamo.png" class="w-[40%]" alt="Logo">
            <p class="font-montserrat text-queaternary text-xl">Create a moment</p>
        </div>
        <h2 class="text-center text-2xl font-bold text-blue-900 mt-12 mb-10">Pilih Bingkai Foto Favoritmu</h2>
        <div class="flex items-center justify-center gap-12 h-[80%]">
            <button id="btnPrev" type="button" class="transition-transform hover:-translate-x-2 active:scale-95 drop-shadow-lg">
                <svg class="w-16 h-16 text-blue-900" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4L4 12L20 20Z" />
                </svg>
            </button>
            <div class="relative aspect-1/3 rounded-lg overflow-hidden h-full">
                <img id="displayFrame" src="{{ asset('assets/img/frames/' . $frames[0]['image']) }}" class="absolute inset-0 w-full h-full z-20 object-cover pointer-events-none">
                <div class="absolute inset-0 z-10">
                    <div class="absolute left-1/2 -translate-x-1/2 top-[8%] w-[80%] overflow-hidden">
                        <img src="https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[0] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[26.7%] w-[80%] overflow-hidden">
                        <img src="https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[1] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[45.5%] w-[80%] overflow-hidden">
                        <img src="https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[2] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[64%] w-[80%] overflow-hidden">
                        <img src="https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[3] }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <button id="btnNext" type="button" class="transition-transform hover:translate-x-2 active:scale-95 drop-shadow-lg">
                <svg class="w-16 h-16 text-blue-900" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 4L20 12L4 20Z" />
                </svg>
            </button>
        </div>
        <div class="absolute bottom-10 left-10">
            <form method="POST" action="/photo/shoot/{{ $order->order_id }}" class="flex items-center justify-center w-14 h-14 border-[3px] border-blue-900 text-blue-900 rounded-full hover:bg-blue-900 hover:text-white transition-colors shadow-md">
                @csrf
                <button type="submit">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </form>
        </div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <form id="form-submit" action="/photo/save-frame/{{ $order->order_id }}" method="post">
            @csrf
                <input type="hidden" name="selected_frame" id="selectedFrameId" value="{{ $frames[0]['id'] }}">
                <input class="hidden" type="email" name="email" id="email" value="noufalraditya068@gmail.com">
                <input type="hidden" id="input-hidden-final-photo" name="final_photo">
                <button id="btn-simpan" name="tombol_simpan" type="button" class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full hover:scale-110 transition-transform shadow-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        const frames = @json($frames);
        let currentIndex = 0;
        const { createClient } = supabase; 
        const supabaseClient = createClient('https://ywrswuyjuvgrnfmugxwm.supabase.co', 'sb_publishable_dCTMWJdBw9MYQrIonQmjtA_3jJYiiqz');

        const displayFrame = document.getElementById('displayFrame');
        const selectedFrameIdInput = document.getElementById('selectedFrameId');
        const btnNext = document.getElementById('btnNext');
        const btnPrev = document.getElementById('btnPrev');

        function updateFrame(index) {
            displayFrame.src = `/assets/img/frames/${frames[index].image}`;
            selectedFrameIdInput.value = frames[index].id;
        }

        btnNext.addEventListener('click', () => {
            currentIndex++;
            if (currentIndex >= frames.length) {
                currentIndex = 0;
            }
            updateFrame(currentIndex);
        });

        btnPrev.addEventListener('click', () => {
            currentIndex--;
            if (currentIndex < 0) {
                currentIndex = frames.length - 1;
            }
            updateFrame(currentIndex);
        });

        document.getElementById('btn-simpan').addEventListener('click', async function() {
            const arrayFotoUser = [
                "https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[0] }}",
                "https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[1] }}",
                "https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[2] }}",
                "https://ywrswuyjuvgrnfmugxwm.supabase.co/storage/v1/object/public/photos/{{ $photos[3] }}"
            ];
            const linkFrame = `/assets/img/frames/${selectedFrameIdInput.value}.png`;

            const hasilGabungan = await generateFinalPhoto(arrayFotoUser, linkFrame);

            if (hasilGabungan) {
                document.getElementById('input-hidden-final-photo').value = hasilGabungan;
                await uploadKeSupabase(hasilGabungan, '{{ $order->order_id }}');
            }
        });

        async function uploadKeSupabase(base64Data, orderId) {
            try {
                const namaFile = `/photos/${orderId}/final_${Date.now()}.jpg`;
                const responseGambar = await fetch(base64Data);
                const blobGambar = await responseGambar.blob();

                const { data, error } = await supabaseClient
                    .storage
                    .from('photos')
                    .upload(namaFile, blobGambar, {
                        contentType: 'image/jpeg'
                    });

                if (error) {
                    console.error("Gagal upload ke Supabase:", error);
                    alert("Gagal upload: " + error.message);
                } else {
                    const { data: publicUrlData } = supabaseClient
                        .storage
                        .from('photos')
                        .getPublicUrl(`photos/${orderId}/final_${Date.now()}.jpg`);
                    
                    const fileUrl = publicUrlData.publicUrl;
                    const emailPelanggan = document.getElementById('email').value;

                    const responseLaravel = await fetch(`/photo/save-frame/${orderId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            image_url: fileUrl,
                            email: emailPelanggan
                        })
                    });

                    if (responseLaravel.ok) {
                        alert("Data berhasil dicatat di database dan Supabase!");
                        window.location.href = "/photo"; 
                    } else {
                        alert("File masuk Supabase, tapi gagal dicatat di database lokal.");
                    } 
                }
            } catch (err) {
                console.error("Error internal JS:", err);
                alert("Terjadi kesalahan: " + err.message); 
            }
        }

        function loadImage(src) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                img.onload = () => resolve(img);
                img.onerror = (e) => reject(new Error('Gagal meload gambar: ' + src));
                img.src = src;
            });
        }

        async function generateFinalPhoto(photoUrls, frameUrl) {
            try {
                const frameImg = await loadImage(frameUrl);
                const photos = await Promise.all(photoUrls.map(url => loadImage(url)));

                const canvas = document.createElement('canvas');
                canvas.width = frameImg.width;
                canvas.height = frameImg.height;
                const ctx = canvas.getContext('2d');

                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                const widthPercent = 0.80;
                const heightPercent = 0.175;
                const topPercentages = [0.08, 0.267, 0.455, 0.64];

                const photoWidth = Math.floor(canvas.width * widthPercent);
                const photoHeight = Math.floor(canvas.height * heightPercent);
                const xOffset = Math.floor((canvas.width - photoWidth) / 2);

                photos.forEach((photo, index) => {
                    if (topPercentages[index]) {
                        const yOffset = Math.floor(canvas.height * topPercentages[index]);
                        
                        const srcRatio = photo.width / photo.height;
                        const dstRatio = photoWidth / photoHeight;
                        let sWidth = photo.width, sHeight = photo.height, sx = 0, sy = 0;

                        if (srcRatio > dstRatio) {
                            sWidth = photo.height * dstRatio;
                            sx = (photo.width - sWidth) / 2;
                        } else {
                            sHeight = photo.width / dstRatio;
                            sy = (photo.height - sHeight) / 2;
                        }

                        ctx.drawImage(photo, sx, sy, sWidth, sHeight, xOffset, yOffset, photoWidth, photoHeight);
                    }
                });

                ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);

                const finalBase64 = canvas.toDataURL('image/png');
                return finalBase64;

            } catch (error) {
                console.error(error);
                alert("Gagal memproses gambar!");
                return null;
            }
        }
    </script>
</body>
</html>