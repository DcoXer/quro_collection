<?php

namespace App\Filament\Resources\FeaturedProducts;

use App\Filament\Resources\FeaturedProducts\Pages\CreateFeaturedProduct;
use App\Filament\Resources\FeaturedProducts\Pages\EditFeaturedProduct;
use App\Filament\Resources\FeaturedProducts\Pages\ListFeaturedProducts;
use App\Filament\Resources\FeaturedProducts\Schemas\FeaturedProductForm;
use App\Filament\Resources\FeaturedProducts\Tables\FeaturedProductsTable;
use App\Models\FeaturedProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeaturedProductResource extends Resource
{
    protected static ?string $model = FeaturedProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Produk Unggulan';

    protected static ?string $modelLabel = 'Produk Unggulan';

    protected static ?string $pluralModelLabel = 'Produk Unggulan';

    protected static \UnitEnum|string|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return FeaturedProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeaturedProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFeaturedProducts::route('/'),
            'create' => CreateFeaturedProduct::route('/create'),
            'edit'   => EditFeaturedProduct::route('/{record}/edit'),
        ];
    }
}
