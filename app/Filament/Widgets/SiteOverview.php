<?php

namespace App\Filament\Widgets;

use App\Models\CommunityPost;
use App\Models\Destination;
use App\Models\Partner;
use App\Models\Photo;
use App\Models\Story;
use App\Models\Subscriber;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SiteOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'At a glance';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $published = Story::where('is_published', true)->count();
        $drafts = Story::where('is_published', false)->count();
        $waiting = CommunityPost::where('permission_confirmed', false)->count();
        $photos = Photo::count();
        $empty = Photo::whereNull('path')->count();

        return [
            Stat::make('Published stories', $published)
                ->description($drafts === 1 ? '1 draft' : $drafts.' drafts')
                ->icon(Heroicon::OutlinedNewspaper),
            Stat::make('Destinations', Destination::where('is_published', true)->count())
                ->description(Destination::where('is_published', false)->count().' unpublished')
                ->icon(Heroicon::OutlinedMap),
            Stat::make('Certified partners', Partner::where('is_published', true)->count())
                ->description(Partner::where('is_published', false)->count().' unpublished')
                ->icon(Heroicon::OutlinedCheckBadge),
            Stat::make('Subscribers', Subscriber::count())
                ->description(Subscriber::where('created_at', '>=', now()->subDays(30))->count().' in the last 30 days')
                ->icon(Heroicon::OutlinedEnvelope),
            Stat::make('Community posts waiting for permission', $waiting)
                ->description($waiting ? 'Cannot be published until the member agrees' : 'All posts have permission')
                ->descriptionColor($waiting ? 'warning' : 'success')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight),
            Stat::make('Photo slots still empty', $empty)
                ->description('of '.$photos.' photos')
                ->descriptionColor($empty ? 'warning' : 'success')
                ->icon(Heroicon::OutlinedPhoto),
        ];
    }
}
