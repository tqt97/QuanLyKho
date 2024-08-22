<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel,WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new Product([
            'common_title' => $row['common_title'],
            'product_title' => $row['product_title'],
            'slug' => Str::slug($row['product_title']).'-'. rand(1000,9999),
            'description' => $row['description'],
            'dosage' => $row['dosage'],
            'expiry_date' => $row['expiry_date'],
            'qty_per_product' => $row['qty_per_product'],
            'original_price' => $row['original_price'],
            'sell_price' => $row['sell_price'],
            'image' => $row['image'],
        ]);
    }
}
