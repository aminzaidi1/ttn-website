<?php

namespace Database\Seeders;

use App\Models\NavItem;
use App\Models\Page;
use App\Models\PartnerCategory;
use App\Models\Region;
use App\Models\Setting;
use App\Models\StoryCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Site structure every install needs: the first admin, regions, categories,
 * navigation, the editable site text and the simple pages.
 * Safe to run more than once: it never overwrites text an editor has changed.
 */
class SiteSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@tothenorth.test'], ['name' => 'TTN Admin', 'password' => 'change-me-now', 'role' => 'admin']);
        User::firstOrCreate(['email' => 'editor@tothenorth.test'], ['name' => 'TTN Editor', 'password' => 'change-me-now', 'role' => 'editor']);

        foreach (['Gilgit-Baltistan', 'Khyber Pakhtunkhwa', 'Azad Kashmir', 'Chitral'] as $i => $name) {
            Region::firstOrCreate(['name' => $name], ['order' => $i]);
        }
        foreach (['Trekking', 'Road trips', 'Photography', 'Culture', 'Guides', 'Environment'] as $i => $name) {
            StoryCategory::firstOrCreate(['name' => $name], ['order' => $i]);
        }
        foreach ([['Hotel', 'Hotels'], ['Tour Operator', 'Tour operators'], ['Jeep Service', 'Jeep services'], ['Guide', 'Guides']] as $i => [$name, $plural]) {
            PartnerCategory::firstOrCreate(['name' => $name], ['plural_name' => $plural, 'order' => $i]);
        }

        $nav = [
            'header' => [['Home', '/'], ['Destinations', '/destinations'], ['Stories', '/stories'], ['TTN Certified', '/certified'], ['Community', '/community'], ['About', '/about']],
            'footer_explore' => [['Destinations', '/destinations'], ['Stories', '/stories'], ['TTN Certified', '/certified']],
            'footer_community' => [['Facebook group', 'https://facebook.com/share/g/1D3ztF2tAq/'], ['Community', '/community'], ['About', '/about'], ['Contact', '/contact']],
        ];
        foreach ($nav as $location => $items) {
            if (NavItem::where('location', $location)->exists()) {
                continue;
            }
            foreach ($items as $i => [$label, $url]) {
                NavItem::create(['location' => $location, 'label' => $label, 'url' => $url, 'order' => $i]);
            }
        }

        // Editable site text. Each file in settings/ returns rows: [group, key, label, value, type?, help?].
        $order = 0;
        foreach (glob(__DIR__.'/settings/*.php') as $file) {
            foreach (require $file as $row) {
                [$group, $key, $label, $value] = $row;
                Setting::firstOrCreate(['key' => $key], [
                    'group' => $group, 'label' => $label, 'value' => $value,
                    'type' => $row[4] ?? (mb_strlen((string) $value) > 90 ? 'textarea' : 'text'),
                    'help' => $row[5] ?? null, 'order' => $order++,
                ]);
            }
        }

        $pages = [
            'Contact' => '<p>The fastest way to reach us is the Facebook group, where the team and 466,000 travellers answer questions every day.</p><p>For partnerships, TTN Certified enquiries and press, write to <a href="mailto:hello@tothenorth.pk">hello@tothenorth.pk</a>.</p>',
            'Privacy' => '<p>We collect one thing: your email address, and only if you subscribe to the newsletter. We use it to send you the newsletter and for nothing else. We do not sell it or share it.</p><p>To be removed from the list, write to <a href="mailto:hello@tothenorth.pk">hello@tothenorth.pk</a> and we will delete your address.</p><p>This page is a placeholder until the final policy is reviewed.</p>',
            'Terms' => '<p>Everything on this site is written and photographed by the To The North team unless credited otherwise. Photography is by Waseem Photography and may not be reused without permission.</p><p>Road, weather and security conditions in the north change quickly. Treat what you read here as a starting point and check the Facebook group before you travel.</p><p>This page is a placeholder until the final terms are reviewed.</p>',
        ];
        foreach ($pages as $title => $body) {
            Page::firstOrCreate(['title' => $title], ['body' => $body]);
        }
    }
}
