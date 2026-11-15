<?php

namespace App\Filament\Resources\PartnerCategories;

use App\Filament\Resources\PartnerCategories\Pages\CreatePartnerCategory;
use App\Filament\Resources\PartnerCategories\Pages\EditPartnerCategory;
use App\Filament\Resources\PartnerCategories\Pages\ListPartnerCategories;
use App\Filament\Resources\PartnerCategories\Schemas\PartnerCategoryForm;
use App\Filament\Resources\PartnerCategories\Tables\PartnerCategoriesTable;
use App\Filament\Support\AdminOnly;
use App\Models\PartnerCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartnerCategoryResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = PartnerCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Setup';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Partner categories';

    protected static ?string $modelLabel = 'partner category';

    protected static ?string $pluralModelLabel = 'partner categories';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PartnerCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartnerCategories::route('/'),
            'create' => CreatePartnerCategory::route('/create'),
            'edit' => EditPartnerCategory::route('/{record}/edit'),
        ];
    }
}
