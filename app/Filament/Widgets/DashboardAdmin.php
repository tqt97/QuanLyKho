<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardAdmin extends BaseWidget
{
    protected function getStats(): array
    {
        $total_orders = Order::count();
        $total_revenue = Order::sum('total_price');
        $average_order_value = Order::avg('total_price');
        $total_products = Product::count();
        $total_customers = Customer::count();
        $total_product_price = Product::sum('original_price');

        return [
            Stat::make('total_products', $total_products)
                ->label(__('shop/product.total_products'))
                ->color('warning'),
            Stat::make('total_orders', $total_orders)
                ->label(__('shop/order.total_orders'))
                ->color('success'),
            Stat::make('total_customers', $total_customers)
                ->label(__('shop/customer.total_customers'))
                ->color('danger'),
            Stat::make('average_order_value', format_price($total_product_price).' ₫')
                ->label(__('shop/product.total_product_price'))
                ->color('info'),
            Stat::make('total_revenue', format_price($total_revenue).' ₫')
                ->label(__('shop/order.total_revenue'))
                ->color('primary'),
            Stat::make('average_order_value', format_price($average_order_value).' ₫')
                ->label(__('shop/order.average_order_value'))
                ->color('info'),
        ];
    }
}
