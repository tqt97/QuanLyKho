<?php

namespace App\Imports;

use App\Models\Product;
use Faker\Core\Uuid;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    use Importable;

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        $common_title = $row['common_title'];
        $product_title = $row['product_title'];
        $slug = $common_title ? Str::slug($common_title) : Str::slug($product_title);
        $slug .= '-' . uniqid();
        return new Product([
            'common_title' => $common_title,
            'product_title' => $product_title,
            'slug' => $slug,
            'description' => $row['description'],
            'dosage' => $row['dosage'],
            'expiry' => $row['expiry'],
            'unit' => $row['unit'],
            'original_price' => $row['original_price'],
            'sell_price' => $row['sell_price'],
            'image' => $row['image'],
        ]);
    }
}
