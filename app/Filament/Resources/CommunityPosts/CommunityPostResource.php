<?php

namespace App\Filament\Resources\CommunityPosts;

use App\Filament\Resources\CommunityPosts\Pages\CreateCommunityPost;
use App\Filament\Resources\CommunityPosts\Pages\EditCommunityPost;
use App\Filament\Resources\CommunityPosts\Pages\ListCommunityPosts;
use App\Filament\Resources\CommunityPosts\Schemas\CommunityPostForm;
use App\Filament\Resources\CommunityPosts\Tables\CommunityPostsTable;
use App\Filament\Support\SentenceCaseLabels;
use App\Models\CommunityPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CommunityPostResource extends Resource
{
    use SentenceCaseLabels;

    protected static ?string $model = CommunityPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Community posts';

    protected static ?string $modelLabel = 'community post';

    protected static ?string $pluralModelLabel = 'community posts';

    protected static ?string $recordTitleAttribute = 'member_name';

    public static function form(Schema $schema): Schema
    {
        return CommunityPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommunityPostsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommunityPosts::route('/'),
            'create' => CreateCommunityPost::route('/create'),
            'edit' => EditCommunityPost::route('/{record}/edit'),
        ];
    }
}
