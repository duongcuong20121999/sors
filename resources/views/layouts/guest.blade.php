<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=1200">
        
        <title>Website đăng ký lấy số trực tuyến SOSR - Phường Quang Trung, Tp Vinh, Nghệ An</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>
            .style-font {
                text-align: center;
                font-family: 'Poppins', 'Segoe UI', 'Helvetica Neue', sans-serif;
                font-weight: 600;
            }
        </style>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <?php $setting = \App\Models\Setting::first(); ?>
    <body class="font-sans text-gray-900 antialiased" style="zoom: 1;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                     <img src="{{ !empty($setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/logo.png') }}"
                            alt="Logo" style="width: 128px;" class="logo" />
                </a>
            </div>
            <h1 class="style-font">
                HỆ THỐNG ĐĂNG KÝ DỊCH VỤ MỘT CỬA TRỰC TUYẾN<br>PHƯỜNG QUANG TRUNG
              </h1>


            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
