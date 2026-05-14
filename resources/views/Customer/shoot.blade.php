<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>CREAMO | Create a Moment</title>
    <link rel="shortcut icon" href="/assets/img/logo.svg" type="image/x-icon">
</head>
<body style="background-image: url(/assets/img/bg.png)" class="h-screen bg-no-repeat bg-cover flex justify-center">
    <section style="background-image: url(/assets/img/bg2.png)" class="h-[80vh] m-auto w-11/12 rounded-[50px] shadow-2xl/50 bg-no-repeat bg-cover flex justify-center items-center">
        <div class="w-213.25 bg-[#e5e5e5] rounded-xl shadow-2xl/30 overflow-hidden relative">
            
            <!-- HEADER BAR -->
            <div class="bg-linear-to-b from-[#f6f6f6] to-[#d4d4d4] p-2.5 flex items-center border-b border-[#a9a9a9] relative">
                <div class="flex gap-2 z-10 pl-1">
                    <span class="w-3 h-3 rounded-full bg-[#ff5f56] border border-[#e0443e]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#ffbd2e] border border-[#dea123]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#27c93f] border border-[#1aab29]"></span>
                </div>
                
                <div class="absolute w-full text-center pointer-events-none flex justify-center items-center gap-4 left-0">
                    <span class="text-[13px] text-[#4d4d4d] font-bold uppercase tracking-widest">Photo Booth</span>
                    <div id="statusUI" class="hidden flex gap-3 ml-4 bg-black/10 px-3 py-0.5 rounded-full border border-gray-400">
                        <span id="timerDisplay" class="text-red-600 font-black text-sm">3</span>
                        <span class="text-gray-400">|</span>
                        <span class="text-[#4d4d4d] font-bold text-sm"><span id="counterDisplay">0</span>/3</span>
                    </div>
                </div>
            </div>

            <!-- AREA KAMERA & PREVIEW -->
            <div class="bg-black relative w-full h-120 overflow-hidden">
                <!-- Video stream -->
                <video id="videoElement" class="w-full h-full object-cover -scale-x-100" autoplay playsinline></video>
                
                <!-- Canvas tersembunyi untuk proses capture -->
                <canvas id="canvasElement" class="hidden"></canvas>
                
                <!-- Hasil Foto (Preview) -->
                <div id="previewContainer" class="absolute inset-0 z-20 hidden bg-black flex flex-col items-center justify-center">
                    <img id="previewImage" class="w-full h-full object-cover">
                    <div class="absolute bottom-10 flex gap-6">
                        <button id="retakeBtn" class="bg-red-600 text-white px-8 py-3 rounded-full font-bold shadow-2xl hover:bg-red-700 transition-all uppercase tracking-wider">
                            Retake
                        </button>
                        <button id="nextBtn" class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-2xl hover:bg-blue-700 transition-all uppercase tracking-wider">
                            Next
                        </button>
                    </div>
                </div>
                
                <!-- Efek Flash -->
                <div id="flashEffect" class="absolute inset-0 bg-white opacity-0 pointer-events-none transition-opacity duration-100 z-30"></div>
            </div>

            <!-- FOOTER / TOMBOL SNAP -->
            <div class="bg-[#f0f0f0] p-4 flex justify-center items-center border-t border-gray-300">
                <button id="snapButton" class="w-14 h-14 bg-red-500 border-4 border-white rounded-full cursor-pointer ring-2 ring-gray-300 transition-transform duration-100 active:scale-95 active:bg-red-700 hover:bg-red-600 shadow-lg">
                </button>
            </div>
        </div> 
    </section>

    <!-- Form Hidden untuk Redirect POST -->
    <form id="redirectForm" action="/photo/select-frame/{{ $order->order_id }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->order_id }}">
        <!-- Di sini nanti kamu bisa sisipkan data foto jika sudah di-upload ke server -->
    </form>

    <script>
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvasElement');
        const snapBtn = document.getElementById('snapButton');
        const timerDisplay = document.getElementById('timerDisplay');
        const counterDisplay = document.getElementById('counterDisplay');
        const statusUI = document.getElementById('statusUI');
        const flash = document.getElementById('flashEffect');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const retakeBtn = document.getElementById('retakeBtn');
        const nextBtn = document.getElementById('nextBtn');

        let currentImageData = null;
        let photoCount = 0;
        const maxPhotos = 3;
        let timeLeft = 3;
        let timerInterval;

        const orderId = "{{ $order->order_id }}";

        // 1. Akses Kamera
        navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 1920 }, height: { ideal: 1080 } }
        }).then(stream => {
            video.srcObject = stream;
        }).catch(err => {
            alert("Kamera tidak terdeteksi!");
        });

        // 2. Fungsi Jalankan Timer
        function startTimer() {
            timeLeft = 3;
            timerDisplay.innerText = timeLeft;
            
            timerInterval = setInterval(() => {
                timeLeft--;
                timerDisplay.innerText = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    takeSnap();
                }
            }, 1000);
        }

        // 3. Fungsi Ambil Foto
        function takeSnap() {
            canvas.width = 1920;
            canvas.height = 1080;
            const context = canvas.getContext('2d');
            
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, 1920, 1080);

            // Pastikan baris ini ada untuk mengisi variabel global tadi
            currentImageData = canvas.toDataURL('image/png'); 

            flash.classList.replace('opacity-0', 'opacity-100');
            setTimeout(() => flash.classList.replace('opacity-100', 'opacity-0'), 100);

            previewImage.src = currentImageData;
            previewContainer.classList.remove('hidden');
        }

        // 4. Event Tombol Utama (Snap)
        snapBtn.addEventListener('click', function() {
            this.parentElement.classList.add('hidden'); // Sembunyikan footer
            statusUI.classList.remove('hidden'); // Munculkan status counter
            startTimer();
        });

        // 5. Event Tombol Next
        nextBtn.addEventListener('click', async function() {
            if (!currentImageData) return;

            nextBtn.innerText = "SAVING...";
            nextBtn.disabled = true;

            // Pastikan photoCount + 1 dikirim sebagai index (1, 2, atau 3)
            const isSaved = await savePhotoToServer(currentImageData);

            if (isSaved) {
                console.log("Berhasil simpan foto ke-" + (photoCount + 1));
                
                previewContainer.classList.add('hidden');
                nextBtn.innerText = "Next";
                nextBtn.disabled = false;

                photoCount++;
                counterDisplay.innerText = photoCount;

                if (photoCount < maxPhotos) {
                    startTimer();
                } else {
                    // Jika sudah 3 foto, form submit
                    document.getElementById('redirectForm').submit();
                }
            } else {
                // Jika isSaved false, tombol balik lagi
                nextBtn.innerText = "Next";
                nextBtn.disabled = false;
                alert("Gagal menyimpan, silakan coba lagi.");
            }
        });

        // 6. Event Tombol Retake
        retakeBtn.addEventListener('click', function() {
            previewContainer.classList.add('hidden');
            // Jangan tambah photoCount, langsung ulang timer
            startTimer();
        });

        async function savePhotoToServer(imageData) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch('/photo/upload', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        image: imageData,
                        order_id: orderId,
                        photo_index: photoCount + 1
                    })
                });

                const result = await response.json();
                
                // SANGAT PENTING: Pastikan mengembalikan nilai boolean
                return result.success === true; 

            } catch (error) {
                console.error('Fetch Error:', error);
                return false;
            }
        }
    </script>
</body>
</html>