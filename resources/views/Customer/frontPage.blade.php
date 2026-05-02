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
    <script id="midtrans-script" type="text/javascript" src="https://api.midtrans.com/v2/assets/js/midtrans-new-3ds.min.js" data-environment="sandbox" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.12/featherlight.min.js"></script>
    <section style="background-image: url(/assets/img/bg2.png)" class="h-[80vh] m-auto w-11/12 rounded-[50px] shadow-2xl/50 bg-no-repeat bg-cover flex justify-center items-center">
        <form action="/photo/payment" method="POST">
            @csrf
            <button id="pay-button" class="bg-secondary h-75 w-150 rounded-3xl shadow-2xl/50 flex flex-col items-center justify-center relative" type="submit">
                <img class="w-100" src="/assets/img/logocreamo.png" alt="Logo">
                <p class="font-montserrat text-queaternary font-medium text-xl absolute bottom-20">Create a moment</p>
            </button>
        </form>
    </section>
</body>
</html>