<div class="relative min-h-screen bg-white rounded-l w-full">

    <div class="relative1 max-w-7xl mx-auto sticky top-0 bg-white z-10">

        <div class="relative">
            <input
                class="appearance-none border-2 pl-10 border-gray-500 hover:border-gray-700 transition-colors rounded-md w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:ring-orange-600 focus:border-orange-600 focus:shadow-outline"
                wire:model.live.debounce.250ms="search" autofocus
                placeholder="{{ __('frontend/layout.search_placeholder') }}" />
            <div class="absolute right-0 inset-y-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5 text-gray-400 hover:text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <div class="absolute left-0 inset-y-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-3 text-gray-400 hover:text-gray-500"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

    </div>
    <div class="flex justify-between items-center border-b border-gray-200 py-3 px-2">
        <div class="flex text-gray-800 group">
            @if ($search)
                <span class="ml-2">
                    {{ __('frontend/layout.contain_text') }}<span
                        class="font-semibold text-orange-800 font-italic">{{ $search }}</span>
                </span>
            @endif
            @if ($search)
                <button class="ml-3 text-gray-800" wire:click="clearFilters()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 group-hover:w-5 group-hover:h-5 text-gray-400 group-hover:text-gray-900 transition duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
        {{-- <div class="flex items-center">
            <div class="flex items-center">
                <span>{{ __('frontend/layout.order_created_at') }}</span>
                <button class="ml-1" wire:click="toggleSort">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 text-gray-950 dark:text-gray-950 transition-all duration-200 {{ $sort === 'asc' ? 'hidden x-cloak' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21 21 17.25" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="w-6 h-6 text-gray-950 dark:text-gray-950 transition-all duration-200 {{ $sort === 'desc' ? 'hidden x-cloak' : '' }}">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                    </svg>
                </button>
            </div>
        </div> --}}
    </div>
    <div class="border-gray-800">
        <section class="mb-10">
            {{-- <div class="relative max-w-7xl mx-auto md:px-2 px-2">
                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10 mt-5"> --}}

            @if (count($products))
                <section
                    class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">
                    @foreach ($products as $product)
                        <x-product-card wire:key="{{ $product->id }}" :product="$product" />
                    @endforeach

                </section>
            @else
                <div class="text-center w-full mt-20 mx-auto text-gray-800">
                    {{ __('frontend/layout.no_data') }}
                </div>
            @endif
            {{-- </div>
            </div> --}}

        </section>
        <div class="px-2">
            @if (count($products))
                {{-- {{ $products->links(['scrollTo' => false]) }} --}}
                {{ $products->links(data: ['scrollTo' => false]) }}
            @endif
        </div>
    </div>
</div>
