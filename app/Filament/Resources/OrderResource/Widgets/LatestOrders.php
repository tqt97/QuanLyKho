<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '5 đơn hàng mới nhất';

    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->sortable()
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('shop/order.customer_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.phone')
                    ->label(__('shop/order.customer_phone'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('shop/order.total_price'))
                    ->currency('VND')
                    ->numeric(locale: 'vi_VN')
                    ->money(
                        currency: 'VND',
                        locale: 'vi_VN'
                    )
                    ->sortable()->color('primary')
                    ->summarize(
                        Tables\Columns\Summarizers\Sum::make()
                            ->label(__('shop/order.order_total'))
                            ->currency('VND')
                            ->numeric(locale: 'vi_VN')
                            ->money(
                                currency: 'VND',
                                locale: 'vi_VN'
                            )
                    ),
                Tables\Columns\TextColumn::make('created_at'),
            ]);
    }
}
