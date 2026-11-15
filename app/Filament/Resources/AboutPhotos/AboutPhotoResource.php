<?php

namespace App\Filament\Resources\AboutPhotos;

use App\Filament\Resources\AboutPhotos\Pages\CreateAboutPhoto;
use App\Filament\Resources\AboutPhotos\Pages\EditAboutPhoto;
use App\Filament\Resources\AboutPhotos\Pages\ListAboutPhotos;
use App\Filament\Resources\AboutPhotos\Schemas\AboutPhotoForm;
use App\Filament\Resources\AboutPhotos\Tables\AboutPhotosTable;
use App\Filament\Support\SentenceCaseLabels;
use App\Models\AboutPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AboutPhotoResource extends Resource
{
    use SentenceCaseLabels;

    protected static ?string $model = AboutPhoto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Library';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'About page photos';

    protected static ?string $modelLabel = 'about page photo';

    protected static ?string $pluralModelLabel = 'about page photos';

    public static function form(Schema $schema): Schema
    {
        return AboutPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AboutPhotosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAboutPhotos::route('/'),
            'create' => CreateAboutPhoto::route('/create'),
            'edit' => EditAboutPhoto::route('/{record}/edit'),
        ];
    }
}
