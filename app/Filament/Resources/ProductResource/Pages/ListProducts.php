<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Widgets\ProductsOverview;
use App\Imports\ProductImport;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    use ExposesTableToWidgets;
    use PageHasContextMenu;

    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // \EightyNine\ExcelImport\ExcelImportAction::make()
            //     ->color("primary"),
            Action::make('Import')
                ->label(__('shop/product.import_product'))
                ->color('success')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    FileUpload::make('file'),
                ])
                ->action(function (array $data) {
                    // dd($data);
                    $file = public_path('storage/'.$data['file']);

                    Excel::import(new ProductImport, $file);

                    Notification::make()
                        ->title(__('shop/product.import_success'))
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label(__('shop/product.create_new_product')),
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
            ProductsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('shop/order.tabs.all')),
            'today' => Tab::make(__('shop/order.tabs.today'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->startOfDay())),
            'this_week' => Tab::make(__('shop/order.tabs.this_week'))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subWeek())),
            'this_month' => Tab::make(__('shop/order.tabs.this_month'))->modifyQueryUsing(fn (Builder $query) => $query->where('created_at', '>', now()->subMonth())),
        ];
    }
}
