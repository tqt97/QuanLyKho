<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('shop/order.tabs.all')),
            'today' => Tab::make(__())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('created_at', '>', now()->startOfDay())),
            'this_week' => Tab::make(__('shop/order.tabs.this_week'))
                ->modifyQueryUsing(fn(Builder $query) => $query->where('created_at', '>', now()->subWeek())),
            'this_month' => Tab::make(__('shop/order.tabs.this_month'))->modifyQueryUsing(fn(Builder $query) => $query->where('created_at', '>', now()->subMonth())),
        ];
    }
}
