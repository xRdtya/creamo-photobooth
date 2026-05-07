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
<body style="background-image: url(/assets/img/bg.png)" class="h-screen bg-no-repeat bg-cover flex justify-center">
    <section style="background-image: url(/assets/img/bg2.png)" class="h-[80vh] m-auto w-11/12 rounded-[50px] shadow-2xl/50 bg-no-repeat bg-cover flex justify-center items-center">
        <div class="w-213.25  bg-[#e5e5e5] rounded-xl shadow-2xl/30 overflow-hidden">
            <div class="bg-linear-to-b from-[#f6f6f6] to-[#d4d4d4] p-2.5 flex items-center border-b border-[#a9a9a9] relative">
                <div class="flex gap-2 z-10 pl-1">
                    <span class="w-3 h-3 rounded-full bg-[#ff5f56] border border-[#e0443e]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#ffbd2e] border border-[#dea123]"></span>
                    <span class="w-3 h-3 rounded-full bg-[#27c93f] border border-[#1aab29]"></span>
                </div>
                <div class="absolute w-full text-center text-[13px] text-[#4d4d4d] font-medium left-0 pointer-events-none">
                    Photo Booth
                </div>
            </div>
            <div class="bg-black relative w-full h-120">
                <video id="videoElement" class="w-full h-full object-cover -scale-x-100" autoplay playsinline></video>
                <canvas id="canvasElement" class="hidden"></canvas>
            </div>
            <div class="bg-[#f0f0f0] p-4 flex justify-center items-center border-t border-gray-300">
                <button 
                    id="snapButton" 
                    class="w-14 h-14 bg-red-500 border-4 border-white rounded-full cursor-pointer ring-2 ring-gray-300 transition-transform duration-100 active:scale-95 active:bg-red-700 hover:bg-red-600"
                    title="Ambil Foto">
                </button>
            </div>
        </div> 
    </section>
    <script>
        const video = document.getElementById('videoElement');
        const canvas = document.getElementById('canvasElement');
        const snapBtn = document.getElementById('snapButton');

        navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: { ideal: 1920 },
                height: { ideal: 1080 }
            } 
        })
            .then(function(stream) {
                video.srcObject = stream;
            }).catch(function(err) {
                alert("Ups, akses kamera gagal!", err);
            });

        snapBtn.addEventListener('click', function() {
            canvas.width = 1920;
            canvas.height = 1080;
            
            const context = canvas.getContext('2d');
            
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            
            context.drawImage(video, 0, 0, 1920, 1080);

            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = 'hasil-photobooth-1080p.png';
            link.href = dataUrl;
            link.click();
        });
    </script>
</body>
</html>