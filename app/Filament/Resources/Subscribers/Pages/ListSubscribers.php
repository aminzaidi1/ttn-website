<?php

namespace App\Filament\Resources\Subscribers\Pages;

use App\Filament\Resources\Subscribers\SubscriberResource;
use App\Models\Subscriber;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListSubscribers extends ListRecords
{
    protected static string $resource = SubscriberResource::class;

    public function getSubheading(): ?string
    {
        return 'People who signed up for the newsletter. Export the list to send a newsletter from your mail service.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->action(fn (): StreamedResponse => static::csv()),
        ];
    }

    public static function csv(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['email', 'source', 'signed_up_at']);
            Subscriber::query()->orderBy('created_at')->lazy()->each(
                fn (Subscriber $s) => fputcsv($out, [$s->email, $s->source, $s->created_at?->toDateTimeString()])
            );
            fclose($out);
        }, 'ttn-subscribers-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }
}
