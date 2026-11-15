<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('role', 20)->default('editor'); // admin | editor
        });

        Schema::create('photos', function (Blueprint $t) {
            $t->id();
            $t->string('path')->nullable();          // null = still a labelled placeholder slot
            $t->string('caption');                   // always names the location
            $t->string('alt')->nullable();
            $t->string('credit')->default('Photo: Waseem Photography');
            $t->string('slot_code', 60)->nullable(); // e.g. HOME-01, from the photography brief
            $t->string('slot_note')->nullable();     // what the slot is waiting for
            $t->unsignedInteger('width')->nullable();
            $t->unsignedInteger('height')->nullable();
            $t->timestamps();
            $t->index('slot_code');
        });

        Schema::create('regions', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('destinations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('region_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('blurb')->nullable();
            $t->longText('overview')->nullable();
            $t->text('pull_quote')->nullable();
            $t->longText('getting_there')->nullable();
            $t->longText('where_to_stay')->nullable();
            $t->string('stat_season')->nullable();
            $t->string('stat_altitude')->nullable();
            $t->string('stat_drive')->nullable();
            $t->string('stat_difficulty')->nullable();
            $t->string('stat_permits')->nullable();
            $t->decimal('lat', 8, 5)->nullable();
            $t->decimal('lng', 8, 5)->nullable();
            $t->string('origin_name')->nullable();
            $t->decimal('origin_lat', 8, 5)->nullable();
            $t->decimal('origin_lng', 8, 5)->nullable();
            $t->text('road_status_text')->nullable();
            $t->date('road_status_as_of')->nullable();
            $t->foreignId('hero_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->foreignId('card_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 320)->nullable();
            $t->timestamps();
            $t->index(['is_published', 'order']);
        });

        Schema::create('content_blocks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $t->string('type', 20)->default('section'); // section | route_leg
            $t->unsignedInteger('order')->default(0);
            // section
            $t->string('kind')->nullable();
            $t->string('heading')->nullable();
            $t->text('body')->nullable();
            // route leg
            $t->string('from_place')->nullable();
            $t->string('to_place')->nullable();
            $t->string('distance', 60)->nullable();
            $t->string('duration', 60)->nullable();
            $t->timestamps();
            $t->index(['destination_id', 'type', 'order']);
        });

        Schema::create('destination_photo', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $t->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('authors', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('role_line')->nullable();
            $t->text('bio')->nullable();
            $t->foreignId('photo_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('story_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('stories', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->foreignId('author_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('story_category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $t->string('location')->nullable();
            $t->boolean('is_featured')->default(false);
            $t->text('excerpt')->nullable();
            $t->text('lead')->nullable();
            $t->longText('body')->nullable();
            $t->foreignId('hero_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->foreignId('card_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->unsignedSmallInteger('read_minutes')->nullable();
            $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 320)->nullable();
            $t->timestamps();
            $t->index(['is_published', 'published_at']);
        });

        Schema::create('partner_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');          // singular, e.g. Hotel
            $t->string('plural_name');   // filter label, e.g. Hotels
            $t->string('slug')->unique();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('partners', function (Blueprint $t) {
            $t->id();
            $t->foreignId('partner_category_id')->constrained()->restrictOnDelete();
            $t->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->string('slug')->unique();
            $t->string('location')->nullable();
            $t->text('description')->nullable();
            $t->longText('overview')->nullable();
            $t->date('certified_since')->nullable();
            $t->text('verdict')->nullable();
            $t->string('signature')->default('The To The North team');
            $t->string('address')->nullable();
            $t->string('phone', 60)->nullable();
            $t->string('email')->nullable();
            $t->string('website')->nullable();
            $t->string('map_note')->nullable();
            $t->text('cta_text')->nullable();
            $t->foreignId('hero_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->foreignId('thumb_photo_id')->nullable()->constrained('photos')->nullOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->boolean('is_published')->default(false);
            $t->timestamp('published_at')->nullable();
            $t->string('seo_title')->nullable();
            $t->string('seo_description', 320)->nullable();
            $t->timestamps();
            $t->index(['is_published', 'order']);
        });

        Schema::create('partner_offers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $t->string('icon', 40)->default('check');
            $t->string('text');
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('partner_photo', function (Blueprint $t) {
            $t->id();
            $t->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $t->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('destination_partner', function (Blueprint $t) {
            $t->id();
            $t->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $t->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('community_posts', function (Blueprint $t) {
            $t->id();
            $t->string('facebook_url', 500);
            $t->text('quote');
            $t->string('member_name');
            $t->date('posted_on')->nullable();
            $t->foreignId('photo_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();
            $t->boolean('show_on_home')->default(false);
            $t->boolean('permission_confirmed')->default(false);
            $t->boolean('is_published')->default(false);
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
            $t->index(['is_published', 'posted_on']);
        });

        Schema::create('subscribers', function (Blueprint $t) {
            $t->id();
            $t->string('email')->unique();
            $t->string('source', 40)->nullable();
            $t->timestamps();
        });

        Schema::create('team_members', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('role_line')->nullable();
            $t->foreignId('photo_id')->nullable()->constrained()->nullOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('about_photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('photo_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('nav_items', function (Blueprint $t) {
            $t->id();
            $t->string('location', 40); // header | footer_explore | footer_community
            $t->string('label');
            $t->string('url');
            $t->unsignedInteger('order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->timestamps();
            $t->index(['location', 'order']);
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('group', 60);
            $t->string('key', 100)->unique();
            $t->string('label');
            $t->string('help')->nullable();
            $t->string('type', 20)->default('text'); // text | textarea
            $t->text('value')->nullable();
            $t->unsignedInteger('order')->default(0);
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->longText('body')->nullable();
            $t->string('seo_description', 320)->nullable();
            $t->timestamps();
        });

        Schema::create('redirects', function (Blueprint $t) {
            $t->id();
            $t->string('from_path')->unique();
            $t->string('to_path');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['redirects', 'pages', 'settings', 'nav_items', 'about_photos', 'team_members', 'subscribers',
            'community_posts', 'destination_partner', 'partner_photo', 'partner_offers', 'partners',
            'partner_categories', 'stories', 'story_categories', 'authors', 'destination_photo',
            'content_blocks', 'destinations', 'regions', 'photos'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
