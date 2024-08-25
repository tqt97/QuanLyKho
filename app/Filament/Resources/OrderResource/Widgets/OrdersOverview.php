<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    use InteractsWithPageTable;

    // protected int | string | array $columnSpan = 5;
    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    protected function getStats(): array
    {
        $total_orders = Order::count();
        $total_revenue = Order::sum('total_price');
        $total_price_today = Order::whereDate('created_at', today())->sum('total_price');
        // get total this week
        $total_price_this_week = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_price');
        // total order today
        $total_order_today = Order::whereDate('created_at', today())->count();
        // total order this week
        $total_order_this_week = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // compare total order with last month
        $total_order_last_month = Order::whereBetween('created_at', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->subMonth()])->count();
        $compare_order_total_today_last_month = $total_order_today - $total_order_last_month;
        $description_total_today_last_month = $compare_order_total_today_last_month > 0 ? 'Tăng '.$compare_order_total_today_last_month.' so với tháng trước' : 'Giảm '.abs($compare_order_total_today_last_month).' so với tháng trước';
        $icon_total_today_last_month = $compare_order_total_today_last_month > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_total_today_last_month = $compare_order_total_today_last_month > 0 ? 'success' : 'danger';

        // compare TOTAL ORDER TODAY with TOTAL ORDER YESTERDAY
        $total_order_yesterday = Order::whereDate('created_at', today()->subDay())->count();
        $compare_order_total_today_yesterday = $total_order_today - $total_order_yesterday;
        $description_total_today_yesterday = $compare_order_total_today_yesterday > 0 ? 'Tăng '.$compare_order_total_today_yesterday.' so với hôm qua' : 'Giảm '.abs($compare_order_total_today_yesterday).' so với hôm qua';
        $icon_total_today_yesterday = $compare_order_total_today_yesterday > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_total_today_yesterday = $compare_order_total_today_yesterday > 0 ? 'success' : 'danger';

        // compare TOTAL ORDER THIS WEEK with TOTAL ORDER LAST WEEK
        $total_order_last_week = Order::whereBetween('created_at', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()])->count();
        $compare_order_this_week_last_week = $total_order_this_week - $total_order_last_week;
        $description_this_week_last_week = $compare_order_this_week_last_week > 0 ? 'Tăng '.$compare_order_this_week_last_week.' so với tuần trước' : 'Giảm '.abs($compare_order_this_week_last_week).' so với tuần trước';
        $icon_this_week_last_week = $compare_order_this_week_last_week > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_this_week_last_week = $compare_order_this_week_last_week > 0 ? 'success' : 'danger';

        // compare TOTAL PRICE TODAY with TOTAL PRICE YESTERDAY
        $total_price_yesterday = Order::whereDate('created_at', today()->subDay())->sum('total_price');
        $compare_price_total_today_yesterday = $total_price_today - $total_price_yesterday;
        $description_total_price_today_yesterday = $compare_price_total_today_yesterday > 0 ? 'Tăng '.format_price($compare_price_total_today_yesterday).' so với hôm qua' : 'Giảm '.format_price(abs($compare_price_total_today_yesterday)).' so với hôm qua';
        $icon_total_price_today_yesterday = $compare_price_total_today_yesterday > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_total_price_today_yesterday = $compare_price_total_today_yesterday > 0 ? 'success' : 'danger';

        // compare TOTAL PRICE THIS WEEK with TOTAL PRICE LAST WEEK
        $total_price_last_week = Order::whereBetween('created_at', [now()->startOfWeek()->subWeek(), now()->endOfWeek()->subWeek()])->sum('total_price');
        $compare_price_this_week_last_week = $total_price_this_week - $total_price_last_week;
        $description_price_this_week_last_week = $compare_price_this_week_last_week > 0 ? 'Tăng '.format_price($compare_price_this_week_last_week).' so với tuần trước' : 'Giảm '.format_price(abs($compare_price_this_week_last_week)).' so với tuần trước';
        $icon_price_this_week_last_week = $compare_price_this_week_last_week > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_price_this_week_last_week = $compare_price_this_week_last_week > 0 ? 'success' : 'danger';

        // compare TOTAL PRICE THIS WEEK with TOTAL PRICE LAST MONTH
        $total_price_last_month = Order::whereBetween('created_at', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->subMonth()])->sum('total_price');
        $compare_price_this_week_last_month = $total_price_this_week - $total_price_last_month;
        $description_price_this_week_last_month = $compare_price_this_week_last_month > 0 ? 'Tăng '.format_price($compare_price_this_week_last_month).' so với tháng trước' : 'Giảm '.format_price(abs($compare_price_this_week_last_month)).' so với tháng trước';
        $icon_price_this_week_last_month = $compare_price_this_week_last_month > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $color_price_this_week_last_month = $compare_price_this_week_last_month > 0 ? 'success' : 'danger';

        // compare total order this month with total order last month
        // $total_order_last_month = Order::whereBetween('created_at', [now()->startOfMonth()->subMonth(), now()->endOfMonth()->subMonth()])->count();

        // compare total order this year with total order last year
        // $total_order_last_year = Order::whereBetween('created_at', [now()->startOfYear()->subYear(), now()->endOfYear()->subYear()])->count();

        return [
            // TOTAL ORDER TODAY VS TOTAL ORDER YESTERDAY
            Stat::make('total_orders', $total_orders)
                ->label(__('shop/order.total_orders'))
                ->color($color_total_today_last_month)
                ->description($description_total_today_last_month)
                ->descriptionIcon($icon_total_today_last_month),
            // TOTAL ORDER THIS WEEK VS TOTAL ORDER LAST WEEK
            Stat::make('total_order_this_week', $total_order_this_week)
                ->label(__('shop/order.total_order_this_week'))
                ->description($description_this_week_last_week)
                ->descriptionIcon($icon_this_week_last_week)
                ->color($color_this_week_last_week),
            // TOTAL ORDER TODAY VS TOTAL ORDER YESTERDAY
            Stat::make('total_order_today', $total_order_today)
                ->label(__('shop/order.total_order_today'))
                ->color($color_total_today_yesterday)
                ->description($description_total_today_yesterday)
                ->descriptionIcon($icon_total_today_yesterday),
            // TOTAL PRICE THIS WEEK VS TOTAL PRICE LAST WEEK
            Stat::make('total_revenue', format_price($total_revenue).' ₫')
                ->label(__('shop/order.total_revenue'))
                ->description($description_price_this_week_last_month)
                ->descriptionIcon($icon_price_this_week_last_month)
                ->color($color_price_this_week_last_month),
            // TOTAL PRICE THIS WEEK VS TOTAL PRICE LAST WEEK
            Stat::make('total_price_this_week', format_price($total_price_this_week).' ₫')
                ->label(__('shop/order.total_price_this_week'))
                ->description($description_price_this_week_last_week)
                ->descriptionIcon($icon_price_this_week_last_week)
                ->color($color_price_this_week_last_week),
            // TOTAL PRICE TODAY VS TOTAL PRICE YESTERDAY
            Stat::make('total_price_today', format_price($total_price_today).' ₫')
                ->label(__('shop/order.total_price_today'))
                ->description($description_total_price_today_yesterday)
                ->descriptionIcon($icon_total_price_today_yesterday)
                ->color($color_total_price_today_yesterday),

        ];
    }
}
