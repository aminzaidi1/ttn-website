<?php

namespace App\Filament\Resources\NavItems\Schemas;

use App\Filament\Support\Copy;
use App\Models\NavItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NavItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->columns(2)->schema([
                Select::make('location')->label('Where it appears')
                    ->options(NavItem::LOCATIONS)->required()->native(false),
                Copy::text('label', 'Link text')->required()->maxLength(255),
                TextInput::make('url')->label('Goes to')->required()->maxLength(255)
                    ->regex('#^(/|https?://)#')
                    ->validationMessages(['regex' => 'Start with / for a page on this site, or https:// for another site.'])
                    ->helperText('A path on this site like /stories, or a full https:// link'),
                TextInput::make('order')->label('Position')->numeric()->default(0)
                    ->helperText('Lower numbers come first. You can also drag rows in the list.'),
                Toggle::make('is_visible')->label('Visible')->default(true)
                    ->helperText('Turn off to hide the link without deleting it.'),
            ]),
        ]);
    }
}
