<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Sign In | CREAMO</title>
    <link rel="shortcut icon" href="/assets/img/logo.svg" type="image/x-icon">
    <!-- Font Awesome for Apple Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-image: url(/assets/img/bg.png)" class="min-h-screen bg-no-repeat bg-cover bg-center flex justify-center items-center font-montserrat py-10">
    
    <div class="w-11/12 max-w-[1200px] min-h-[700px] flex rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] bg-white/40 backdrop-blur-lg border border-white/50 overflow-hidden relative">
        
        <!-- Left Side: Logo Area -->
        <div class="hidden lg:flex flex-col justify-center items-center w-1/2 p-12 relative z-10">
            <div class="w-full max-w-[400px]">
                <img src="/assets/img/logocreamo.png" alt="Creamo Logo" class="w-full drop-shadow-md">
                <p class="text-[#3b4b86] text-3xl font-medium mt-2 tracking-wide text-center">Create a moment</p>
            </div>
        </div>

        <!-- Right Side: Form Area -->
        <div class="w-full lg:w-1/2 flex justify-center items-center p-6 md:p-12 relative z-10">
            <div class="bg-white w-full max-w-[500px] rounded-[30px] shadow-2xl p-8 md:p-12">
                
                <h2 class="text-[32px] font-bold text-black mb-1">Sign In</h2>
                <p class="text-gray-400 text-[13px] mb-8 font-medium">We missed you! Please enter your details</p>

                <form action="/signin" method="POST" class="w-full">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="mb-5">
                        <label for="email" class="block mb-2 text-[13px] font-bold text-gray-800">Email or Phone</label>
                        <input type="text" id="email" name="email" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#172b4d] focus:border-transparent outline-none transition-all text-sm text-gray-800" required>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-2">
                        <label for="password" class="block mb-2 text-[13px] font-bold text-gray-800">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#172b4d] focus:border-transparent outline-none transition-all text-sm text-gray-800" required>
                    </div>
                    <p class="text-[11px] text-gray-400 mb-6">Use 8 or more characters with a mix of letters, numbers & symbols.</p>

                    <!-- Term Checkbox -->
                    <div class="flex items-center mb-8">
                        <div class="flex items-center justify-center w-5 h-5 border border-gray-300 rounded-full bg-white mr-2">
                            <input id="term" type="checkbox" class="opacity-0 absolute w-5 h-5 cursor-pointer">
                            <div class="w-3 h-3 rounded-full bg-[#172b4d] hidden peer-checked:block"></div>
                        </div>
                        <label for="term" class="text-[13px] font-medium text-gray-800 cursor-pointer">I accept the <a href="#" class="text-[#0052cc] hover:underline">Term</a></label>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center mb-6">
                        <div class="flex-grow border-t border-gray-300"></div>
                        <span class="mx-4 text-xs font-bold text-gray-800">Or with</span>
                        <div class="flex-grow border-t border-gray-300"></div>
                    </div>

                    <!-- Social Buttons -->
                    <div class="flex gap-4 mb-8">
                        <a href="/auth/google/redirect" class="flex-1 flex items-center justify-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4 mr-2">
                            <span class="text-[12px] font-bold text-gray-800">Sign In with Google</span>
                        </a>
                        <a href="/auth/apple/redirect" class="flex-1 flex items-center justify-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fa-brands fa-apple text-lg mr-2"></i>
                            <span class="text-[12px] font-bold text-gray-800">Sign In with Apple</span>
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" onclick="alert('Sign In simulasi berhasil!')" class="w-full text-white bg-[#172b4d] hover:bg-[#101f38] font-medium rounded-lg text-sm px-5 py-3 text-center transition-colors shadow-md">
                        Sign In
                    </button>
                    
                    <!-- Footer Link -->
                    <p class="text-[12px] font-medium text-gray-400 mt-6 text-center">
                        Don't have an account? <a href="/signup" class="font-bold text-[#0052cc] hover:underline">Sign Up</a>
                    </p>
                </form>
            </div>
        </div>
        
    </div>

</body>
</html>
