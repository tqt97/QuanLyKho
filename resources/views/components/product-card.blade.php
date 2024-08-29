{{-- @props(['product']) --}}

<div class=" shadow-lg rounded-xl hover:shadow-2xl transition duration-300">
    <div>
        <img src="{{ $product->getUrlImage() }}" alt="{{ $product->product_title }}"
            class="h-auto w-full object-cover rounded-t-xl" />
        <div class="px-4 py-3 w-full">
            {{-- <span class="text-gray-400 mr-3 uppercase text-xs">Brand</span> --}}

            <p class="text-2xl font-bold text-gray-900 truncate block mt-2 cp" title="Sao chép"
                x-clipboard.raw="{{ $product->common_title }}">
                {{ $product->common_title }}
            </p>

            <p class="text-lg font-medium text-gray-900 truncate block mt-2 mb-2 cp" title="Sao chép"
                x-clipboard.raw="{{ $product->product_title }}">{{ $product->product_title }}</p>
            <hr>
            <div class="flex items-center my-2">
                <p class="text-md text-black cursor-auto">
                    <span class="font-semibold text-gray-800">Giá bán:</span>
                    <span class="font-semibold text-red-500 text-3xl">
                        {{ $product->formatPrice() }} <sup class="text-sm">VND</sup>
                    </span>
                </p>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-4">
                    <span class="font-semibold text-gray-800">Đơn vị:</span>
                    {{ $product->qty_per_product }}
                </span>
            </div>
            <div class="my-2 cp" x-clipboard.raw="{{ $product->dosage }}" title="Sao chép">
                <span class="mr-3 text-md text-black mt-2 mb-2 ">
                    <span class="font-semibold text-gray-800">Liều dùng:</span> {{ $product->dosage }}
                </span>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-2">
                    <span class="font-semibold text-gray-800">Hạn sử dụng:</span> {{ $product->expiry }}</span>
            </div>
            <div class="text-black text-justify mt-2 cp" x-clipboard.raw="{{ $product->description }}">
                <span class="font-semibold text-gray-800">Mô tả:</span>
                {{ $product->description }}
            </div>
        </div>
    </div>
</div>
