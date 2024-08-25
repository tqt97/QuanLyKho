{{-- @props(['product']) --}}

<div class=" bg-white shadow-md rounded-xl duration-500 hover:shadow-2xl transition">
    <a href="#" title="{{ $product->product_title }}">
        <img src="{{ $product->getUrlImage() }}" alt="{{ $product->product_title }}"
            class="h-auto w-full object-cover rounded-t-xl" />
        <div class="px-4 py-3 w-full">
            {{-- <span class="text-gray-400 mr-3 uppercase text-xs">Brand</span> --}}
            <p class="text-xl font-bold text-gray-900 truncate block mt-2">{{ $product->common_title }}</p>
            <p class="text-lg font-bold text-gray-800 truncate block mt-2 mb-2">{{ $product->product_title }}</p>
            <hr>
            <div class="flex items-center my-2">
                <p class="text-md text-black cursor-auto">
                    <span class="font-bold text-gray-800">Giá bán:</span>
                    <span class="font-bold text-red-600 text-2xl">
                        {{ $product->formatPrice() }} VND
                    </span>
                </p>
                <del>
                    {{-- <p class="text-sm text-gray-600 cursor-auto ml-2"> {{ $product->original_price }}</p> --}}
                </del>

            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-4">
                    <span class="font-bold text-gray-800">Số lượng:</span>
                    {{ $product->qty_per_product }}
                </span>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-2">
                    <span class="font-bold text-gray-800">Liều dùng:</span> {{ $product->dosage }}
                </span>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-2">
                    <span class="font-bold text-gray-800">Hạn sử dụng:</span> {{ $product->expiry_date }}</span>
            </div>
            <div class="text-black text-justify mt-2">
                <span class="font-bold text-gray-800">Mô tả:</span>
                {{ $product->description }}
            </div>
        </div>
    </a>
</div>
