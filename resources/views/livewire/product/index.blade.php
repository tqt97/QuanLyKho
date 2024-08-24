<div class="relative min-h-screen bg-white rounded-l w-full">

    <div class="x-full mx-auto flex min-h-full flex-col">
            <div class="sticky w-full mx-auto sticky1 top-0 bg-white z-10 lg:hidden block">

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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-3 text-gray-400 hover:text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        <div class="mx-auto flex w-full items-start gap-x-4 py-10">
            <aside class="sticky top-0 hidden w-72 shrink-0 lg:block ">
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
            </aside>
            <main class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:px-0 px-2">

                    @if (count($products))
                        @foreach ($products as $product)
                            <x-product-card wire:key="{{ $product->id }}" :product="$product" />
                        @endforeach
                    @else
                        <div class="w-full bg-white">

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
