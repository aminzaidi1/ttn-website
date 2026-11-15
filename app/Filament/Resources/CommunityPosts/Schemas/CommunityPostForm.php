<?php

namespace App\Filament\Resources\CommunityPosts\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CommunityPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('The post')->columnSpanFull()->columns(2)->schema([
                TextInput::make('facebook_url')->label('Facebook link')
                    ->url()->required()->maxLength(500)->columnSpanFull()
                    ->placeholder('https://www.facebook.com/groups/...')
                    ->helperText('Paste the link to the original Facebook post'),
                Copy::area('quote', 'Quote', 4)->required()->columnSpanFull()
                    ->helperText('The member\'s words, trimmed if needed but never reworded.'),
                Copy::text('member_name', 'Member name')->required()->maxLength(255),
                DatePicker::make('posted_on')->label('Posted on'),
                PhotoPicker::make('photo_id', 'Photo')->helperText('Optional.'),
                Select::make('destination_id')->label('Destination')
                    ->relationship('destination', 'name')->searchable()->preload()
                    ->helperText('Shows this post as a community tip on that destination'),
            ]),
            Section::make('Permission and publishing')->columnSpanFull()->columns(2)->schema([
                Toggle::make('permission_confirmed')->label('Permission confirmed')->live()
                    ->helperText('Tick only when the member has agreed to be featured.'),
                Toggle::make('is_published')->label('Published')
                    ->rule('declined', fn (Get $get): bool => ! $get('permission_confirmed'))
                    ->validationMessages(['declined' => 'This post cannot be published until the member has agreed to be featured. Tick "Permission confirmed" first.'])
                    ->helperText('Needs permission confirmed first.'),
                Toggle::make('show_on_home')->label('Show on the homepage'),
                TextInput::make('order')->label('Position in lists')->numeric()->default(0)
                    ->helperText('Lower numbers come first.'),
            ]),
        ]);
    }
}
