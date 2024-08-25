<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;

class ManageOrders extends ManageRecords
{
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
}
