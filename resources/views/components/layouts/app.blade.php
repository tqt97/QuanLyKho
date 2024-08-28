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
    <meta name="description" content="BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP">
    {{-- <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Laravel') }}</title> --}}
    <title>BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP | BACHHOATL</title>

    {{-- // favion --}}
    <link rel="shortcut icon" href="{{ asset('images/favicon-16x16.ico') }}" type="image/x-icon">

    {{-- og share --}}
    <meta property="og:title" content="BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP">
    <meta property="og:description" content="BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP">
    <meta property="og:image" content="{{ asset('images/og-image-1200x630.png') }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:type" content="website">

    {{-- twitter --}}
    <meta name="twitter:title" content="BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP">
    <meta name="twitter:description" content="BÁCH HÓA TL HCM - THẾ GIỚI HÀNG NHẬP">
    <meta name="twitter:image" content="{{ asset('images/og-image-1200x630') }}">
    <meta name="twitter:card" content="summary_large_image">


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-600 work-sans leading-normal text-base tracking-normal">

    <x-alert />

    <main class="min-h-screen">
        <div class="w-full mx-auto flex flex-grow relative">
            {{ $slot }}
        </div>
    </main>
    <div x-data="{ scrollBackTop: false }" x-cloak>
        <button x-show="scrollBackTop"
            x-on:scroll.window="scrollBackTop = (window.pageYOffset > window.outerHeight * 0.5) ? true : false"
            @click.prevent="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Back to top"
            class="fixed bottom-4 md:bottom-8 right-0 py-2 px-2 rounded-md mx-3 my-3 md:my-10 text-white bg-[#c11f27] dark:bg-gray-800 hover:cursor-pointer focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
            </svg>
            <span class="sr-only">Back to top</span>
        </button>
    </div>
    @livewireScripts

    <script>
        function searchComponent() {
            return {
                // isOpen: false,
                init() {
                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'k' && (event.metaKey || event.ctrlKey)) {
                            event.preventDefault();
                            // console.log(this.$refs.searchInput);
                            this.$nextTick(() => this.$refs.searchInput.focus());
                            // this.toggleSearch();
                        }
                    });
                },
                // toggleSearch() {
                //     this.isOpen = !this.isOpen;
                //     if (this.isOpen) {
                //         this.$nextTick(() => this.$refs.searchInput.focus());
                //     }
                // }
            };
        }
    </script>
</body>

</html>
