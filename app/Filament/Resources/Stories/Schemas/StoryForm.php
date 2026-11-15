<?php

namespace App\Filament\Resources\Stories\Schemas;

use App\Filament\Support\Copy;
use App\Filament\Support\PhotoPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(['default' => 1, 'lg' => 3])->columnSpanFull()->schema([
                Group::make()->columnSpan(['lg' => 2])->schema([
                    Section::make('The story')->schema([
                        Copy::text('title', 'Title')->required()->maxLength(255),
                        Copy::text('location', 'Location')
                            ->maxLength(255)
                            ->helperText('Names the place, e.g. Fairy Meadows, Gilgit-Baltistan'),
                        Copy::area('excerpt', 'Excerpt', 2)
                            ->maxLength(200)
                            ->helperText('One or two sentences for story cards and search results. Up to 200 characters.'),
                        Copy::area('lead', 'Lead paragraph', 3)
                            ->helperText('The opening paragraph, shown larger above the story.'),
                        Copy::storyBody('body', 'Story')
                            ->helperText('Use Heading 2 for sections and Heading 3 for sub-sections. Images can be added with the picture button.'),
                    ]),
                    Section::make('Search engines')
                        ->description('How this story appears on Google. Leave empty to use the title and excerpt.')
                        ->collapsed()
                        ->schema([
                            Copy::text('seo_title', 'Search title')->maxLength(255),
                            Copy::area('seo_description', 'Search description', 2)->maxLength(320),
                        ]),
                ]),
                Group::make()->columnSpan(['lg' => 1])->schema([
                    Section::make('Publishing')->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->helperText('Off keeps the story as a draft that only editors can see.'),
                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->seconds(false)
                            ->helperText('Filled in automatically when you first publish. A future date holds the story back until then.'),
                        Toggle::make('is_featured')
                            ->label('Featured story')
                            ->helperText('Only one story leads the Stories page. Featuring this one unfeatures the previous one.'),
                        TextInput::make('read_minutes')
                            ->label('Reading time (minutes)')
                            ->numeric()->minValue(1)->maxValue(120)
                            ->helperText('Calculated automatically when left empty.'),
                    ]),
                    Section::make('Filed under')->schema([
                        Select::make('author_id')->label('Author')
                            ->relationship('author', 'name')->searchable()->preload(),
                        Select::make('story_category_id')->label('Category')
                            ->relationship('category', 'name')->searchable()->preload(),
                        Select::make('region_id')->label('Region')
                            ->relationship('region', 'name')->preload(),
                        Select::make('destination_id')->label('Related destination')
                            ->relationship('destination', 'name')->searchable()->preload()
                            ->helperText('Optional. Links the story to a destination page.'),
                    ]),
                    Section::make('Photos')->schema([
                        PhotoPicker::make('hero_photo_id', 'Hero photo')
                            ->helperText('The large photo at the top of the story.'),
                        PhotoPicker::make('card_photo_id', 'Card photo')
                            ->helperText('Shown on story cards. Leave empty to use the hero photo.'),
                    ]),
                    Section::make('Web address')->collapsed()->schema([
                        TextInput::make('slug')
                            ->label('Slug')
                            ->prefix('/stories/')
                            ->maxLength(255)
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->helperText('Made from the title when left empty. Lower-case words joined by hyphens. Changing it keeps old links working.'),
                    ]),
                ]),
            ]),
        ]);
    }
}
