<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth focus:scroll-auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Language" content="vi-VN">
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="keywords" content="BachHoaTL, Bách Hóa TL HCM, BÁCH HÓA TL HCM, THẾ GIỚI HÀNG NHẬP">
    <meta name="description" content="BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP">
    {{-- <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title> --}}
    <title>BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP</title>

    {{-- // favion --}}
    <link rel="shortcut icon" href="{{ asset('images/favicon-16x16.ico') }}" type="image/x-icon">

    {{-- og share --}}
    <meta property="og:title" content="BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP">
    <meta property="og:description" content="BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP">
    <meta property="og:image" content="{{ asset('images/og-image-1200x630') }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:type" content="website">

    {{-- twitter --}}
    <meta name="twitter:title" content="BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP">
    <meta name="twitter:description" content="BÁCH HÓA TL HCM- THẾ GIỚI HÀNG NHẬP">
    <meta name="twitter:image" content="{{ asset('images/og-image-1200x630') }}">
    <meta name="twitter:card" content="summary_large_image">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @livewireStyles
    @vite('resources/css/app.css')
</head>

<body class="bg-white text-gray-600 work-sans leading-normal text-base tracking-normal">
    <nav id="header" class="w-full mx-auto z-30 top-0 py-2 border shadow bg-[#c11f27]">
        <div class="max-w-7xl mx-auto container flex flex-wrap items-center justify-between mt-0 px-6">

            {{-- <label for="menu-toggle" class="cursor-pointer md:hidden block">
                <svg class="fill-current text-gray-900" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                    viewBox="0 0 20 20">
                    <title>menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                </svg>
            </label>
            <input class="hidden" type="checkbox" id="menu-toggle" /> --}}

            <div class="hidden md:flex md:items-center md:w-auto w-full order-3 md:order-1" id="menu">
                <nav>
                    <ul class="md:flex items-center justify-between text-base text-gray-700 pt-4 md:pt-0">
                        {{-- <li class="">
                            <a class="font-semibold text-black inline-block no-underline hover:text-orange-600 py-2"
                                href="/admin">
                                Admin
                            </a>
                        </li> --}}
                        {{-- <li><a class="inline-block no-underline hover:text-black hover:underline py-2 px-4"
                                href="#">About</a></li> --}}
                    </ul>
                </nav>
            </div>

            <div class="order-1 md:order-2">
                <a class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-orange-600 text-xl "
                    href="/">
                    {{-- <svg class="fill-current text-orange-600" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24">
                        <path
                            d="M5,22h14c1.103,0,2-0.897,2-2V9c0-0.553-0.447-1-1-1h-3V7c0-2.757-2.243-5-5-5S7,4.243,7,7v1H4C3.447,8,3,8.447,3,9v11 C3,21.103,3.897,22,5,22z M9,7c0-1.654,1.346-3,3-3s3,1.346,3,3v1H9V7z M5,10h2v2h2v-2h6v2h2v-2h2l0.002,10H5V10z" />
                    </svg> --}}
                    {{-- BachHoaTL --}}
                    <img src="{{ asset('images/logo-tl.png') }}" alt="BachHoaTL" class="md:h-16">
                </a>
            </div>

            <div class="order-2 md:order-3 flex items-center" id="nav-content">

                {{-- <a class="inline-block no-underline hover:text-black" href="/admin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-slate-100">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>

                </a> --}}

                {{-- <a class="pl-3 inline-block no-underline hover:text-black" href="#">
                    <svg class="fill-current hover:text-black" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24">
                        <path
                            d="M21,7H7.462L5.91,3.586C5.748,3.229,5.392,3,5,3H2v2h2.356L9.09,15.414C9.252,15.771,9.608,16,10,16h8 c0.4,0,0.762-0.238,0.919-0.606l3-7c0.133-0.309,0.101-0.663-0.084-0.944C21.649,7.169,21.336,7,21,7z M17.341,14h-6.697L8.371,9 h11.112L17.341,14z" />
                        <circle cx="10.5" cy="18.5" r="1.5" />
                        <circle cx="17.5" cy="18.5" r="1.5" />
                    </svg>
                </a> --}}

            </div>
        </div>
    </nav>

    <main class="my-12">
        <div class="max-w-7xl mx-auto flex flex-grow md:px-2 px-4 relative">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts
</body>

</html>
