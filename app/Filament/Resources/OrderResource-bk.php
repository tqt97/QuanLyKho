<?php

// namespace App\Filament\Resources;

// use App\Enums\OrderStatus;
// use App\Filament\Resources\OrderResource\Pages;
// use App\Filament\Resources\OrderResource\RelationManagers;
// use App\Models\Order;
// use App\Models\Product;
// use Filament\Forms;
// use Filament\Forms\Components\Actions\Action;
// use Filament\Forms\Components\Repeater;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

// class OrderResource extends Resource
// {
//     protected static ?string $navigationGroup = 'Cửa hàng';

//     protected static ?string $model = Order::class;

//     protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

//     protected static ?int $navigationSort = 4;


//     public static function getNavigationBadgeTooltip(): ?string
//     {
//         return __('shop/order.number_of_orders');
//     }
//     public static function getNavigationBadge(): ?string
//     {
//         return static::getModel()::count();
//     }

//     public static function getNavigationLabel(): string
//     {
//         return __('shop/order.order');
//     }

//     public static function getModelLabel(): string
//     {
//         return __('shop/order.order');
//     }
//     protected static ?string $recordTitleAttribute = 'number';


//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\Group::make()
//                     ->schema([
//                         Forms\Components\Section::make()
//                             ->schema(static::getDetailsFormSchema())
//                             ->columns(2),

//                         Forms\Components\Section::make(__('shop/order.order_items'))
//                             ->headerActions([
//                                 Action::make('reset')->label(__('shop/order.reset'))
//                                     ->modalHeading(__('shop/order.are_you_sure'))
//                                     ->modalDescription(__('shop/order.modal_description_reset'))
//                                     ->requiresConfirmation()
//                                     ->color('danger')
//                                     ->action(fn(Forms\Set $set) => $set('items', [])),
//                             ])
//                             ->schema([
//                                 static::getItemsRepeater(),
//                             ]),
//                     ])
//                     ->columnSpan(['lg' => fn(?Order $record) => $record === null ? 3 : 2]),

//                 Forms\Components\Section::make()
//                     ->schema([
//                         Forms\Components\Placeholder::make('created_at')
//                             ->label(__('shop/order.created_at'))
//                             ->content(fn(Order $record): ?string => $record->created_at?->diffForHumans()),

//                         Forms\Components\Placeholder::make('updated_at')
//                             ->label(__('shop/order.updated_at'))
//                             ->content(fn(Order $record): ?string => $record->updated_at?->diffForHumans()),
//                     ])
//                     ->columnSpan(['lg' => 1])
//                     ->hidden(fn(?Order $record) => $record === null),
//             ])->columns(3);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('number')
//                     ->label(__('shop/order.order_number'))
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('customer.name')
//                     ->label(__('shop/order.customer_name'))
//                     ->numeric()
//                     ->sortable(),

//                 Tables\Columns\TextColumn::make('items.product.title_product')
//                     ->label(__('shop/order.title_product'))
//                     ->sortable(),
//                 // Tables\Columns\TextColumn::make('items.product.unit_price')
//                 //     // ->money('usd')
//                 //     ->getStateUsing(function (Order $record) {
//                 //         return $record->unit_price;
//                 //     })
//                 //     ->summarize(Tables\Columns\Summarizers\Sum::make()),
//                 Tables\Columns\TextColumn::make('customer_status')
//                     ->label(__('shop/order.status')),
//                 Tables\Columns\TextColumn::make('deleted_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//                 Tables\Columns\TextColumn::make('created_at')
//                     ->label(__('shop/order.created_at'))
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//                 Tables\Columns\TextColumn::make('updated_at')
//                     ->label(__('shop/order.updated_at'))
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->filters([
//                 Tables\Filters\TrashedFilter::make(),
//             ])
//             ->actions([
//                 Tables\Actions\ViewAction::make(),
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                     Tables\Actions\ForceDeleteBulkAction::make(),
//                     Tables\Actions\RestoreBulkAction::make(),
//                 ]),
//             ])
//             ->emptyStateHeading(__('shop/order.no_orders_yet'))
//             ->emptyStateDescription(__('shop/order.no_orders_yet_description'))
//             ->emptyStateIcon('heroicon-o-shopping-bag')
//             ->emptyStateActions([
//                 Tables\Actions\Action::make('create')
//                     ->label(__('shop/order.create_order'))
//                     ->url(static::resourceUrl('create'))
//                     ->icon('heroicon-m-plus')
//                     ->button(),
//             ])
//             // ->defaultSort('created_at', 'desc')
//             ->defaultGroup('customer.name')
//         ;
//     }

//     public static function resourceUrl($path = ''): string
//     {
//         return 'orders' . ($path ? '/' . $path : $path);
//     }
//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListOrders::route('/'),
//             'create' => Pages\CreateOrder::route('/create'),
//             'edit' => Pages\EditOrder::route('/{record}/edit'),
//         ];
//     }

//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()
//             ->withoutGlobalScopes([
//                 SoftDeletingScope::class,
//             ]);
//     }

//     public static function getDetailsFormSchema(): array
//     {
//         return [
//             Forms\Components\TextInput::make('number')
//                 ->label(__('shop/order.order_number'))
//                 ->default('TL-' . random_int(100000, 999999))
//                 ->disabled()
//                 ->dehydrated()
//                 ->required()
//                 ->maxLength(32)
//                 ->unique(Order::class, 'number', ignoreRecord: true),

//             Forms\Components\Select::make('customer_id')
//                 ->label(__('shop/order.customer_name'))
//                 ->relationship('customer', 'name')
//                 ->preload()
//                 ->searchable()
//                 ->required()
//                 ->createOptionForm([
//                     Forms\Components\TextInput::make('name')
//                         ->label(__('shop/order.customer_name'))
//                         ->required()
//                         ->maxLength(255),
//                     Forms\Components\TextInput::make('phone')
//                         ->label(__('shop/order.phone'))
//                         ->maxLength(255),

//                     Forms\Components\Select::make('gender')
//                         ->label(__('shop/order.gender'))
//                         ->placeholder('Select gender')
//                         ->options([
//                             'male' => __('shop/order.male'),
//                             'female' => __('shop/order.female'),
//                         ])
//                         ->required()
//                         ->native(false),
//                 ])
//                 ->createOptionAction(function (Action $action) {
//                     return $action
//                         ->modalHeading(__('shop/order.create_customer'))
//                         ->modalSubmitActionLabel(__('shop/order.create_customer'))
//                         ->modalWidth('lg');
//                 }),

//             Forms\Components\ToggleButtons::make('customer_status')->label(__('shop/order.customer_order_status_label'))
//                 ->inline()
//                 ->options(OrderStatus::class)
//                 ->required(),
//             Forms\Components\MarkdownEditor::make('note')->label(__('shop/order.note'))
//                 ->columnSpan('full'),
//         ];
//     }

//     public static function getItemsRepeater(): Repeater
//     {
//         return Repeater::make('items')->label(__('shop/order.order'))
//             ->relationship()
//             ->schema([
//                 Forms\Components\Select::make('product_id')
//                     ->label(__('shop/order.title_product'))
//                     ->options(Product::query()->pluck('title_product', 'id'))
//                     ->required()
//                     ->reactive()
//                     ->afterStateUpdated(fn($state, Forms\Set $set) => $set('unit_price', Product::find($state)?->price ?? 0))
//                     ->distinct()
//                     ->disableOptionsWhenSelectedInSiblingRepeaterItems()
//                     ->columnSpan([
//                         'md' => 5,
//                     ])
//                     ->searchable(),

//                 Forms\Components\TextInput::make('quantity')
//                     ->label(__('shop/order.quantity'))
//                     ->numeric()
//                     ->default(1)
//                     ->columnSpan([
//                         'md' => 2,
//                     ])
//                     ->required(),

//                 Forms\Components\TextInput::make('unit_price')
//                     ->label(__('shop/order.unit_price'))
//                     ->disabled()
//                     ->dehydrated()
//                     ->numeric()
//                     ->required()
//                     ->columnSpan([
//                         'md' => 3,
//                     ]),
//             ])
//             ->extraItemActions([
//                 Action::make('openProduct')
//                     ->label(__('shop/order.open_product'))
//                     ->tooltip(__('shop/order.open_product'))
//                     ->icon('heroicon-m-arrow-top-right-on-square')
//                     ->url(function (array $arguments, Repeater $component): ?string {
//                         $itemData = $component->getRawItemState($arguments['item']);

//                         $product = Product::find($itemData['product_id']);

//                         if (! $product) {
//                             return null;
//                         }

//                         return ProductResource::getUrl('edit', ['record' => $product]);
//                     }, shouldOpenInNewTab: true)
//                     ->hidden(fn(array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
//             ])
//             ->orderColumn('sort')
//             ->defaultItems(1)
//             ->hiddenLabel()
//             ->columns([
//                 'md' => 10,
//             ])
//             ->required()
//             ->reorderableWithButtons()
//             ->collapsible()
//             ->itemLabel(fn(array $state): ?string => Product::find($state['product_id'])?->title_popular ? __('shop/order.title_popular_label') . Product::find($state['product_id'])?->title_popular : null); //Product::find($state)?->price ?? 0
//     }
// }
