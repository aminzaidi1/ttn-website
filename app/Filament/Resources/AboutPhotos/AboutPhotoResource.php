<?php

namespace App\Filament\Resources\AboutPhotos;

use App\Filament\Resources\AboutPhotos\Pages\CreateAboutPhoto;
use App\Filament\Resources\AboutPhotos\Pages\EditAboutPhoto;
use App\Filament\Resources\AboutPhotos\Pages\ListAboutPhotos;
use App\Filament\Resources\AboutPhotos\Schemas\AboutPhotoForm;
use App\Filament\Resources\AboutPhotos\Tables\AboutPhotosTable;
use App\Models\AboutPhoto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AboutPhotoResource extends Resource
{
    protected static ?string $model = AboutPhoto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AboutPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AboutPhotosTable::configure($table);
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
            'index' => ListAboutPhotos::route('/'),
            'create' => CreateAboutPhoto::route('/create'),
            'edit' => EditAboutPhoto::route('/{record}/edit'),
        ];
    }
}
