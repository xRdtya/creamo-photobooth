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
            <div id="payment" class="grid grid-rows-2 h-full">
                <div class="self-center">
                    <img src="/assets/img/logocreamo.png" alt="Logo">
                    <p class="font-montserrat text-queaternary text-xl">Create a moment</p>
                </div>
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 50 50">
                        <path d="M 25 2 C 12.264481 2 2 12.264481 2 25 C 2 37.735519 12.264481 48 25 48 C 37.735519 48 48 37.735519 48 25 C 48 12.264481 37.735519 2 25 2 z M 25 4 C 36.664481 4 46 13.335519 46 25 C 46 36.664481 36.664481 46 25 46 C 13.335519 46 4 36.664481 4 25 C 4 13.335519 13.335519 4 25 4 z M 25 11 A 3 3 0 0 0 25 17 A 3 3 0 0 0 25 11 z M 21 21 L 21 23 L 23 23 L 23 36 L 21 36 L 21 38 L 29 38 L 29 36 L 27 36 L 27 21 L 21 21 z"></path>
                    </svg>
                    <div>
                        <h3 class="font-montserrat text-2xl text-queaternary font-bold">Pembayaran melalui QR Code</h3>
                        <p class="font-montserrat text-lg font-light text-queaternary">Selesaikan pembayaran untuk lanjut ke sesi foto</p>
                    </div>
                </div>
            </div>
            <div id="success" class="grid grid-rows-3 h-full hidden">
                <div class="grid grid-cols-2 items-center">
                    <div class="">
                        <img src="/assets/img/logocreamo.png" alt="Logo">
                        <p class="font-montserrat text-queaternary text-xl">Create a moment</p>
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <p class="font-montserrat text-queaternary text-4xl font-medium">Pembayaran Sukses</p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="bg-secondary text-queaternary font-bold text-6xl py-5 px-18 rounded-4xl shadow-2xl/30">Take a Photo</p>
                </div>
            </div>
            <img id="qr" class="justify-self-center ml-50" src="{{ $qr_url }}" alt="" width="350">
        </section>
        <script>
            setInterval(() => {
                fetch('/photo/status/{{ $orderId }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.payment_status === 'success') {
                            document.getElementById("qr").classList.add("hidden");
                            document.getElementById("payment").classList.add("hidden");
                            document.getElementById("success").classList.remove("hidden");
                        } 
                    });
            }, 3000);
        </script>
    </body>
</html>