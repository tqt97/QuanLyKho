<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Pages\ViewProduct;

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
    protected static ?string $recordTitleAttribute = 'title_popular';

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
                Forms\Components\Tabs::make()->tabs([
                    Forms\Components\Tabs\Tab::make(__('shop/product.main_info'))
                        ->icon('heroicon-o-queue-list')
                        ->schema([

                            Forms\Components\TextInput::make('title_product')
                                ->label(__('shop/product.title_product'))
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                })->columnSpan(1),

                            Forms\Components\TextInput::make('slug')
                                ->label(__('shop/product.slug'))
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->maxLength(255)
                                ->unique(Product::class, 'slug', ignoreRecord: true)->columnSpan(1),

                            Forms\Components\TextInput::make('title_popular')
                                ->label(__('shop/product.title_popular'))
                                ->required()
                                ->maxLength(255)->columnSpan('full'),
                            Forms\Components\TextInput::make('price')
                                ->label(__('shop/product.price'))
                                ->numeric()
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->required()->columnSpan(1),

                            Forms\Components\TextInput::make('dosage')
                                ->label(__('shop/product.dosage'))
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('quantity_per_pack')
                                ->label(__('shop/product.quantity_per_pack'))
                                ->columnSpan(1),
                            Forms\Components\DateTimePicker::make('expiry_date')
                                ->label(__('shop/product.expiry_date'))
                                ->required()->columnSpan(1),
                            Forms\Components\MarkdownEditor::make('description')
                                ->label(__('shop/product.description'))
                                ->columnSpan('full'),
                        ])->columns(2),
                    Forms\Components\Tabs\Tab::make('SEO')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            Forms\Components\TextInput::make('seo_title')
                                ->label(__('shop/product.seo_title'))
                                ->columnSpan('full'),
                            Forms\Components\Textarea::make('seo_description')
                                ->label(__('shop/product.seo_description'))
                                ->columnSpan('full'),
                        ])
                        ->columns(2),
                ])->columnSpan(['lg' => 2]),


                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('shop/product.additional_info'))
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->label(__('shop/product.image')),
                                // Forms\Components\Toggle::make('is_visible')
                                //     ->label(__('shop/product.is_visible'))
                                //     ->helperText(__('shop/product.is_visible_helper'))
                                //     ->default(true),
                            ])->collapsible(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_popular')
                    ->label(__('shop/product.title_popular'))
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('title_product')
                    ->label(__('shop/product.title_product'))
                    ->searchable(isIndividual: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label(__('shop/product.expiry_date'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_per_pack')
                    ->label(__('shop/product.quantity_per_pack'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('shop/product.price'))
                    ->money('vnd')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('quantity')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\IconColumn::make('is_active')
                //     ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('shop/product.deleted_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('shop/product.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shop/product.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('created_from')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('created_until')
                    ->form([
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })->default(now()),
            Tables\Filters\Filter::make('price')
                ->form([
                    Forms\Components\TextInput::make('price'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['price'],
                            fn(Builder $query, $price): Builder => $query->where('price', $price),
                        )
                        ->when(
                            $data['price'],
                            fn(Builder $query, $price): Builder => $query->where('price', '>=', $price),
                        )
                        ->when(
                            $data['price'],
                            fn(Builder $query, $price): Builder => $query->where('price', '<=', $price),
                        );
                }),

                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('title_popular')->label(__('shop/product.title_popular'))->icon('heroicon-o-queue-list'),
                        TextConstraint::make('title_product')->label(__('shop/product.title_product'))->icon('heroicon-o-queue-list'),
                    ])

            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
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
            // ->defaultSort('created_at', 'desc')
            // ->defaultGroup('title_popular')
            // ->recordUrl(
            //     fn(Model $record): string => Pages\ViewProduct::getUrl([$record->id]),
            // )
        ;
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
            // 'view' => Pages\ViewProduct::route('/{record}'),
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
        return ['title_product', 'title_popular'];
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist->schema([
    //         Section::make('Post')
    //             ->schema([
    //                 Fieldset::make('General')
    //                     ->schema([
    //                         TextEntry::make('title_popular'),
    //                         TextEntry::make('title_product'),
    //                         // TextEntry::make('description'),
    //                     ]),
    //                 Fieldset::make('Publish Information')
    //                     ->schema([
    //                         // TextEntry::make('status')
    //                         // ->badge()->color(function ($state) {
    //                         //     return $state->getColor();
    //                     ]),
    //                 Fieldset::make('Description')
    //                     ->schema([
    //                         TextEntry::make('content')
    //                             ->html()
    //                             ->columnSpanFull(),
    //                     ]),
    //             ]),
    //     ]);
    // }
}
