<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\BrandResource\Pages;
// use App\Filament\Resources\BrandResource\RelationManagers;
// use App\Models\Brand;
// use Filament\Forms;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

// class BrandResource extends Resource
// {
//     protected static ?string $navigationGroup = 'Shops';
//     // protected static ?string $navigationParentItem = 'Products';

//     protected static ?string $model = Brand::class;

//     protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

//     // protected static ?string $activeNavigationIcon = 'heroicon-o-puzzle-piece';

//     protected static ?int $navigationSort = 1;


//     public static function getNavigationBadgeTooltip(): ?string
//     {
//         return 'The number of brands';
//     }
//     public static function getNavigationBadge(): ?string
//     {
//         return static::getModel()::count();
//     }
//     public static function getNavigationLabel(): string
//     {
//         return 'Brand';
//     }
//     protected static ?string $recordTitleAttribute = 'name';


//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Forms\Components\TextInput::make('name')
//                     ->required()
//                     ->maxLength(255),
//                 Forms\Components\TextInput::make('slug')
//                     ->required()
//                     ->maxLength(255),
//                 Forms\Components\TextInput::make('website')
//                     ->maxLength(255)
//                     ->default(null),
//                 Forms\Components\Textarea::make('description')
//                     ->columnSpanFull(),
//                 Forms\Components\TextInput::make('position')
//                     ->required()
//                     ->numeric()
//                     ->default(0),
//                 Forms\Components\Toggle::make('is_visible')
//                     ->required(),
//                 Forms\Components\TextInput::make('seo_title')
//                     ->maxLength(60)
//                     ->default(null),
//                 Forms\Components\TextInput::make('seo_description')
//                     ->maxLength(160)
//                     ->default(null),
//                 Forms\Components\TextInput::make('sort')
//                     ->numeric()
//                     ->default(null),
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 Tables\Columns\TextColumn::make('name')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('slug')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('website')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('position')
//                     ->numeric()
//                     ->sortable(),
//                 Tables\Columns\IconColumn::make('is_visible')
//                     ->boolean(),
//                 Tables\Columns\TextColumn::make('seo_title')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('seo_description')
//                     ->searchable(),
//                 Tables\Columns\TextColumn::make('sort')
//                     ->numeric()
//                     ->sortable(),
//                 Tables\Columns\TextColumn::make('created_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//                 Tables\Columns\TextColumn::make('updated_at')
//                     ->dateTime()
//                     ->sortable()
//                     ->toggleable(isToggledHiddenByDefault: true),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                 ]),
//             ]);
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
//             'index' => Pages\ListBrands::route('/'),
//             'create' => Pages\CreateBrand::route('/create'),
//             'edit' => Pages\EditBrand::route('/{record}/edit'),
//         ];
//     }
// }
