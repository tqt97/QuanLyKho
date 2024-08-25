<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use PageHasContextMenu;

    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
