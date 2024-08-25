<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class ProductResource extends Resource
{
    protected static ?string $navigationGroup = 'Cửa hàng';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadgeTooltip(): ?string
    {
        return __('shop/product.product_count_numbers');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'common_title';

    public static function getNavigationLabel(): string
    {
        return __('shop/product.product');
    }

    public static function getModelLabel(): string
    {
        return __('shop/product.product');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            // ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('product_title')
                                    ->label(__('shop/product.title_product'))
                                    ->hint(__('shop/product.title_product_helper'))
                                    ->required()
                                    ->characterLimit(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    })
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('copy')
                                            ->icon('heroicon-s-clipboard-document-check')
                                            ->action(function ($livewire, $state) {
                                                $livewire->js(
                                                    'window.navigator.clipboard.writeText("'.$state.'");
                    $tooltip("'.__('Copied to clipboard').'", { timeout: 1500 });'
                                                );
                                            })
                                    )
                                    ->columnSpan('full'),

                                TextInput::make('common_title')
                                    ->label(__('shop/product.title_popular'))
                                    ->hint(__('shop/product.title_popular_helper'))
                                    ->required()
                                    ->maxLength(255)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('copy')
                                            ->icon('heroicon-s-clipboard-document-check')
                                            ->action(function ($livewire, $state) {
                                                $livewire->js(
                                                    'window.navigator.clipboard.writeText("'.$state.'");
                                    $tooltip("'.__('Copied to clipboard').'", { timeout: 1500 });'
                                                );
                                            })
                                    )
                                    ->columnSpan(1),
                                TextInput::make('slug')
                                    ->label(__('shop/product.slug'))
                                    ->hint(__('shop/product.slug_helper'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Product::class, 'slug', ignoreRecord: true)->columnSpan(1),
                                //             TextInput::make('sell_title')
                                //                 ->label(__('shop/product.title_sell'))
                                //                 ->helperText(__('shop/product.title_sell_helper'))
                                //                 ->required()
                                //                 ->maxLength(255)
                                //                 ->suffixAction(
                                //                     Forms\Components\Actions\Action::make('copy')
                                //                         ->icon('heroicon-s-clipboard-document-check')
                                //                         ->action(function ($livewire, $state) {
                                //                             $livewire->js(
                                //                                 'window.navigator.clipboard.writeText("' . $state . '");
                                // $tooltip("' . __('Copied to clipboard') . '", { timeout: 1500 });'
                                //                             );
                                //                         })
                                //                 )
                                //                 ->columnSpan('full'),
                                TextArea::make('dosage')
                                    ->label(__('shop/product.dosage'))
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),

                                TextArea::make('description')
                                    ->label(__('shop/product.description'))
                                    ->rows(5)
                                    // ->cols(5)
                                    ->columnSpan('full'),
                            ])->columns(2),
                        // Forms\Components\Tabs\Tab::make('SEO')
                        //     ->icon('heroicon-o-tag')
                        //     ->schema([
                        //         TextInput::make('seo_title')
                        //             ->label(__('shop/product.seo_title'))
                        //             ->columnSpan('full'),
                        //         Forms\Components\Textarea::make('seo_description')
                        //             ->label(__('shop/product.seo_description'))
                        //             ->columnSpan('full'),
                        //     ])
                        //     ->columns(2),
                    ])

                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            // ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label(__('shop/product.image'))
                                    ->directory('products')
                                    ->preserveFilenames()
                                    ->imageEditor()
                                    // ->fetchFileInformation(false)
                                    ->optimize('webp')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('9:10')
                                    ->imageResizeTargetWidth('540')
                                    ->imageResizeTargetHeight('600')
                                    // ->panelLayout('grid')
                                    // ->resize(50)
                                    ->image(),
                                // Forms\Components\Toggle::make('is_visible')
                                //     ->label(__('shop/product.is_visible'))
                                //     ->helperText(__('shop/product.is_visible_helper'))
                                //     ->default(true),
                                TextInput::make('original_price')
                                    ->label(__('shop/product.original_price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    // ->suffix('vnd')
                                    ->suffix('₫')
                                    ->columnSpan(1),

                                TextInput::make('sell_price')
                                    ->label(__('shop/product.sell_price'))
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->suffix('₫')
                                    // ->suffix('vnd')
                                    ->required()->columnSpan(1),
                                TextInput::make('qty_per_product')
                                    ->label(__('shop/product.quantity_per_pack'))
                                    ->hint(__('shop/product.quantity_per_pack_helper'))
                                    ->characterLimit(255)
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('expiry_date')
                                    ->label(__('shop/product.expiry_date'))
                                    ->suffixIcon('heroicon-m-calendar')
                                    // ->format('d/m/Y')
                                    // ->displayFormat('d/m/Y')
                                    // ->required()
                                    // ->locale('vi')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('shop/product.image'))
                    ->alignCenter()
                    ->square(),
                Tables\Columns\TextColumn::make('common_title')
                    ->label(__('shop/product.title_popular'))
                    // ->description(fn(Product $record): string => $record->dosage . ' - ' . $record->qty_per_product)
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_title')
                    ->label(__('shop/product.title_product'))
                    ->description(fn (Product $record): string => 'Số lượng: '.$record->qty_per_product.' - Liều dùng: '.$record->dosage)
                    // ->limit(50)
                    ->wrap()
                    ->copyable()
                    ->searchable(isIndividual: true)
                    ->words(10)
                    ->wrap()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('title_sell')
                //     ->label(__('shop/product.title_product'))
                //     ->searchable(isIndividual: true)
                //     ->sortable(),
                Tables\Columns\TextColumn::make('sell_price')
                    ->label(__('shop/product.sell_price'))
                    ->money('vnd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label(__('shop/product.expiry_date'))
                    // ->date('m/Y')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('qty_per_product')
                //     ->label(__('shop/product.quantity_per_pack'))
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('original_price')
                //     ->label(__('shop/product.price'))
                //     ->money('vnd')
                //     ->sortable(),

                // Tables\Columns\TextColumn::make('quantity')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\IconColumn::make('is_active')
                //     ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('shop/product.deleted_at'))
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shop/product.created_at'))
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shop/product.updated_at'))
                    ->dateTime('d-m-Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(
                [
                    QueryBuilder::make()
                        ->constraints([
                            TextConstraint::make('title_popular')->label(__('shop/product.title_popular'))->icon('heroicon-o-cube'),
                            TextConstraint::make('title_product')->label(__('shop/product.title_product'))->icon('heroicon-o-cube'),
                            NumberConstraint::make('price')->label(__('shop/product.price'))->icon('heroicon-o-currency-dollar'),
                        ]),

                ],
                // layout: Tables\Enums\FiltersLayout::AboveContent
            )
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge)->modal(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(Tables\Actions\ViewAction::class)
            ->defaultSort('created_at', 'desc');
        // ->defaultGroup('title_popular')
        // ->recordUrl(
        //     fn(Model $record): string => Pages\ViewProduct::getUrl([$record->id]),
        // )
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['product_title', 'common_title'];
    }
}
