<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrdersOverview;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;

class ManageOrders extends ManageRecords
{
    use ExposesTableToWidgets;
    use HasTabs;
    use PageHasContextMenu;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label(__('shop/order.create_new_order'))
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()?->id() ?? null;

                    $last_id = OrderResource::getModel()::latest()->first();
                    if ($last_id) {
                        $last_id = $last_id->id;
                        $last_id++;
                    } else {
                        $last_id = 1;
                    }

                    // $uid = uniqid();
                    $date = date('d-m-Y');
                    $data['uuid'] = $date.'-'.$last_id;

                    return $data;
                })
                ->modalWidth(MaxWidth::SevenExtraLarge),
        ];
    }

    public static function getContextMenuActions(): array
    {
        return [
            RefreshAction::make(),
            GoBackAction::make(),
            GoForwardAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrdersOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('shop/order.tabs.all')),
            'today' => Tab::make(__('shop/order.tabs.today'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->startOfDay())),
            'yesterday' => Tab::make(__('shop/order.tabs.yesterday'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subDay())),
            '2_days_ago' => Tab::make(__('shop/order.tabs.2_days_ago'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subDays(2))),
            '3_days_ago' => Tab::make(__('shop/order.tabs.3_days_ago'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subDays(3))),
            '5_days_ago' => Tab::make(__('shop/order.tabs.5_days_ago'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subDays(5))),
            'this_week' => Tab::make(__('shop/order.tabs.this_week'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subWeek())),
            'this_month' => Tab::make(__('shop/order.tabs.this_month'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subMonth())),
        ];
    }
}
