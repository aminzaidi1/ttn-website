<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Partner;
use App\Models\Story;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_every_public_page_renders(): void
    {
        $urls = ['/', '/stories', '/destinations', '/certified', '/community', '/about', '/search?q=hunza',
            '/privacy', '/terms', '/contact', '/styleguide', '/sitemap.xml', '/feed.xml', '/robots.txt',
            '/stories?region=azad-kashmir', '/destinations?region=chitral', '/certified?category=guide'];
        foreach (Story::all() as $m) $urls[] = '/stories/'.$m->slug;
        foreach (Destination::all() as $m) $urls[] = '/destinations/'.$m->slug;
        foreach (Partner::all() as $m) $urls[] = '/certified/'.$m->slug;

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_unknown_and_unpublished_pages_are_404(): void
    {
        $this->get('/this-road-does-not-exist')->assertNotFound()->assertSee('This road does not go anywhere.');

        $story = Story::first();
        $story->update(['is_published' => false]);
        $this->get('/stories/'.$story->slug)->assertNotFound();

        $destination = Destination::first();
        $destination->update(['is_published' => false]);
        $this->get('/destinations/'.$destination->slug)->assertNotFound();

        $partner = Partner::first();
        $partner->update(['is_published' => false]);
        $this->get('/certified/'.$partner->slug)->assertNotFound();
    }

    public function test_road_status_renders_nothing_when_empty(): void
    {
        $with = Destination::whereNotNull('road_status_text')->first();
        $this->get('/destinations/'.$with->slug)->assertSee('Road status as of');

        $with->update(['road_status_text' => null, 'road_status_as_of' => null]);
        $this->get('/destinations/'.$with->slug)->assertDontSee('Road status as of');
    }

    public function test_changing_a_slug_keeps_the_old_link_working(): void
    {
        $story = Story::first();
        $old = '/stories/'.$story->slug;
        $story->update(['slug' => 'a-new-address']);

        $this->get($old)->assertRedirect('/stories/a-new-address');
    }

    public function test_newsletter_stores_subscribers_and_rejects_bad_input(): void
    {
        $this->postJson('/newsletter', ['email' => 'Reader@Example.com', 'source' => 'footer'])->assertOk();
        $this->assertDatabaseHas('subscribers', ['email' => 'reader@example.com', 'source' => 'footer']);

        $this->postJson('/newsletter', ['email' => 'reader@example.com'])->assertOk();
        $this->assertSame(1, Subscriber::count());

        $this->postJson('/newsletter', ['email' => 'not-an-email'])->assertStatus(422);

        // Honeypot filled in: looks like success, stores nothing.
        $this->postJson('/newsletter', ['email' => 'bot@example.com', 'website' => 'spam'])->assertOk();
        $this->assertDatabaseMissing('subscribers', ['email' => 'bot@example.com']);
    }

    public function test_search_finds_content_across_types(): void
    {
        $this->get('/search/results?q=hunza')->assertOk()->assertSee('Hunza Valley')->assertSee('Eagle Nest Hotel');
        $this->get('/search/results?q=zzzz')->assertOk()->assertSee('Nothing here yet');
    }

    public function test_sitemap_lists_published_content_only(): void
    {
        $hidden = Story::first();
        $hidden->update(['is_published' => false]);
        $shown = Destination::first();

        $this->get('/sitemap.xml')->assertOk()->assertSee($shown->slug)->assertDontSee($hidden->slug);
    }

    public function test_page_cache_serves_then_clears_on_save(): void
    {
        config(['ttn.page_cache' => true]);
        $this->get('/about')->assertHeader('X-TTN-Cache', 'miss');
        $this->get('/about')->assertHeader('X-TTN-Cache', 'hit');

        Story::first()->update(['title' => 'A changed title']);
        $this->get('/about')->assertHeader('X-TTN-Cache', 'miss');
    }
}
