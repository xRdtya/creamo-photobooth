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
                        <img src="{{ asset('storage/' . $photos[0]) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[26.7%] w-[80%] overflow-hidden">
                        <img src="{{ asset('storage/' . $photos[1]) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[45.5%] w-[80%] overflow-hidden">
                        <img src="{{ asset('storage/' . $photos[2]) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute left-1/2 -translate-x-1/2 top-[64%] w-[80%] overflow-hidden">
                        <img src="{{ asset('storage/' . $photos[3]) }}" class="w-full h-full object-cover">
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
            <form method="POST" action="/photo/save-frame/{{ $order->order_id }}">
                @csrf
                <input type="hidden" name="selected_frame" id="selectedFrameId" value="{{ $frames[0]['id'] }}">
                
                <button type="submit" class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full hover:scale-110 transition-transform shadow-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            </form>
        </div>
    </section>
    <script>
        const frames = @json($frames);
        let currentIndex = 0;

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
    </script>
</body>
</html>