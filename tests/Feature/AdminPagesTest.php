<?php

namespace Tests\Feature;

use App\Filament\Resources\Destinations\Pages\EditDestination;
use App\Filament\Resources\Partners\Pages\CreatePartner;
use App\Filament\Resources\CommunityPosts\Pages\CreateCommunityPost;
use App\Filament\Resources\Stories\Pages\CreateStory;
use App\Models\AboutPhoto;
use App\Models\Author;
use App\Models\CommunityPost;
use App\Models\Destination;
use App\Models\NavItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\Photo;
use App\Models\Redirect;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Story;
use App\Models\StoryCategory;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\User;
use App\Rules\CopyRules;
use Filament\Forms\Components\Repeater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->firstOrFail();
        $this->ensureOneOfEach();
    }

    /** The seeders may not create every kind of row; make sure each edit page has a record. */
    protected function ensureOneOfEach(): void
    {
        $photo = Photo::first() ?? Photo::create(['caption' => 'Kharfaq Lake, Gilgit-Baltistan', 'slot_code' => 'TEST-01']);
        $region = Region::firstOrFail();
        $destination = Destination::first() ?? Destination::create(['name' => 'Fairy Meadows', 'region_id' => $region->id]);
        Author::first() ?? Author::create(['name' => 'Test Author']);
        Story::first() ?? Story::create(['title' => 'A test story']);
        Partner::first() ?? Partner::create(['name' => 'Test Hotel', 'partner_category_id' => PartnerCategory::firstOrFail()->id]);
        CommunityPost::first() ?? CommunityPost::create(['facebook_url' => 'https://facebook.com/x', 'quote' => 'Lovely.', 'member_name' => 'Ali']);
        TeamMember::first() ?? TeamMember::create(['name' => 'Team Person']);
        AboutPhoto::first() ?? AboutPhoto::create(['photo_id' => $photo->id]);
        Page::first() ?? Page::create(['title' => 'Privacy']);
        Subscriber::first() ?? Subscriber::create(['email' => 'reader@example.com', 'source' => 'home']);
        Redirect::first() ?? Redirect::create(['from_path' => '/old', 'to_path' => '/new']);
    }

    /** @return array<string, array{0: class-string, 1: bool, 2: bool}> slug => [model, has create, has edit] */
    public static function resources(): array
    {
        return [
            'stories' => [Story::class, true, true],
            'destinations' => [Destination::class, true, true],
            'partners' => [Partner::class, true, true],
            'community-posts' => [CommunityPost::class, true, true],
            'photos' => [Photo::class, true, true],
            'authors' => [Author::class, true, true],
            'team-members' => [TeamMember::class, true, true],
            'about-photos' => [AboutPhoto::class, true, true],
            'settings' => [Setting::class, false, true],
            'nav-items' => [NavItem::class, true, true],
            'pages' => [Page::class, true, true],
            'subscribers' => [Subscriber::class, false, false],
            'regions' => [Region::class, true, true],
            'story-categories' => [StoryCategory::class, true, true],
            'partner-categories' => [PartnerCategory::class, true, true],
            'users' => [User::class, true, true],
            'redirects' => [Redirect::class, true, true],
        ];
    }

    public function test_dashboard_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertOk();

        Livewire::test(\App\Filament\Widgets\SiteOverview::class)
            ->assertSee('Photo slots still empty')
            ->assertSee('of '.Photo::count().' photos');
        Livewire::test(\App\Filament\Widgets\Drafts::class)->assertSee('Drafts');
    }

    public function test_every_resource_page_loads_for_the_admin(): void
    {
        $this->actingAs($this->admin);

        foreach (static::resources() as $slug => [$model, $hasCreate, $hasEdit]) {
            $this->get("/admin/{$slug}")->assertOk();

            if ($hasCreate) {
                $this->get("/admin/{$slug}/create")->assertOk();
            } else {
                $this->get("/admin/{$slug}/create")->assertNotFound();
            }

            if ($hasEdit) {
                $record = $model::query()->firstOrFail();
                $this->get("/admin/{$slug}/{$record->getRouteKey()}/edit")->assertOk();
            }
        }
    }

    public function test_editors_cannot_reach_setup_resources(): void
    {
        $editor = User::where('role', 'editor')->firstOrFail();
        $this->actingAs($editor);

        foreach (['regions', 'story-categories', 'partner-categories', 'users', 'redirects'] as $slug) {
            $this->get("/admin/{$slug}")->assertForbidden();
        }
        $this->get('/admin/regions/create')->assertForbidden();
        $this->get('/admin/users/'.$this->admin->id.'/edit')->assertForbidden();

        $this->get('/admin/stories')->assertOk();
        $this->get('/admin/settings')->assertOk();
    }

    public function test_subscribers_export_streams_a_csv(): void
    {
        $this->actingAs($this->admin);
        $response = \App\Filament\Resources\Subscribers\Pages\ListSubscribers::csv();
        ob_start();
        $response->sendContent();
        $csv = ob_get_clean();

        $this->assertStringStartsWith('email,source,signed_up_at', $csv);
        $this->assertStringContainsString(Subscriber::first()->email, $csv);
    }

    public function test_a_story_can_be_created_through_the_form(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateStory::class)
            ->fillForm([
                'title' => 'Three days on the Deosai plains',
                'location' => 'Deosai, Gilgit-Baltistan',
                'excerpt' => 'Wild flowers, brown bears and a very long road.',
                'body' => '<h2>Day one</h2><p>We left Skardu before dawn.</p>',
                'story_category_id' => StoryCategory::first()->id,
                'region_id' => Region::first()->id,
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $story = Story::where('title', 'Three days on the Deosai plains')->firstOrFail();
        $this->assertSame('three-days-on-the-deosai-plains', $story->slug);
        $this->assertStringContainsString('We left Skardu before dawn.', $story->body);
        $this->assertNotNull($story->published_at);
        $this->assertGreaterThanOrEqual(1, $story->read_minutes);
    }

    public function test_copy_rules_reject_em_dashes_and_emoji(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateStory::class)
            ->fillForm(['title' => "Hunza \u{2014} the long way round"])
            ->call('create')
            ->assertHasFormErrors(['title']);

        Livewire::test(CreateStory::class)
            ->fillForm(['title' => 'Hunza in autumn', 'body' => "<p>So good \u{1F3D4}\u{FE0F}</p>"])
            ->call('create')
            ->assertHasFormErrors(['body']);

        $this->assertFalse(Story::where('title', 'like', 'Hunza%')->exists());
        $this->assertTrue(CopyRules::breaks("A \u{2014} B"));
        $this->assertTrue(CopyRules::breaks("Nice \u{1F600}"));
        $this->assertFalse(CopyRules::breaks('Hunza, Gilgit-Baltistan. 2,500 m, 5 to 6 hours.'));
    }

    public function test_destination_repeaters_save_rows_with_the_right_type(): void
    {
        $this->actingAs($this->admin);
        $destination = Destination::firstOrFail();
        $photos = collect([
            Photo::create(['caption' => 'Strip one, Hunza']),
            Photo::create(['caption' => 'Strip two, Hunza']),
        ]);

        $undo = Repeater::fake();

        Livewire::test(EditDestination::class, ['record' => $destination->getRouteKey()])
            ->fillForm([
                'routeLegs' => [
                    ['from_place' => 'Islamabad', 'to_place' => 'Chilas', 'distance' => '450 km', 'duration' => '10 hours', 'body' => 'Leave early.'],
                    ['from_place' => 'Chilas', 'to_place' => 'Raikot Bridge', 'distance' => '60 km', 'duration' => '1 hour', 'body' => null],
                ],
                'blocks' => [
                    ['kind' => 'Places to visit', 'heading' => 'Beyal camp', 'body' => 'An hour above the meadow.'],
                ],
                'stripPhotos' => [(string) $photos[1]->id, (string) $photos[0]->id],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $undo();

        $destination->refresh();
        $legs = $destination->routeLegs()->get();
        $this->assertCount(2, $legs);
        $this->assertSame(['route_leg'], $legs->pluck('type')->unique()->values()->all());
        $this->assertSame(['Islamabad', 'Chilas'], $legs->pluck('from_place')->all());

        $blocks = $destination->blocks()->get();
        $this->assertCount(1, $blocks);
        $this->assertSame('section', $blocks->first()->type);
        $this->assertSame('Beyal camp', $blocks->first()->heading);

        $this->assertSame([$photos[1]->id, $photos[0]->id], $destination->stripPhotos()->pluck('photos.id')->all());
    }

    public function test_a_partner_cannot_be_published_without_a_verdict(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreatePartner::class)
            ->fillForm([
                'name' => 'Eagle Nest Hotel',
                'partner_category_id' => PartnerCategory::first()->id,
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['verdict', 'certified_since']);

        Livewire::test(CreatePartner::class)
            ->fillForm([
                'name' => 'Eagle Nest Hotel',
                'partner_category_id' => PartnerCategory::first()->id,
                'is_published' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
    }

    public function test_a_community_post_needs_permission_before_publishing(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateCommunityPost::class)
            ->fillForm([
                'facebook_url' => 'https://facebook.com/groups/ttn/posts/1',
                'quote' => 'The road to Naltar was fine in a car.',
                'member_name' => 'Sana',
                'is_published' => true,
                'permission_confirmed' => false,
            ])
            ->call('create')
            ->assertHasFormErrors(['is_published']);
    }
}
