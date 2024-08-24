<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Imports\ProductImport;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
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
}
