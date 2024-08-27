<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $total_products = Product::count();
        // get product with the most orders
        $id_popular = OrderItem::select('product_id')
            ->selectRaw('count(*) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->first();

        $popular_products = $id_popular ? Product::find($id_popular['product_id'])?->first() : null;

        // get product with the most price
        $id_price = Product::max('sell_price');
        // dd($id_price);
        $most_expensive_product = Product::where('sell_price', $id_price)->first() ?? null;

        return [
            Stat::make('total_products', $total_products)
                ->label(__('shop/product.total_products')),
            Stat::make('most_popular_product', $popular_products['common_title'] ?? null)
                ->label(__('shop/product.most_popular_product'))
                ->description($popular_products['product_title'] ?? null),
            Stat::make('Average time on page', $most_expensive_product['common_title'])
                ->label(__('shop/product.most_expensive_product'))
                ->description(format_price($most_expensive_product['sell_price']).' ₫'),
        ];
    }
}
