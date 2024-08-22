<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

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
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('shop/order.order_customer_info'))
                            ->icon('heroicon-o-user')
                            ->schema(static::getDetailsFormSchema())
                            ->columns(6),

                        Forms\Components\Section::make(__('shop/order.cart'))
                            // ->icon('heroicon-m-squares-2x2')
                            ->description(__('shop/order.order_items_help_text'))
                            ->headerActions([
                                Action::make('reset')->label(__('shop/order.reset'))
                                    ->modalHeading(__('shop/order.are_you_sure'))
                                    ->modalDescription(__('shop/order.modal_description_reset'))
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn(Forms\Set $set) => $set('items', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater(),
                            ]),
                    ])
                    ->columnSpan(['lg' => fn(?Order $record) => $record === null ? 2 : 2]),

                Forms\Components\Group::make()
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
                                    ->default(0),
                            ])
                            ->columns(1),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label(__('shop/order.created_at'))
                                    ->content(fn(?Order $record): ?string => $record->created_at?->diffForHumans()),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label(__('shop/order.updated_at'))
                                    ->content(fn(?Order $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->hidden(fn(?Order $record) => $record === null)
                            ->columns(2),
                        Forms\Components\Section::make()->schema([
                            TextArea::make('notes')->label(__('shop/order.note'))
                                ->columnSpan('full'),
                        ])
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label(__('shop/order.order_number'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label(__('shop/order.customer_name'))
                    ->searchable(isIndividual: true)
                    ->sortable(),

                // Tables\Columns\TextColumn::make('items.product.product_title')
                //     ->label(__('shop/order.title_product'))
                //     ->sortable()
                //     ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('customer_status')
                    ->label(__('shop/order.status')),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('shop/order.total'))
                    ->money('vnd')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
                Tables\Columns\TextColumn::make('customer_status')
                    ->label(__('shop/order.status'))
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'success',
                        'old' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('shop/order.created_by'))
                    ->description(function (Order $record) {
                        // $msg = __('shop/order.created_by') . ': ' . $record->user->name . ' (' . $record->user->email . ')';
                        // $msg = __('shop/order.created_at_description') . ': ' . $record->created_at->diffForHumans() . ' (' . $record->created_at->format('d-m-Y H:i:s') . ')';
                        $msg = __('shop/order.created_at_description') . ': ' . $record->created_at->format('d-m-Y H:i') . ' (' . $record->created_at->diffForHumans() . ')';
                        return $msg;
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shop/order.created_at'))
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shop/order.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth(MaxWidth::SevenExtraLarge)->modal(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('shop/order.no_orders_yet'))
            ->emptyStateDescription(__('shop/order.no_orders_yet_description'))
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label(__('shop/order.create_order'))
                    ->url(static::resourceUrl('create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            // ->defaultSort('created_at', 'desc')
            // ->defaultGroup('customer.name')
        ;
    }

    public static function resourceUrl($path = ''): string
    {
        return 'orders' . ($path ? '/' . $path : $path);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function getDetailsFormSchema(): array
    {
        return [
            // Forms\Components\TextInput::make('number')
            //     ->label(__('shop/order.order_number'))
            //     ->default('TL-' . random_int(100000, 999999))
            //     ->disabled()
            //     ->dehydrated()
            //     ->required()
            //     ->maxLength(32)
            //     ->unique(Order::class, 'number', ignoreRecord: true)->columnSpan(6),

            // Forms\Components\TextInput::make('uuid')
            //     // ->disabled(fn(Order $order) => $order->exists)
            //     ->label(__('shop/order.order_number'))
            //     ->default(fn() => 'TL' . '-' . \Illuminate\Support\Str::random(8))
            //     ->required()
            //     ->columnSpanFull()
            //     ->maxLength(16)
            //     ->disabled()
            //     ->dehydrated()
            //     ->unique(Order::class, 'uuid', ignoreRecord: true)
            //     ->suffixAction(
            //         Forms\Components\Actions\Action::make('copy')
            //             ->icon('heroicon-s-clipboard-document-check')
            //             ->action(function ($livewire, $state) {
            //                 $livewire->js(
            //                     'window.navigator.clipboard.writeText("' . $state . '");
            //         $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
            //                 );
            //             })
            //     ),

            Forms\Components\Select::make('customer_id')
                ->label(__('shop/order.customer_phone'))
                ->relationship('customer', 'phone')
                ->preload()
                ->searchable()
                ->required()
                ->live()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label(__('shop/order.customer_name'))
                        ->required()
                        ->characterLimit(255)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('copy')
                                ->icon('heroicon-s-clipboard-document-check')
                                ->action(function ($livewire, $state) {
                                    $livewire->js(
                                        'window.navigator.clipboard.writeText("' . $state . '");
                    $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
                                    );
                                })
                        ),
                    TextInput::make('phone')
                        ->label(__('shop/order.phone'))
                        ->required()
                        ->characterLimit(15)
                        ->suffixAction(
                            Forms\Components\Actions\Action::make('copy')
                                ->icon('heroicon-s-clipboard-document-check')
                                ->action(function ($livewire, $state) {
                                    $livewire->js(
                                        'window.navigator.clipboard.writeText("' . $state . '");
                    $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
                                    );
                                })
                        ),

                    Forms\Components\Select::make('gender')
                        ->label(__('shop/order.gender'))
                        ->placeholder(__('shop/order.select_gender'))
                        ->options([
                            'male' => __('shop/order.male'),
                            'female' => __('shop/order.female'),
                        ])
                        ->required()
                        ->native(false),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading(__('shop/order.create_customer'))
                        ->modalSubmitActionLabel(__('shop/order.create_customer'))
                        ->modalWidth('lg');
                })
                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                    // $set('phone', Customer::find($state)?->name);
                    // set hint content foreach customer
                    // $set('customer.phone', Customer::find($state)?->phone);
                    $set('dynamic_hint', Customer::find($state)?->name);
                })
                // ->prefixAction(
                //     Forms\Components\Actions\Action::make('create')
                //         ->icon('heroicon-s-plus')
                //         ->label(__('shop/order.create_customer'))
                //         ->url(CustomerResource::getUrl('create'))
                //         ->button()
                // )
                ->suffixAction(
                    Forms\Components\Actions\Action::make('copy')
                        ->icon('heroicon-s-clipboard-document-check')
                        ->action(function ($livewire, $state) {
                            $name = Customer::find($state)?->name;
                            $livewire->js(
                                'window.navigator.clipboard.writeText("' . $name . '");
                    $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
                            );
                        })
                )
                ->columnSpan('full'),
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
            // Forms\Components\TextInput::make('customer.phone')
            //     ->label(__('shop/order.customer_phone'))
            //     // ->required()
            //     ->readOnly()
            //     ->suffixAction(
            //         Forms\Components\Actions\Action::make('copy')
            //             ->icon('heroicon-s-clipboard-document-check')
            //             ->action(function ($livewire, $state) {
            //                 $livewire->js(
            //                     'window.navigator.clipboard.writeText("' . $state . '");
            //         $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
            //                 );
            //             })
            //     )
            //     ->columnSpan(3),
            // Forms\Components\ToggleButtons::make('customer_status')
            //     ->label(__('shop/order.customer_order_status_label'))
            //     ->inline()
            //     ->options(OrderStatus::class)
            //     ->default(OrderStatus::New)
            //     ->required()->columnSpan(3),

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
                        $set('price', Product::find($state)?->sell_price ?? 0);
                        $set('total', 1 * $get('price'));
                    })
                    ->distinct()
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 6,
                    ])
                    ->searchable()
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
                    ->reactive()
                    ->required()
                    // ->afterStateUpdated(fn($state, Forms\Set $set) => $set('total', Product::find($state)?->price * $state['quantity'] ?? 0))
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                        // dd($get('quantity'));
                        $set('total', $get('quantity') * $get('price'));
                    }),

                Forms\Components\TextInput::make('price')
                    ->label(__('shop/order.unit_price'))
                    // ->disabled()
                    ->readOnly()
                    // ->dehydrated()
                    ->numeric()
                    // ->reactive()
                    ->required()
                    ->columnSpan([
                        'md' => 2,
                    ]),
                Forms\Components\TextInput::make('total')
                    ->label(__('shop/order.total_single_product'))
                    // ->disabled()
                    ->reactive()
                    ->readOnly()
                    ->numeric()
                    ->required()
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

                        // $total += ((($product->price + $product->vat) - $getDiscount) * $orderItem['qty']);
                        $total_price += ($product->sell_price * $orderItem['quantity']);
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
                Action::make('openProduct')
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

    // ->headerActions([
                            Forms\Components\Actions\Action::make('reset')
                                ->label(__('shop/order.add_new_user'))
                                // ->modalHeading(__('shop/order.are_you_sure'))
                                // ->modalDescription(__('shop/order.modal_description_reset'))
                                ->disabled(fn(Order $order) => $order->exists)
                                ->requiresConfirmation()
                                ->color('success')
                                // ->modalHidden(fn(): bool => $this->role !== 'admin')
                                ->requiresConfirmation(false)

                                ->action(function (Forms\Set $set, Forms\Get $get) {
                                    Customer::create([
                                        'name' => $get('customer_name'),
                                        'phone' => $get('customer_phone')[0],
                                    ]);
                                }),
                        ])
}
