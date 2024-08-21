<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    // use HasWizard;

    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()?->id() ?? null;

        $uid = uniqid();
        $data['uuid'] = 'TL-' . $uid;

        return $data;
    }

    // public function form(Form $form): Form
    // {
    //     return parent::form($form)
    //         ->schema([
    //             Wizard::make($this->getSteps())
    //                 ->startOnStep($this->getStartStep())
    //                 ->cancelAction($this->getCancelFormAction())
    //                 ->submitAction($this->getSubmitFormAction())
    //                 ->skippable($this->hasSkippableSteps())
    //                 ->contained(false),
    //         ])
    //         ->columns(null);
    // }

    // protected function getSteps(): array
    // {
    //     return [
    //         Step::make(__('shop/order.order_details'))
    //             ->schema([
    //                 Section::make()->schema(OrderResource::getDetailsFormSchema())->columns(),
    //             ]),

    //         Step::make(__('shop/order.order_items'))
    //             ->schema([
    //                 Section::make()->schema([
    //                     OrderResource::getItemsRepeater(),
    //                 ]),
    //             ]),
    //     ];
    // }
}
