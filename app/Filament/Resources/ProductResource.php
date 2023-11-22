<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\CategoriesRelationManager;
use App\Models\Product;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'products';

    public static function form(Form $form): Form
    {
        return $form->schema([
            SelectTree::make('categories')
                ->relationship('categories', 'name', 'parent_id'),

            Select::make('brand_id')
                ->relationship('brand', 'name')
                ->searchable()
                ->required(),

            TextInput::make('sku')
                ->required(),

            TextInput::make('slug'),

            TextInput::make('quantity')
                ->required()
                ->integer(),

            TextInput::make('price')
                ->required()
                ->numeric(),

            TextInput::make('sale_price')
                ->required()
                ->numeric(),

            Checkbox::make('active'),

            Checkbox::make('featured'),

            Placeholder::make('created_at')
                ->label('Created Date')
                ->content(fn(?Product $record): string => $record?->created_at?->diffForHumans() ?? '-'),

            Placeholder::make('updated_at')
                ->label('Last Modified Date')
                ->content(fn(?Product $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

            SpatieMediaLibraryFileUpload::make('media')
                ->multiple()
                ->enableReordering()
                ->customProperties(['zip_filename_prefix' => 'products/'])
            ->statePath('products')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
//            TextColumn::make('brand.name')
//                ->searchable()
//                ->sortable(),

            TextColumn::make('sku'),

            TextColumn::make('name')
                ->searchable()
                ->sortable(),

//            TextColumn::make('slug')
//                ->searchable()
//                ->sortable(),

            TextColumn::make('quantity'),

            TextColumn::make('price'),

            TextColumn::make('sale_price'),

            CheckboxColumn::make('active'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'brand.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->brand) {
            $details['Brand'] = $record->brand->name;
        }

        return $details;
    }
}
