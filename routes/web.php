<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\StoryController;
use App\Http\Middleware\CachePage;
use Illuminate\Support\Facades\Route;

/*
| Public site. Every content page is cached for anonymous visitors and the
| cache clears itself when anything is saved in the admin (/admin).
*/

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->middleware(CachePage::class);
Route::get('/feed.xml', [SeoController::class, 'feed'])->middleware(CachePage::class);

Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:8,1')->name('newsletter');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/results', [SearchController::class, 'results'])->middleware('throttle:120,1');

Route::middleware(CachePage::class)->group(function () {
    Route::get('/', HomeController::class)->name('home');

    Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/{story:slug}', [StoryController::class, 'show'])->name('stories.show');

    Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
    Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');

    Route::get('/certified', [PartnerController::class, 'index'])->name('certified.index');
    Route::get('/certified/{partner:slug}', [PartnerController::class, 'show'])->name('certified.show');

    Route::get('/community', CommunityController::class)->name('community');
    Route::get('/about', AboutController::class)->name('about');
});

if (config('ttn.styleguide')) {
    Route::view('/styleguide', 'styleguide')->name('styleguide');
}

// Editable pages (privacy, terms, contact), saved redirects, then the 404.
Route::get('/{path}', [PageController::class, 'show'])->where('path', '^(?!admin|livewire|storage|build|up).+$')->middleware(CachePage::class);
