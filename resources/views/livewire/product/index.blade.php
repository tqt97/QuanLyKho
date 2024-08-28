<div class="relative min-h-screen  rounded-l w-full">
    {{-- <div class="fixed top-0 w-full z-30 bg-gray-100 "> --}}

    <nav id="header" class=" w-full mx-auto py-1 shadow bg-[#c11f27] z-30 transition-all duration-300"
        :class="{
            'w-full lg:sticky1 fixed top-0': sticky,
            'fixed top-0 w-full': !sticky
        }"
        x-data="{
            percent: 0,
            sticky: false,
            top: true,
            isOpenMenuMobile: false,
            lastPos: window.scrollY + 0,
            scroll() {
                console.log(window.scrollY, this.$refs.nav.offsetHeight, this.lastPos, window.scrollY, this.top);
                {{-- console.log(this.lastPos); --}}
                this.sticky = window.scrollY > this.$refs.nav.offsetHeight && this.lastPos > window.scrollY;
                this.lastPos = window.scrollY;
                this.top = window.scrollY < this.$refs.nav.offsetHeight;
            },
        }" x-ref="nav" @scroll.window="scroll()">
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
                    </ul>
                </nav>
            </div>

            <div class="order-1 md:order-2">
                <a class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-orange-600 text-xl "
                    href="/">

                    <img src="{{ asset('images/logo-tl.png') }}" alt="BachHoaTL" :class="top ? 'h-16' : 'h-10'">
                </a>
            </div>

            <div class="order-2 md:order-3 flex items-center" id="nav-content">
            </div>
        </div>
        <div class="relative max-w-7xl mx-auto rounded-lg transition duration-300" x-data="searchComponent()" x-init="init()">
            <input x-ref="searchInput"
                class="appearance-none  focuc:bg-white focus:py-2 hover:bg-white border-2 pl-12 border-[#c11f27] hover:border-[#c11f27] transition-colors
                rounded-md w-full px-3 text-black leading-tight focus:outline-none  focus:shadow-outline" :class="top ? 'py-2' : 'py-[0.4rem]'"
                wire:model.live.debounce.300ms="search" placeholder="{{ __('frontend/layout.search_placeholder') }} " />

            {{-- <div wire:loading class="mt-4">Đang tìm kiếm sản phẩm...</div> --}}
            {{-- <div wire:loading
                class="absolute right-[25%] md:right[50%] top-4 inset-y-0 flex items-center cursor-pointer">
                Đang tìm kiếm sản phẩm...
            </div> --}}
            {{-- <div wire:loading.remove> --}}
            @if ($search)
                <div class="absolute right-0 inset-y-0 flex items-center cursor-pointer" wire:click="clearFilters()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-4 w-4 text-gray-500 hover:text-gray-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            @endif

            <div class="absolute left-2 inset-y-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-3 text-gray-400 hover:text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>


    </nav>


    <div class="relative w-full max-w-7xl mx-auto flex min-h-full flex-col mt-40 md:mt-32">
        <div class="w-full mx-auto z-10 md:mt-10">

            <h1 class="text-3xl font-bold text-center mb-10 text-[#c11f27]">Tất cả sản phẩm</h1>
            <div class="mx-auto flex w-full items-start gap-x-4 pb-10 ">
                {{-- <aside class="sticky top-0 hidden w-72 shrink-0 lg:block ">
                <div class="relative1 max-w-7xl mx-auto sticky top-0 bg-white z-10">

                    <div class="relative">
                        <input
                            class="appearance-none border-2 pl-10 border-gray-300 hover:border-gray-400 transition-colors rounded-md w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none  focus:shadow-outline"
                            wire:model.live.debounce.250ms="search" autofocus
                            placeholder="{{ __('frontend/layout.search_placeholder') }}" />
                        @if ($search)
                            <div class="absolute right-0 inset-y-0 flex items-center cursor-pointer"
                                wire:click="clearFilters()">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="-ml-1 mr-3 h-4 w-4 text-gray-500 hover:text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        @endif


                        <div class="absolute left-0 inset-y-0 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 ml-3 text-gray-400 hover:text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                </div>
                <div class="flex justify-between items-center py-3 px-2">
                    <div class="flex text-gray-800 group">
                        @if ($search)
                            <span class="">
                                <span class="font-semibold">{{ count($products) }}</span> kết quả cho từ
                                khóa
                                <span class="font-semibold">{{ $search }}</span>
                            </span>
                        @endif
                    </div>
                </div>
            </aside> --}}
                <main class="flex-1">
                    <div class="grid grid-cols-1 lg:grid-cols-3 sm:grid-cols-2 gap-12 px-2 xl:px-0">

                        @if (count($products))
                            @foreach ($products as $product)
                                <x-product-card wire:key="{{ $product->id }}" :product="$product" />
                            @endforeach
                        @else
                            <div class="w-full ptext-center ">

                                {{-- <div class="text-center w-full mt-20 mx-auto text-gray-800"> --}}
                                {{ __('frontend/layout.no_data') }}
                            </div>
                        @endif
                    </div>
                    <div class="w-full my-10">
                        {{ $products->links(data: ['scrollTo' => false]) }}
                    </div>
                </main>

                {{-- <aside class="sticky top-8 hidden w-44 shrink-0 lg:block bg-gray-200">
                <h1>Right</h1>
            </aside> --}}
            </div>
        </div>

    </div>
</div>
