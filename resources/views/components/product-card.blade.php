{{-- @props(['product']) --}}

<div class="w-96 bg-white shadow-md rounded-xl duration-500 hover:shadow-2xl transition">
    <a href="#">
        <img src="https://images.unsplash.com/photo-1651950519238-15835722f8bb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwcm9maWxlLXBhZ2V8Mjh8fHxlbnwwfHx8fA%3D%3D&auto=format&fit=crop&w=500&q=60"
            alt="Product" class="h-90 w-full object-cover rounded-t-xl" />
        <div class="px-4 py-3 w-full">
            {{-- <span class="text-gray-400 mr-3 uppercase text-xs">Brand</span> --}}
            <p class="text-xl font-bold text-orange-600 truncate block mt-2">{{ $product->common_title }}</p>
            <p class="text-lg font-bold text-black truncate block mt-2 mb-2">{{ $product->product_title }}</p>
            <hr>
            <div class="flex items-center my-2">
                <p class="text-md text-black cursor-auto">
                    <span class="font-bold text-gray-800">Giá bán :</span> {{ $product->sell_price }} VND
                </p>
                <del>
                    {{-- <p class="text-sm text-gray-600 cursor-auto ml-2"> {{ $product->original_price }}</p> --}}
                </del>
                {{-- <div class="ml-auto flex">

                    {{ $product->expiry_date }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                        fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z" />
                        <path
                            d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                    </svg>
                </div> --}}
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-4">
                        <span class="font-bold text-gray-800">Số lượng :</span>
                        {{ $product->qty_per_product }}
                    </span>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-2">
                    <span class="font-bold text-gray-800">Liều dùng :</span> {{ $product->dosage }}
                </span>
            </div>
            <div class="my-2">
                <span class="mr-3 text-md text-black mt-2 mb-2">
                    <span class="font-bold text-gray-800">Hạn sử dụng :</span> {{ $product->expiry_date }}</span>
            </div>
            <div class="text-black text-justify mt-2">
                <span class="font-bold text-gray-800">Mô tả :</span>
                {{ $product->description }}
            </div>
        </div>
    </a>
</div>
