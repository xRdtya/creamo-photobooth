<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>CREAMO | Create a Moment</title>
    <link rel="shortcut icon" href="assets/img/logo.svg" type="image/x-icon">
</head>
    <body>
        <nav class="flex justify-center fixed w-full z-40">
            <ul class="flex flex-row justify-around bg-tertiary/40 mt-5 w-11/12 h-24 shadow-2xl/40 bg-clip-padding backdrop-filter backdrop-blur-xs rounded-full border border-gray-300">
                <li class="grid content-center">
                    <a href="#"><img class="w-56" src="assets/img/logocreamo.png" alt="Logo"></a>
                </li>
                <li class="grid content-center">
                    <a class="font-montserrat font-bold text-lg text-queaternary" href="#">About</a>
                </li>
                <li class="grid content-center">
                    <a class="font-montserrat font-bold text-lg text-queaternary" href="#">Pricing</a>
                </li>
                <li class="grid content-center my-4 w-32 bg-queaternary text-secondary rounded-full hover:bg-queaternary/80">
                    <a class="text-center text-lg font-montserrat font-medium" href="#">Login</a>
                </li>
            </ul>
        </nav>
        <section style="background-image: url(/assets/img/bg.png)" class="h-screen bg-no-repeat bg-cover flex justify-center" id="homepage">
            <div class="grid content-center h-full w-11/12">
                <div class="h-[80vh] mt-30 rounded-[50px] shadow-2xl/50 bg-no-repeat bg-cover" style="background-image: url(/assets/img/bg2.png)">
                    <div class="h-full grid content-center">
                        <div class="flex justify-around">
                            <div class="col-span-2 content-center">
                                <h1 class="text-queaternary font-montserrat font-extrabold text-9xl">CREAMO</h1>
                                <p class="text-queaternary text-5xl">Create A Moment</p>
                            </div>
                            <div class="col-span-2">
                                <img class="fill-queaternary" src="/assets/img/logo-queaternary.svg" alt="LOGO">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="h-full bg-tertiary flex flex-col justify-center items-center relative" id="about">
            <div class="grid content-center h-full w-11/12">
                <div class="h-screen my-30 rounded-[50px] z-20 border border-(--glass-border) shadow-2xl/20 inset-shadow-sm inset-shadow-white/20 backdrop-blur-sm bg-(--glass-bg)">
                    <div class="h-full items-center grid grid-cols-2 mx-17.5">
                        <div class="grid-cols-1 h-full content-center">
                            <p class="bg-white/20 w-32.5 rounded-4xl text-center py-2.5 shadow-lg border border-gray-300 font-montserrat font-light text-lg text-queaternary mb-10">About Us</p>
                            <h2 class="font-montserrat text-6xl font-bold text-queaternary my-5">Photobooth Digital <br>untuk Setiap Momen <br>Berhargamu</h2>
                            <p class="font-montserrat text-queaternary font-light text-lg">
                                Creamo adalah platform Software as a Service (SaaS) inovatif yang membawa pengalaman photobooth ke level berikutnya. Kami menyediakan solusi dokumentasi digital yang praktis, interaktif, dan mudah diakses. Dengan Creamo, setiap perayaan mulai dari festival, bazaar, hingga acara kampus dapat menghadirkan keseruan berfoto tanpa perlu menyewa bilik atau mesin cetak tradisional yang memakan banyak tempat.
                                <br>
                                <br>
                                Misi kami adalah memudahkan semua orang dalam mengabadikan kebahagiaan. Melalui integrasi teknologi yang seamless, mulai dari kemudahan transaksi hingga kustomisasi bingkai foto yang menarik, Creamo memastikan pengalaman menyimpan kenangan menjadi lebih cepat, modern, dan pastinya tak terlupakan.
                            </p>
                        </div>
                        <div class="grid-cols-1 flex justify-center relative">
                            <div class="bg-tertiary shadow-2xl/50 h-[70vh] w-[50vh] rounded-4xl">
                                <img class="absolute left-70 z-20" src="assets/img/7.png" alt="Foto">
                                <img class="absolute left-30 top-20 z-10" src="assets/img/9.png" alt="Foto">
                                <img class="absolute left-95 top-30 z-30" src="assets/img/8.png" alt="Foto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <svg class="absolute bottom-0 z-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="var(--color-secondary)" fill-opacity="1" d="M0,32L48,69.3C96,107,192,181,288,197.3C384,213,480,171,576,165.3C672,160,768,192,864,224C960,256,1056,288,1152,261.3C1248,235,1344,149,1392,106.7L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
        </section>
        <section class="h-full bg-secondary flex flex-col justify-center items-center relative" id="subs">
            <div class="grid content-center h-full w-11/12">
                <div class="h-screen my-30 rounded-[50px] z-20 border border-(--glass-border) shadow-2xl/20 inset-shadow-sm inset-shadow-white/20 backdrop-blur-sm bg-(--glass-bg)">
                    <div class="h-full grid grid-rows-3 mx-17.5">
                        <div class="grid grid-cols-2 h-full items-center">
                            <h2 class="font-montserrat text-6xl font-medium text-queaternary my-5">Enjoy the finest features with our products</h2>
                            <p class="text-queaternary text-end font-medium text-lg">We provide all the advantages that can simplify all your <br>financial transactions without any further requirements</p>
                        </div>
                        <div class="grid grid-cols-3 items-center justify-items-center">
                            <div class="bg-tertiary w-112.5 h-87.5 rounded-3xl shadow-2xl flex flex-col justify-center px-7.5">
                                <svg class="bg-secondary w-15 rounded-full p-2 text-end" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                                </svg>
                                <h2 class="text-queaternary text-4xl font-montserrat font-bold my-5">Easy Payment</h2>
                                <p class="font-montserrat font-light text-lg">Kemudahan bertransaksi menggunakan QRIS dan berbagai pilihan E-Wallet terpopuler langsung dari genggaman Anda.</p>
                            </div>
                            <div class="bg-tertiary w-112.5 h-87.5 rounded-3xl shadow-2xl flex flex-col justify-center px-7.5">
                                <svg class="bg-secondary w-15 rounded-full p-2 text-end" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <h2 class="text-queaternary text-4xl font-montserrat font-bold my-5">Safe Transaction</h2>
                                <p class="font-montserrat font-light text-lg">Sistem pembayaran terintegrasi yang dienkripsi untuk menjamin keamanan penuh atas setiap transaksi digital Anda.</p>
                            </div>
                            <div class="bg-tertiary w-112.5 h-87.5 rounded-3xl shadow-2xl flex flex-col justify-center px-7.5">
                                <img class="bg-secondary w-15 rounded-full p-2 text-end" src="assets/img/quickpay.png" alt="Icon">
                                <h2 class="text-queaternary text-4xl font-montserrat font-bold my-5">Quick Transaction</h2>
                                <p class="font-montserrat font-light text-lg">Verifikasi pembayaran instan. Pelanggan dapat langsung memulai sesi foto dalam hitungan detik tanpa hambatan.</p>
                            </div>
                        </div>
                        <div class="flex justify-center items-center">
                            <button class="bg-queaternary px-10 h-20 flex text-tertiary font-montserrat font-bold text-2xl rounded-2xl items-center">
                                Berlangganan Sekarang! &nbsp;
                                <svg class="w-6 h-6 text-tertiary" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <svg class="absolute bottom-0 z-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="var(--color-queaternary)" fill-opacity="1" d="M0,96L48,90.7C96,85,192,75,288,85.3C384,96,480,128,576,165.3C672,203,768,245,864,261.3C960,277,1056,267,1152,218.7C1248,171,1344,85,1392,42.7L1440,0L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
        </section>
        <footer class="h-[40vh] bg-queaternary flex justify-center">
            <div class="h-11/12 w-11/12 rounded-4xl border-(--glass-border) shadow-2xl/20 inset-shadow-sm inset-shadow-white/20 backdrop-blur-sm bg-(--glass-bg)"></div>
        </footer>
    </body>
</html>