<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use AymanAlhattami\FilamentContextMenu\Actions\GoBackAction;
use AymanAlhattami\FilamentContextMenu\Actions\GoForwardAction;
use AymanAlhattami\FilamentContextMenu\Actions\RefreshAction;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class OrderResource extends Resource
{
    protected static ?string $navigationGroup = 'Cửa hàng';

    protected static ?string $model = Order::class;


    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 4;


    public static function getNavigationBadgeTooltip(): ?string
    {
        return __('shop/order.number_of_orders');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationLabel(): string
    {
        return __('shop/order.order');
    }

    public static function getModelLabel(): string
    {
        return __('shop/order.order');
    }
    protected static ?string $recordTitleAttribute = 'uuid';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make(__('shop/order.order_customer_info'))
                        ->icon('heroicon-o-user')
                        ->footerActions([
                            Forms\Components\Actions\Action::make('add_new_user')
                                ->label(__('shop/order.add_new_user'))
                                ->icon('heroicon-o-user-plus')
                                // ->modalHeading(__('shop/order.are_you_sure'))
                                // ->modalDescription(__('shop/order.modal_description_reset'))
                                // ->disabled(fn(Order $order) => $order->exists)
                                ->hidden(function (Forms\Get $get, Forms\Set $set, Order $order) {
                                    // $phone = Order::whereJsonContains('customer_phone', $get('customer_phone'))->first();
                                    $is_buy = $get('is_buy');
                                    $customer_name = $get('customer_name');
                                    $customer_phone = $get('customer_phone');
                                    // Log::info('phone: ' . $phone ? 'Exist' : 'null');
                                    // Log::info('is_buy: ' . $is_buy . ' ,customer_name: ' . $customer_name );
                                    $is_name    = $customer_name === null || empty($customer_name);
                                    $is_phone   = $customer_phone === null || empty($customer_phone);
                                    // Log::info('is_name: ' . $is_name . ' ,is_phone: ' . $is_phone . ' ,is_buy: ' . $is_buy);
                                    $condition =  $order->exists || $is_buy === true || $is_name  || $is_phone;

                                    // Log::info('condition: ' . $condition);
                                    return $condition;
                                })
                                // ->requiresConfirmation()
                                ->color('success')
                                // ->modalHidden(fn(): bool => $this->role !== 'admin')
                                ->requiresConfirmation(false)
                                ->action(function (Forms\Set $set, Forms\Get $get) {
                                    $id = Customer::create([
                                        'name' => $get('customer_name'),
                                        'phone' => $get('customer_phone'),
                                    ])->id;
                                    $set('customer_id', $id);
                                })
                                // ->mutateFormDataUsing(function (array $data): array {
                                //     $data['last_edited_by_id'] = auth()->id();

                                //     return $data;
                                // })
                                ->modalSubmitActionLabel(__('shop/order.create_customer')),
                        ])
                        ->schema([
                            Forms\Components\Select::make('customer_id')
                                ->label(__('shop/order.customer_phone_check'))
                                ->relationship('customer', 'phone')
                                ->searchable()
                                ->searchDebounce(300)
                                ->preload()
                                ->live(debounce: 500)
                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                    // dd($state);
                                    // dd(...(Order::query()->pluck('customer_phone', 'id')));
                                    // check phone number is exist in customer_phone of order table
                                    // $order = Order::whereJsonContains('customer_phone', $state)->first();
                                    $customer = Customer::where('id', $state)->first();
                                    // dd($order);
                                    if ($customer === null || empty($state)) {
                                        $set('customer_name', null);
                                        $set('is_buy', false);
                                    } else {
                                        $set('customer_name', $customer->name);
                                        $set('is_buy', true);
                                    }
                                })
                                // ->disabled(fn(Order $order) => $order->exists)
                                ->required(
                                    function (Forms\Get $get) {
                                        return $get('customer_id') !== null;
                                    }
                                )
                                ->columnSpan('full'),
                            Forms\Components\TextInput::make('customer_phone')
                                ->label(__('shop/order.customer_phone_new_user'))
                                ->reactive()
                                ->required(
                                    function (Forms\Get $get) {
                                        return $get('customer_id') === null;
                                    }
                                )
                                ->hidden(
                                    function (Forms\Get $get) {
                                        return $get('customer_id') !== null;
                                    }
                                )
                                ->maxLength(255),
                            Forms\Components\TextInput::make('customer_name')
                                ->label(__('shop/order.customer_name'))
                                ->reactive()
                                // ->required()
                                // ->hidden(
                                //     function (Forms\Get $get, Forms\Set $set, Order $order) {
                                //         // Log::info('customer_id: ' . $get('customer_id'));
                                //         $customer = Customer::where('id', $get('customer_id'))->first();
                                //         return $customer !== null && $get('customer_id') !== null;
                                //     }
                                // )
                                ->maxLength(255),
                            Forms\Components\Toggle::make('is_buy')
                                ->label(__('shop/order.is_buy'))
                                ->default(false)
                                // ->onIcon('heroicon-m-bolt')
                                ->onIcon('heroicon-m-user')
                                ->onColor('success')
                                // ->offColor('')
                                ->inline(false)
                                ->hidden(
                                    function (Forms\Get $get) {
                                        return $get('customer_id') !== null;
                                    }
                                )
                            // ->live()

                            // ->reactive()
                            ,
                            Forms\Components\Select::make('bonus_id')
                                ->label(__('shop/order.bonus'))
                                ->relationship('bonus', 'name')
                                ->preload()
                                ->searchable()
                                ->searchDebounce(300),
                            Forms\Components\Select::make('status')
                                ->label(__('shop/order.order_status'))
                                ->options(
                                    OrderStatus::class
                                )
                                ->hidden(fn(Order $order) => !$order->exists)
                                ->searchable()
                                ->searchDebounce(300),
                        ])
                        ->columns(4),

                    Forms\Components\Section::make(__('shop/order.cart'))
                        // ->icon('heroicon-m-squares-2x2')
                        ->description(__('shop/order.order_items_help_text'))
                        ->headerActions([
                            Forms\Components\Actions\Action::make('reset')->label(__('shop/order.reset'))
                                ->modalHeading(__('shop/order.are_you_sure'))
                                ->modalDescription(__('shop/order.modal_description_reset'))
                                ->requiresConfirmation()
                                ->color('danger')
                                ->action(fn(Forms\Set $set) => $set(
                                    'items',
                                    []
                                )),
                            ExportAction::make()
                        ])
                        ->schema([
                            static::getItemsRepeater(),
                        ]),
                ])->columnSpan(4),
                // Forms\Components\Group::make()->schema([
                Forms\Components\Group::make()
                    // ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Forms\Components\Section::make(__('shop/order.cart_label'))
                            // ->description('Total amount of the order')
                            ->icon('heroicon-o-banknotes')
                            ->schema([
                                Forms\Components\TextInput::make('total_price')
                                    ->reactive()
                                    ->label(__('shop/order.order_total'))
                                    // ->disabled()
                                    ->readOnly()
                                    ->numeric()
                                    ->suffix('vnd')
                                    // ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                                    ->default(0),
                            ])
                            ->columns(1),
                        Forms\Components\Section::make(__('shop/order.order_time'))
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('shop/order.created_at'))
                                    ->content(fn(?Order $record): ?string => $record->created_at?->diffForHumans()),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label(__('shop/order.updated_at'))
                                    ->content(fn(?Order $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->hidden(fn(?Order $record) => $record === null)
                            ->columns(1),
                        Forms\Components\Group::make()->schema([
                            TextArea::make('notes')->label(__('shop/order.note'))
                                ->columnSpan('full'),
                        ])
                    ])
                    ->columnSpan(1),
                // ])->columnSpan('1'),
            ])

            ->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('shop/order.customer_name'))
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.phone')
                    ->label(__('shop/order.customer_phone'))
                    ->searchable(isIndividual: true)
                    ->copyable()
                    ->sortable(),

                // Tables\Columns\TextColumn::make('customer_name')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('shop/order.total_price'))
                    ->currency('VND')
                    ->numeric()
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('shop/order.status'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_buy')
                    ->label(__('shop/order.is_buy'))
                    // ->icon(fn($record) => $record->is_buy ? 'heroicon-o-check' : 'heroicon-o-x')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth(MaxWidth::SevenExtraLarge)->modal(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('shop/order.no_orders_yet'))
            ->emptyStateDescription(__('shop/order.no_orders_yet_description'))
            ->emptyStateIcon('heroicon-o-shopping-bag')
        ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOrders::route('/'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            // Forms\Components\Select::make('customer_id')
            //     ->label(__('shop/order.customer_phone'))
            //     ->relationship('customer', 'phone')
            //     ->preload()
            //     ->searchable()
            //     ->required()
            //     ->live()
            //     ->createOptionForm([
            //         TextInput::make('name')
            //             ->label(__('shop/order.customer_name'))
            //             ->required()
            //             ->characterLimit(255)
            //             ->suffixAction(
            //                 Forms\Components\Actions\Action::make('copy')
            //                     ->icon('heroicon-s-clipboard-document-check')
            //                     ->action(function ($livewire, $state) {
            //                         $livewire->js(
            //                             'window.navigator.clipboard.writeText("' . $state . '");
            //         $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
            //                         );
            //                     })
            //             ),
            //         TextInput::make('phone')
            //             ->label(__('shop/order.phone'))
            //             ->required()
            //             ->characterLimit(15)
            //             ->suffixAction(
            //                 Forms\Components\Actions\Action::make('copy')
            //                     ->icon('heroicon-s-clipboard-document-check')
            //                     ->action(function ($livewire, $state) {
            //                         $livewire->js(
            //                             'window.navigator.clipboard.writeText("' . $state . '");
            //         $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
            //                         );
            //                     })
            //             ),

            //         Forms\Components\Select::make('gender')
            //             ->label(__('shop/order.gender'))
            //             ->placeholder(__('shop/order.select_gender'))
            //             ->options([
            //                 'male' => __('shop/order.male'),
            //                 'female' => __('shop/order.female'),
            //             ])
            //             ->required()
            //             ->native(false),
            //     ])
            //     ->createOptionAction(function (Action $action) {
            //         return $action
            //             ->modalHeading(__('shop/order.create_customer'))
            //             ->modalSubmitActionLabel(__('shop/order.create_customer'))
            //             ->modalWidth('lg');
            //     })
            //     ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {

            //         $set('dynamic_hint', Customer::find($state)?->name);
            //     })
            //     // ->prefixAction(
            //     //     Forms\Components\Actions\Action::make('create')
            //     //         ->icon('heroicon-s-plus')
            //     //         ->label(__('shop/order.create_customer'))
            //     //         ->url(CustomerResource::getUrl('create'))
            //     //         ->button()
            //     // )
            //     ->suffixAction(
            //         Forms\Components\Actions\Action::make('copy')
            //             ->icon('heroicon-s-clipboard-document-check')
            //             ->action(function ($livewire, $state) {
            //                 $name = Customer::find($state)?->name;
            //                 $livewire->js(
            //                     'window.navigator.clipboard.writeText("' . $name . '");
            //         $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
            //                 );
            //             })
            //     )
            //     ->columnSpan('full'),
            Forms\Components\TextInput::make('customer_name')
                ->label(__('shop/order.customer_name'))
                ->placeholder(function (Forms\Get $get) {
                    $customer = Customer::find($get('customer_id'));
                    return $customer?->name ?? __('shop/order.customer_phone_hint');
                })
                ->hidden(function (Forms\Get $get) {
                    return ! $get('customer_id');
                })
                ->disabled()
                ->label('')
                // ->extraInputAttributes(['class' => 'text-muted'])
                ->columnSpan('full'),


        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('items')->label(__('shop/order.cart'))
            ->relationship()
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label(__('shop/order.title_product'))
                    ->options(Product::query()->pluck('product_title', 'id'))
                    ->required()
                    ->reactive()
                    // ->afterStateUpdated(fn($state, Forms\Set $set) => $set('unit_price', Product::find($state)?->price ?? 0,))
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                        Log::info('state: ' . $state);
                        if ($state === null || empty($state)) {
                            $set('price',  0);
                            $set('total', 0);
                            $set('quantity', 1);
                            return;
                        }
                        $set('quantity', 1);
                        $set('price', Product::find($state)?->sell_price ?? 0);
                        $set('total', 1 * $get('price'));
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 6,
                    ])
                    ->searchable()
                    ->searchDebounce(300)

                    ->suffixAction(
                        Forms\Components\Actions\Action::make('copy')
                            ->icon('heroicon-s-clipboard-document-check')
                            ->action(function ($livewire, $state) {
                                $name = Product::find($state)?->product_title;
                                $livewire->js(
                                    'window.navigator.clipboard.writeText("' . $name . '");
                    $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
                                );
                            })
                    ),

                Forms\Components\TextInput::make('quantity')
                    ->label(__('shop/order.quantity'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(20)
                    ->default(1)
                    ->columnSpan(['md' => 2])
                    ->live(500)
                    ->required()
                    // ->afterStateUpdated(fn($state, Forms\Set $set) => $set('total', Product::find($state)?->price * $state['quantity'] ?? 0))
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        // dd($get('quantity'));
                        if ($get('quantity') >= 1 && $get('price') > 0) {
                            $set('total', $get('quantity') * $get('price'));
                        }
                    }),

                Forms\Components\TextInput::make('price')
                    ->label(__('shop/order.unit_price'))
                    // ->disabled()
                    // ->readOnly()
                    // ->dehydrated()
                    ->numeric()
                    ->minValue(1)
                    // ->debounce(300)
                    // ->live()
                    // ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)

                    ->live(debounce: 500)
                    ->required()
                    // ->formatStateUsing(fn($state) => $state ?? number_format($state, 3, ',', '.'))
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        // if ($get('quantity') >= 1 && $get('price') > 0)
                        $set('total', $get('quantity') * $get('price'));
                        // });
                        // $set('total', $get('quantity') * $get('price'));
                    })
                    ->columnSpan([
                        'md' => 2,
                    ]),
                Forms\Components\TextInput::make('total')
                    ->label(__('shop/order.total_single_product'))
                    // ->disabled()
                    // ->reactive()
                    ->live(debounce: 500)
                    ->readOnly()
                    ->numeric()
                    ->required()
                    // ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)

                    ->columnSpan([
                        'md' => 2,
                    ]),
            ])
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                $items = $get('items');
                $total_price = 0;
                // $discount = 0;
                // $vat = 0;
                foreach ($items as $orderItem) {
                    $product = Product::find($orderItem['product_id']);
                    if ($product) {
                        // $getDiscount = 0;
                        // if ($product->discount_to && Carbon::parse($product->discount_to)->isFuture()) {
                        //     $getDiscount = $product->discount;
                        // }
                        // $price_final =  $product->sell_price === $orderItem['price'] ? $product->sell_price : $orderItem['price'];
                        $price_sell = $product->sell_price;
                        $price_edit = $orderItem['price'];

                        $price_final = $price_edit ? $price_edit : $price_sell;

                        Log::info('>> price_final: ' . $price_final . ' ,price_sell: ' . $price_sell . ' ,price_edit: ' . $price_edit);

                        // $total += ((($product->price + $product->vat) - $getDiscount) * $orderItem['qty']);
                        $total_price += $price_final * $orderItem['quantity'];
                        // $discount += ($getDiscount * $orderItem['qty']);
                        // $vat +=  ($product->vat * $orderItem['qty']);
                    }
                }
                $set('total_price', $total_price);
                // $set('discount', $discount);
                // $set('vat', $vat);
            })
            // ->collapsible()
            // ->collapsed(fn($record) => $record)
            ->extraItemActions([
                // use Filament\Forms\Components\Actions\Action;
                Forms\Components\Actions\Action::make('openProduct')
                    ->label(__('shop/order.open_product'))
                    ->tooltip(__('shop/order.open_product'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->orderColumn('sort')
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md' => 12,
            ])
            ->required()
            // ->reorderableWithButtons()
            ->collapsible()
            ->itemLabel(fn(array $state): ?string => Product::find($state['product_id'])?->common_title ? __('shop/order.title_popular_label') . Product::find($state['product_id'])?->common_title : null);
    }
}
