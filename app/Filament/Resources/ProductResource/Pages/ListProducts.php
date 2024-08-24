<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Imports\ProductImport;
use Filament\Pages\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductResource;

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
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    FileUpload::make('file')
                ])
                ->action(function (array $data) {
                    // dd($data);
                    $file = public_path('storage/' . $data['file']);

                    Excel::import(new ProductImport, $file);

                    Notification::make()
                        ->title(__('shop/product.import_success'))
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make()
            ->icon('heroicon-o-plus'),
        ];
    }
}
