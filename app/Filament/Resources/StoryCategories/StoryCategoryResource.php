<?php

namespace App\Filament\Resources\StoryCategories;

use App\Filament\Resources\StoryCategories\Pages\CreateStoryCategory;
use App\Filament\Resources\StoryCategories\Pages\EditStoryCategory;
use App\Filament\Resources\StoryCategories\Pages\ListStoryCategories;
use App\Filament\Resources\StoryCategories\Schemas\StoryCategoryForm;
use App\Filament\Resources\StoryCategories\Tables\StoryCategoriesTable;
use App\Filament\Support\AdminOnly;
use App\Models\StoryCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StoryCategoryResource extends Resource
{
    use AdminOnly;

    protected static ?string $model = StoryCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Setup';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Story categories';

    protected static ?string $modelLabel = 'story category';

    protected static ?string $pluralModelLabel = 'story categories';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return StoryCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoryCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoryCategories::route('/'),
            'create' => CreateStoryCategory::route('/create'),
            'edit' => EditStoryCategory::route('/{record}/edit'),
        ];
    }
}
