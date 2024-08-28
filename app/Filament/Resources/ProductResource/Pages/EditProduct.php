<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;

class EditProduct extends EditRecord
{
    use PageHasContextMenu;
    use HasRecordNavigation;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

        ];
        return array_merge($actions, $this->getNavigationActions());
    }

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
