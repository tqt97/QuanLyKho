<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'common_title' => 'Focus',
            'product_title' => $product_title = '1 Viên Uống Bổ Não Focus Factor (180v) 12/2025 (Hộp) - 585kFocus factor',
            // 'sell_title' => '1 Viên Uống Bổ Não Focus Factor (180v) 12/2025 (Hộp) - 585k',
            'slug' => Str::slug($product_title),
            'description' => 'Focus Factor có thể được sử dụng cho tất cả mọi người trong độ tuổi từ 15 đến 80 tuổi
- Người làm việc trong môi trường căng thẳng, mệt mỏi
- Người đang trong giai đoạn học hành, thi cử căng thẳng
            ',
            'image' => '',
            'dosage' => 'Uống 4 viên/ ngày chia 2 lần sau ăn, tối đa không quá 8 viên/ ngày',
            'expiry' => '06-2026',
            'unit' => '180 viên',
            // 'original_price' => 100,
            'sell_price' => 590000,
        ]);
    }
}
