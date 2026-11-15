<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\PhotoPicker;
use App\Support\Icons;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        $neededToPublish = fn (Get $get): bool => (bool) $get('is_published');

        return $schema->components([
            Grid::make(['default' => 1, 'lg' => 3])->columnSpanFull()->schema([
                Group::make()->columnSpan(['lg' => 2])->schema([
                    Section::make('The partner')->columns(2)->schema([
                        Copy::text('name', 'Name')->required()->maxLength(255),
                        Copy::text('location', 'Location')->maxLength(255)
                            ->helperText('e.g. Karimabad, Hunza'),
                        Copy::area('description', 'Card text', 2)->columnSpanFull()
                            ->helperText('One or two sentences shown on the partner card.'),
                        Copy::rich('overview', 'Overview')->columnSpanFull(),
                    ]),
                    Section::make('Certification')
                        ->description('Why we certify them. A partner cannot be published without a verdict and a certified-since date.')
                        ->columns(2)
                        ->schema([
                            DatePicker::make('certified_since')->label('Certified since')
                                ->required($neededToPublish)
                                ->validationMessages(['required' => 'Add the date they were certified before publishing.']),
                            Copy::text('signature', 'Signed by')->default('The To The North team')->required(),
                            Copy::area('verdict', 'Our verdict', 5)->columnSpanFull()
                                ->required($neededToPublish)
                                ->validationMessages(['required' => 'Write the verdict before publishing. It is the reason the page exists.'])
                                ->helperText('First person plural, specific and honest. This is the product.'),
                        ]),
                    Section::make('What they offer')->schema([
                        Repeater::make('offers')
                            ->hiddenLabel()
                            ->relationship('offers')
                            ->orderColumn('order')
                            ->reorderable()
                            ->defaultItems(0)
                            ->addActionLabel('Add an offer')
                            ->columns(3)
                            ->schema([
                                Select::make('icon')->label('Icon')
                                    ->options(collect(Icons::names())->mapWithKeys(fn ($n) => [$n => Str::headline($n)])->all())
                                    ->default('check')->required()->searchable(),
                                Copy::text('text', 'Text')->required()->maxLength(255)->columnSpan(2),
                            ]),
                    ]),
                    Section::make('Contact')->columns(2)->schema([
                        Copy::text('address', 'Address')->columnSpanFull(),
                        TextInput::make('phone')->label('Phone')->tel()->maxLength(60),
                        TextInput::make('email')->label('Email')->email(),
                        TextInput::make('website')->label('Website')->url()->placeholder('https://'),
                        Copy::text('map_note', 'Map note')->helperText('e.g. Ten minutes on foot from Baltit Fort'),
                        Copy::area('cta_text', 'Booking note', 2)->columnSpanFull()
                            ->helperText('The short line above the contact buttons.'),
                    ]),
                    Section::make('Search engines')->collapsed()->schema([
                        Copy::text('seo_title', 'Search title')->maxLength(255),
                        Copy::area('seo_description', 'Search description', 2)->maxLength(320),
                    ]),
                ]),
                Group::make()->columnSpan(['lg' => 1])->schema([
                    Section::make('Publishing')->schema([
                        Toggle::make('is_published')->label('Published')->live()
                            ->helperText('Needs a verdict and a certified-since date.'),
                        TextInput::make('order')->label('Position in lists')->numeric()->default(0)
                            ->helperText('Lower numbers come first.'),
                    ]),
                    Section::make('Filed under')->schema([
                        Select::make('partner_category_id')->label('Category')
                            ->relationship('category', 'name')->required()->preload(),
                        Select::make('region_id')->label('Region')->relationship('region', 'name')->preload(),
                    ]),
                    Section::make('Photos')->schema([
                        PhotoPicker::make('hero_photo_id', 'Hero photo'),
                        PhotoPicker::make('thumb_photo_id', 'Card photo')
                            ->helperText('The small photo on partner cards.'),
                        PhotoPicker::ordered('galleryPhotos', 'Gallery')
                            ->helperText('Drag the chosen photos to change their order.'),
                    ]),
                    Section::make('Web address')->collapsed()->schema([
                        TextInput::make('slug')->label('Slug')->prefix('/certified/')->maxLength(255)
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->helperText('Made from the name when left empty. Changing it keeps old links working.'),
                    ]),
                ]),
            ]),
        ]);
    }
}
