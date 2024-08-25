<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    use PageHasContextMenu;

    protected static string $resource = ProductResource::class;

    public static function getContextMenuActions(): array
    {
        return [
            RefreshAction::make(),
            GoBackAction::make(),
            GoForwardAction::make(),
        ];
    }
}
