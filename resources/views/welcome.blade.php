<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative   min-h-screen bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif

            <div class="max-w-7xl flex mx-auto p-6 lg:p-8">
                <div class="w-1/2 flex flex-col justify-center text-white">
                    <h3 class="text-3xl font-bold mb-2">Booking Lapangan Menjadi Lebih Mudah</h3>
                    <p>Bermain futsal jadi lebih seru tanpa perlu pusing tidak dapat lapangan main</p>
                </div>
                <div class="w-1/2 flex justify-center">
                    <img class="p-8 img-fluid" src="{{ asset('img/hero.jpg') }}" alt="">
                </div>
                
            </div>

            <footer class="absolute bottom-0 left-0 right-0 p-4 bg-gray-800 flex justify-evenly text-gray-400">
                <div class="w-1/4">
                    <p class="mb-2 font-bold text-gray-300">Follow us:</p>
                    <div class="mb-4 flex gap-2">
                        <a href="">
                            <img src="{{ asset('img/facebook.svg') }}" alt="facebook" class="bg-white p-1">
                        </a>
                        <a href="">
                            <img src="{{ asset('img/instagram.svg') }}" alt="instagram" class="bg-white p-1">
                        </a>
                        <a href="">
                            <img src="{{ asset('img/twitter.svg') }}" alt="twitter" class="bg-white p-1">
                        </a>
                        <a href="">
                            <img src="{{ asset('img/linkedin.svg') }}" alt="linkedin" class="bg-white p-1">
                        </a>
                    </div>
                    <div>
                        <p>All Rights Reserved</p>
                        <p>Privacy Policy</p>
                    </div>
                </div>
                <div class="w-1/4">
                    <div class="mb-3">
                        <h3 class="font-bold text-gray-300">Open Hour: </h3>
                        <p>Monday, Tuesday, Wednesday, Thursday, and Sunday: 08.00 am - 12.00 pm</p>
                        <p>Tuesday, and Sunday: 08.00 am - 12.00 pm</p>
                    </div>
                </div>
                <div class="w-1/4">
                    <div class="mb-3">
                        <h3 class="font-bold text-gray-300">Location: </h3>
                        <p>Victory Residence F15</p>
                    </div>
                </div>
            </footer>
          
        </div>

        
    </body>
</html>
