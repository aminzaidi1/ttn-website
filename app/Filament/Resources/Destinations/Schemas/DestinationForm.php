<?php

namespace App\Filament\Resources\Destinations\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\OrderedRelationSelect;
use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class DestinationForm
{
    public const KINDS = ['Places to visit', 'Food to try', 'Viewpoints', 'Treks and walks', 'When not to go', 'Festivals', 'How to behave'];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Destination')->columnSpanFull()->persistTabInQueryString()->tabs([
                Tab::make('Basics')->schema([
                    Grid::make(2)->schema([
                        Copy::text('name', 'Name')->required()->maxLength(255),
                        Select::make('region_id')->label('Region')->relationship('region', 'name')->required()->preload(),
                    ]),
                    Copy::area('blurb', 'Short description', 2)
                        ->helperText('One or two sentences for destination cards.'),
                    Grid::make(3)->schema([
                        Toggle::make('is_published')->label('Published')
                            ->helperText('Off keeps the page as a draft that only editors can see.'),
                        TextInput::make('order')->label('Position in lists')->numeric()->default(0)
                            ->helperText('Lower numbers come first.'),
                        TextInput::make('slug')->label('Slug')->prefix('/destinations/')->maxLength(255)
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->helperText('Made from the name when left empty. Changing it keeps old links working.'),
                    ]),
                ]),
                Tab::make('At a glance')->schema([
                    Section::make()->description('The five facts shown under the hero. Any left empty are simply not shown.')->columns(2)->schema([
                        Copy::text('stat_season', 'Best season')->placeholder('e.g. May to September'),
                        Copy::text('stat_altitude', 'Altitude')->placeholder('e.g. 3,300 m'),
                        Copy::text('stat_drive', 'Drive from Islamabad')->placeholder('e.g. 14 hours'),
                        Copy::text('stat_difficulty', 'Road difficulty')->placeholder('e.g. Jeep only for the last 12 km'),
                        Copy::text('stat_permits', 'Permits')->placeholder('e.g. None needed'),
                    ]),
                ]),
                Tab::make('Text')->schema([
                    Copy::rich('overview', 'Overview')
                        ->helperText('The opening of the page. Bold, italic and links only.'),
                    Copy::area('pull_quote', 'Pull quote', 2)
                        ->helperText('One line set large in the middle of the page.'),
                    Copy::rich('getting_there', 'Getting there'),
                    Copy::rich('where_to_stay', 'Where to stay')
                        ->helperText('Prices belong here, as ranges inside the sentences. There is no price field on purpose.'),
                ]),
                Tab::make('Route legs')->schema([
                    Repeater::make('routeLegs')
                        ->hiddenLabel()
                        ->relationship('routeLegs')
                        ->orderColumn('order')
                        ->reorderable()
                        ->collapsible()
                        ->defaultItems(0)
                        ->addActionLabel('Add a route leg')
                        ->itemLabel(fn (array $state): ?string => filled($state['from_place'] ?? null) ? ($state['from_place'].' to '.($state['to_place'] ?? '')) : null)
                        ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => [...$data, 'type' => 'route_leg'])
                        ->columns(4)
                        ->schema([
                            Copy::text('from_place', 'From')->required()->columnSpan(2),
                            Copy::text('to_place', 'To')->required()->columnSpan(2),
                            Copy::text('distance', 'Distance')->maxLength(60)->placeholder('e.g. 185 km')->columnSpan(2),
                            Copy::text('duration', 'Duration')->maxLength(60)->placeholder('e.g. 5 to 6 hours')->columnSpan(2),
                            Copy::area('body', 'Note', 2)->columnSpanFull(),
                        ]),
                ]),
                Tab::make('What to do')->schema([
                    Repeater::make('blocks')
                        ->hiddenLabel()
                        ->relationship('blocks')
                        ->orderColumn('order')
                        ->reorderable()
                        ->collapsible()
                        ->defaultItems(0)
                        ->addActionLabel('Add a section')
                        ->itemLabel(fn (array $state): ?string => trim(($state['kind'] ?? '').(filled($state['heading'] ?? null) ? ': '.$state['heading'] : '')) ?: null)
                        ->mutateRelationshipDataBeforeCreateUsing(fn (array $data): array => [...$data, 'type' => 'section'])
                        ->columns(2)
                        ->schema([
                            Copy::text('kind', 'Kind')
                                ->datalist(self::KINDS)
                                ->required()
                                ->helperText('Pick a suggestion or type your own.'),
                            Copy::text('heading', 'Heading'),
                            Copy::area('body', 'Text', 4)->columnSpanFull(),
                        ]),
                ]),
                Tab::make('Map')->schema([
                    Section::make('The destination')->columns(2)->schema([
                        TextInput::make('lat')->label('Latitude')->numeric()->minValue(-90)->maxValue(90)->placeholder('e.g. 35.38750'),
                        TextInput::make('lng')->label('Longitude')->numeric()->minValue(-180)->maxValue(180)->placeholder('e.g. 74.58360'),
                    ]),
                    Section::make('Starting point')
                        ->description('The schematic map draws a line from this starting point to the destination. Usually the town where the drive or trek begins.')
                        ->columns(3)
                        ->schema([
                            Copy::text('origin_name', 'Starting point name')->placeholder('e.g. Raikot Bridge'),
                            TextInput::make('origin_lat')->label('Latitude')->numeric()->minValue(-90)->maxValue(90),
                            TextInput::make('origin_lng')->label('Longitude')->numeric()->minValue(-180)->maxValue(180),
                        ]),
                ]),
                Tab::make('Road status')->schema([
                    Section::make()
                        ->description('Leave both empty to show nothing. If you fill it in, the date is always shown to readers. A stale status is worse than none.')
                        ->schema([
                            Copy::area('road_status_text', 'Road status', 2),
                            DatePicker::make('road_status_as_of')->label('Status checked on')
                                ->requiredWith('road_status_text')
                                ->validationMessages(['required_with' => 'Add the date you checked the road, so readers know how fresh it is.']),
                        ]),
                ]),
                Tab::make('Photos')->schema([
                    Grid::make(2)->schema([
                        PhotoPicker::make('hero_photo_id', 'Hero photo')->helperText('The large photo at the top of the page.'),
                        PhotoPicker::make('card_photo_id', 'Card photo')->helperText('Shown on destination cards. Leave empty to use the hero photo.'),
                    ]),
                    PhotoPicker::ordered('stripPhotos', 'Photo strip')
                        ->helperText('The row of photos further down the page. Drag the chosen photos to change their order.'),
                ]),
                Tab::make('Partners')->schema([
                    OrderedRelationSelect::make('partners', 'Certified partners shown on this page')
                        ->helperText('Shown at the foot of the page. Drag to change their order.'),
                ]),
                Tab::make('Search engines')->schema([
                    Copy::text('seo_title', 'Search title')->maxLength(255)
                        ->helperText('Leave empty to use the destination name.'),
                    Copy::area('seo_description', 'Search description', 2)->maxLength(320)
                        ->helperText('Leave empty to use the short description.'),
                ]),
            ]),
        ]);
    }
}
