<?php

namespace Database\Seeders;

use App\Models\AboutPhoto;
use App\Models\Author;
use App\Models\CommunityPost;
use App\Models\ContentBlock;
use App\Models\Destination;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\Photo;
use App\Models\Region;
use App\Models\Story;
use App\Models\StoryCategory;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * PROTOTYPE PLACEHOLDER CONTENT. Transcribed from the design prototype in
 * design-system/ui_kits/website (data.jsx, story-details.jsx, destination-details.jsx,
 * partner-details.jsx, screens.jsx). None of it is real: every story, quote, phone number,
 * email and photo slot must be replaced in the admin before launch.
 *
 * Every image is a Photo row with no file (path null) that renders as a labelled slot.
 * Run after SiteSeeder, on a fresh database (migrate:fresh --seed).
 */
class ContentSeeder extends Seeder
{
    private const FB_GROUP = 'https://facebook.com/groups/tothenorth';

    private array $photoCache = [];

    public function run(): void
    {
        $this->seedGlobalPhotos();
        $authors = $this->seedAuthors();
        $destinations = $this->seedDestinations();
        $partners = $this->seedPartners();
        $this->attachPartnersToDestinations($destinations, $partners);
        $this->seedStories($authors);
        $this->seedCommunityPosts($destinations);
        $this->seedTeam();
        $this->seedAboutPhotos();
    }

    // ------------------------------------------------------------------ helpers

    /**
     * Create a placeholder Photo from the prototype's slot label lines.
     * Line 1 is "CODE · ratio"; the remaining lines become the note.
     */
    private function photo(array $lines, string $caption): Photo
    {
        [$code, $ratio] = array_pad(explode(' · ', $lines[0], 2), 2, null);
        $parts = array_filter(array_map(fn ($p) => rtrim((string) $p, '.'), [$ratio, ...array_slice($lines, 1)]), 'strlen');

        return Photo::create([
            'slot_code' => $code,
            'slot_note' => $parts ? implode('. ', $parts) : null,
            'caption' => $caption,
        ]);
    }

    /** Caption that names the place: the subject's first clause plus the location. */
    private function cap(string $subject, string $place): string
    {
        return trim(explode(',', $subject)[0]).', '.$place;
    }

    private function h(string $text): string
    {
        return htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
    }

    /** @param string[] $paragraphs */
    private function paragraphs(array $paragraphs): string
    {
        return implode('', array_map(fn ($p) => '<p>'.$this->h($p).'</p>', $paragraphs));
    }

    /**
     * CommunityPost uses the Publishable trait, whose saving hook sets published_at, a column the
     * community_posts table does not have. Skip model events here so the insert succeeds.
     */
    private function createPost(array $attributes): CommunityPost
    {
        return CommunityPost::withoutEvents(fn () => CommunityPost::create($attributes));
    }

    private function date(string $text): Carbon
    {
        return Carbon::parse($text)->startOfDay();
    }

    // ------------------------------------------------------------------ global photos

    private function seedGlobalPhotos(): void
    {
        $this->photo(['HOME-01 · Hero · full-viewport', 'Attabad Lake, golden hour', 'Headline sits lower-left. Keep lower-left quiet.'], 'Attabad Lake, Gilgit-Baltistan');
        $this->photo(['FOUNDER-01 · 3:4', 'Waseem in the field, camera in hand', 'Open shade, mountains soft behind'], 'Waseem in the field, northern Pakistan');
        $this->photo(['404 · full-bleed', 'A road ending at a river or slide, honest and beautiful', 'Headline centred'], 'A road ending at a river, northern Pakistan');
    }

    // ------------------------------------------------------------------ authors

    private function seedAuthors(): array
    {
        $photo = $this->photo(['TEAM-01 · 1:1', 'Head and shoulders'], 'Waseem, To The North team portrait, Pakistan');
        $this->photoCache['team-1'] = $photo;

        return [
            'Waseem' => Author::create([
                'name' => 'Waseem',
                'role_line' => 'Founder of To The North',
                'bio' => 'Founder of To The North. Photographing the northern valleys since 2018.',
                'photo_id' => $photo->id,
            ]),
        ];
    }

    // ------------------------------------------------------------------ destinations

    private function seedDestinations(): array
    {
        $out = [];
        foreach ($this->destinationData() as $i => $d) {
            $place = $d['name'];
            $card = $this->photo($d['card'], $this->cap($d['card'][1], $place));
            $hero = $this->photo($d['hero'], $this->cap($d['hero'][1], $place));
            $map = $d['map'];

            $dest = Destination::create([
                'region_id' => Region::where('name', $d['region'])->value('id'),
                'name' => $d['name'],
                'blurb' => $d['blurb'],
                'overview' => $this->paragraphs($d['overview']),
                'pull_quote' => $d['pull'],
                'getting_there' => $this->paragraphs($d['gettingThere']),
                'where_to_stay' => $this->paragraphs($d['stay']),
                'stat_season' => $d['stats'][0],
                'stat_altitude' => $d['stats'][1],
                'stat_drive' => $d['stats'][2],
                'stat_difficulty' => $d['stats'][3],
                'stat_permits' => $d['stats'][4],
                'lat' => $map['d'][0],
                'lng' => $map['d'][1],
                'origin_name' => $map['origin'],
                'origin_lat' => $map['o'][0],
                'origin_lng' => $map['o'][1],
                'road_status_text' => $d['status']['text'] ?? null,
                'road_status_as_of' => isset($d['status']) ? $this->date($d['status']['asOf']) : null,
                'hero_photo_id' => $hero->id,
                'card_photo_id' => $card->id,
                'order' => $i,
                'is_published' => true,
            ]);

            foreach ($d['blocks'] as $n => [$kind, $heading, $body]) {
                ContentBlock::create(['destination_id' => $dest->id, 'type' => 'section', 'order' => $n,
                    'kind' => $kind, 'heading' => $heading, 'body' => $body]);
            }
            foreach ($d['legs'] as $n => [$from, $to, $dist, $time, $note]) {
                ContentBlock::create(['destination_id' => $dest->id, 'type' => 'route_leg', 'order' => $n,
                    'from_place' => $from, 'to_place' => $to, 'distance' => $dist, 'duration' => $time, 'body' => $note]);
            }
            foreach ($d['strip'] as $n => $subject) {
                $code = sprintf('DEST-STRIP-%02d-%02d', $i + 1, $n + 1);
                $p = $this->photo(["$code · 3:2", $subject], $this->cap($subject, $place));
                $dest->stripPhotos()->attach($p->id, ['order' => $n]);
            }

            $out[] = $dest;
        }

        return $out;
    }

    private function destinationData(): array
    {
        return [
            [
                'name' => 'Hunza Valley', 'region' => 'Gilgit-Baltistan',
                'blurb' => 'Apricot orchards, ancient forts and Rakaposhi on the horizon.',
                'card' => ['DEST-CARD-01 · near-square', 'Rakaposhi from Duikar, dawn', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-01 · 21:9', 'Rakaposhi from Duikar at dawn, valley in shadow, summit lit', 'Name sits lower-left'],
                'stats' => ['April to October', '2,438 m', '14–16 hours', 'Easy, paved', 'None for locals'],
                'overview' => [
                    'Hunza is the valley most people picture when they imagine northern Pakistan: stone lanes climbing to Baltit Fort, apricot terraces stacked above the river, and Rakaposhi filling the southern sky.',
                    'It is also the easiest of the great valleys to travel. The Karakoram Highway runs straight through it, rooms range from family homestays to proper hotels, and the people of Hunza have been hosting travellers for centuries.',
                ],
                'pull' => 'Every valley has a view. Hunza has a horizon.',
                'gettingThere' => [
                    'The drive from Islamabad is long but entirely paved. Most travellers break it overnight at Besham or Chilas. Leave before dawn on day two and you get the light coming up the Indus gorge, which is worth the alarm.',
                ],
                'legs' => [
                    ['Islamabad', 'Besham', '270 km', '6–7 h', 'Hazara Motorway to Mansehra, then the N-35.'],
                    ['Besham', 'Chilas', '260 km', '6 h', 'The roughest stretch. Landslide delays are common after rain; check the group for current status.'],
                    ['Chilas', 'Gilgit', '130 km', '3 h', 'The gorge opens up. Nanga Parbat viewpoint at Thalichi.'],
                    ['Gilgit', 'Karimabad', '100 km', '2.5 h', 'Smooth KKH. Stop at Ghulmet for the Rakaposhi viewpoint.'],
                ],
                'map' => ['origin' => 'Gilgit', 'o' => [35.92, 74.31], 'd' => [36.32, 74.65]],
                'status' => ['asOf' => '12 July 2026', 'text' => 'KKH open through to Khunjerab. Short holds at the Attabad tunnels for works traffic, mornings only.'],
                'stay' => [
                    'Karimabad keeps you close to the forts and the bazaar; Duikar, twenty minutes up the hill, gets the sunrise. A valley-facing room in Duikar runs PKR 8,000 to 18,000 in June and July, roughly half that in shoulder season, and homestays in Karimabad start around 3,500. In high summer book ahead; spring and autumn you can turn up and choose.',
                ],
                'blocks' => [
                    ['Places to visit', 'The two forts', 'Baltit and Altit are both restored and both worth the entry. Go to Altit first, it is quieter, then walk the old lanes between them.'],
                    ['Viewpoints', 'Sunrise at Duikar', 'The classic for a reason. Rakaposhi, Diran and Golden Peak light up one by one. Arrive forty minutes before first light.'],
                    ['Food to try', 'Chapshuro and apricot cake', 'Chapshuro from the bazaar ovens in Aliabad, walnut and apricot cake up in Duikar. Neither costs more than a few hundred rupees and both are better than they need to be.'],
                    ['When not to go', 'Deep winter', 'January and February the valley is cold, half the hotels close, and the light is short. Unless you are coming for snow photography, wait for the blossom in late March.'],
                ],
                'strip' => [
                    'Baltit Fort above the bazaar, morning',
                    'Boats on Attabad Lake, turquoise water',
                    'Husseini suspension bridge, walker mid-span',
                    'Apricot blossom terraces, late March',
                ],
                'tips' => [
                    ['Fuel up in Gilgit and carry cash. Cards work almost nowhere past Aliabad.', 'Usman T.'],
                    ['Book Duikar early for June and July. The sunrise rooms go first.', 'Mahnoor S.'],
                ],
            ],
            [
                'name' => 'Skardu', 'region' => 'Gilgit-Baltistan',
                'blurb' => 'The gateway to the Karakoram and the great cold deserts.',
                'card' => ['DEST-CARD-02 · near-square', 'Katpana dunes with the Indus, late sun', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-02 · 21:9', 'Katpana dunes with the Indus behind, late afternoon', 'Name sits lower-left'],
                'stats' => ['April to October', '2,228 m', '16–18 hours', 'Easy to moderate', 'NOC for some treks'],
                'overview' => [
                    'Skardu sits where the Indus spreads wide between the Karakoram and the Deosai plateau. Every expedition to K2, and to the dozens of eight-thousand-metre neighbours, has staged out of this town.',
                    'You do not need a rope to justify the trip. Cold deserts, the Kachura lakes, Shigar’s restored fort and the road up to Deosai all sit within a day of the bazaar.',
                ],
                'pull' => 'Every expedition to K2 has walked out of this town.',
                'gettingThere' => [
                    'By road it is the longest of the classic drives, and the Jaglot to Skardu section is the wildest gorge driving in the country, rebuilt and mostly excellent. There is also a one-hour flight from Islamabad that is spectacular when it flies and cancelled when it does not.',
                ],
                'legs' => [
                    ['Islamabad', 'Chilas', '530 km', '12–13 h', 'Break the journey here or at Besham.'],
                    ['Chilas', 'Jaglot', '80 km', '2 h', 'Turn east onto the Skardu road at the Indus junction.'],
                    ['Jaglot', 'Skardu', '165 km', '4 h', 'Rebuilt and fast, but respect the drops. No night driving.'],
                ],
                'map' => ['origin' => 'Gilgit', 'o' => [35.92, 74.31], 'd' => [35.30, 75.63]],
                'status' => ['asOf' => '10 July 2026', 'text' => 'Jaglot to Skardu road open and fast. Deosai track open from the Sadpara side; afternoons bring cloud, cross early.'],
                'stay' => [
                    'Skardu town has the widest range of rooms in Baltistan, from backpacker guesthouses around PKR 2,500 a night to the fort hotel at Shigar an hour away, which runs 30,000 and up and earns it. The Kachura lakes are the quiet option, twenty minutes out.',
                ],
                'blocks' => [
                    ['Places to visit', 'Katpana desert and Shigar', 'Dunes of pale sand at 2,300 metres, best in the last hour of light. Then Shigar: the 400-year-old fort is now a heritage hotel; come for lunch and the walled garden even if you sleep elsewhere.'],
                    ['Treks and walks', 'Deosai in a day', 'The second-highest plateau on earth, an hour and a half up from town. A jeep from Skardu runs somewhere between PKR 8,000 and 14,000 for the day depending on the season and how hard you bargain. July for wildflowers, September for gold grass. Leave by six.'],
                    ['Viewpoints', 'Sadpara Lake bend', 'Ten minutes up the Deosai road, the whole lake and the valley behind it. Free, unsigned, and better than most ticketed viewpoints in the country.'],
                    ['When not to go', 'November to March', 'The flight barely flies, Deosai is shut under snow, and the town half-hibernates. Baltistan in winter is for people who already know it in summer.'],
                ],
                'strip' => [
                    'Katpana dunes at last light',
                    'Upper Kachura Lake from the trail',
                    'Shigar Fort courtyard, midday',
                    'Deosai plateau in July wildflower',
                ],
                'tips' => [
                    ['The flight gets cancelled more than it flies. Book refundable and keep two buffer days.', 'Ahmed R.'],
                    ['Deosai in one day from Skardu works, but leave by six and take warm layers even in July.', 'Fatima N.'],
                ],
            ],
            [
                'name' => 'Naltar Valley', 'region' => 'Gilgit-Baltistan',
                'blurb' => 'Pine forests and three lakes the colour of stained glass.',
                'card' => ['DEST-CARD-03 · near-square', 'Satrangi Lake blue-green water, pines', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-03 · 21:9', 'Satrangi Lake at full midday saturation, pines framing', 'Name sits lower-left'],
                'stats' => ['May to September', '2,880 m', '13–14 h + jeep', 'Moderate, jeep track', 'None'],
                'overview' => [
                    'Naltar is a pine-forested side valley above Gilgit, famous for three small lakes whose colour does not look real until you are standing at the shoreline. In winter it is Pakistan’s oldest ski station.',
                    'The valley is close enough to Gilgit and Hunza to visit in a day, which is exactly what most people do. Stay a night and you get the lakes to yourself before the jeeps arrive.',
                ],
                'pull' => 'The lakes do not look real until you are standing in front of them.',
                'gettingThere' => [
                    'The paved road ends at Nomal. From there a rough, narrow jeep track climbs to Naltar Bala and on to the lakes. Hire a local jeep in Gilgit or Nomal; the fare is the best money of the trip.',
                ],
                'legs' => [
                    ['Islamabad', 'Gilgit', '590 km', '13–14 h', 'Standard KKH run, broken at Besham or Chilas.'],
                    ['Gilgit', 'Naltar Bala', '34 km', '1.5 h', 'Jeep track past Nomal. A local jeep from Gilgit runs PKR 6,000 to 9,000 return. Do not attempt in a sedan.'],
                    ['Naltar Bala', 'The lakes', '13 km', '45 min', 'Track ends at Satrangi. Walk the last stretch.'],
                ],
                'map' => ['origin' => 'Gilgit', 'o' => [35.92, 74.31], 'd' => [36.17, 74.18]],
                'status' => ['asOf' => '8 July 2026', 'text' => 'Jeep track open to Satrangi. Rough after last week’s rain past Naltar Bala; local jeeps running normally.'],
                'stay' => [
                    'A handful of simple guesthouses sit in Naltar Bala among the pines, most between PKR 3,000 and 6,000 a night. Most visitors day-trip from Gilgit or Hunza, so rooms are rarely a problem outside July weekends.',
                ],
                'blocks' => [
                    ['Viewpoints', 'The three lakes', 'Satrangi, Blue and Bodlok, strung along the upper valley. Morning light is calmest for reflections; by eleven the jeeps arrive and the surface goes busy.'],
                    ['Treks and walks', 'Pakora Pass', 'For the ambitious: a two-day trek over to Ishkoman valley. Guide required, arranged in Naltar Bala.'],
                    ['When not to go', 'Winter, unless you ski', 'December to February the valley is snowbound and the track is for locals only. The exception is the ski lifts: rental gear exists, expectations should be modest, the snow is real.'],
                ],
                'strip' => [
                    'Satrangi Lake colour bands, midday',
                    'Pine forest track above Naltar Bala',
                    'Winter lift line under fresh snow',
                    'Jeep fording the Naltar stream',
                ],
                'tips' => [
                    ['Do not take a sedan past Nomal. The jeep fare is the best money you will spend.', 'Bilal K.'],
                    ['Go early. By eleven the lakes are busy and the light has gone flat.', 'Areeba J.'],
                ],
            ],
            [
                'name' => 'Kalash Valleys', 'region' => 'Chitral',
                'blurb' => 'Living traditions in the high valleys of the Hindu Kush.',
                'card' => ['DEST-CARD-04 · near-square', 'Bumburet village lanes, spring morning', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-04 · 21:9', 'Bumburet village lanes under walnut trees, spring morning', 'Name sits lower-left'],
                'stats' => ['April to October', '1,900–2,200 m', '10–12 hours', 'Moderate', 'Valley entry toll'],
                'overview' => [
                    'The three Kalash valleys of Bumburet, Rumbur and Birir hold the last practising community of the Kalasha, whose language, festivals and wooden architecture predate everything around them.',
                    'This is the one destination on this list where the culture, not the landscape, is the point. Come as a guest. The valleys are small, the communities are smaller, and how you behave matters.',
                ],
                'pull' => 'Chilam Joshi is not a performance. You are a guest at someone’s spring.',
                'gettingThere' => [
                    'The Lowari Tunnel keeps Chitral open year-round. From Chitral town the valleys are a short but slow drive up a gorge track from Ayun, fine in season for any careful driver.',
                ],
                'legs' => [
                    ['Islamabad', 'Chitral', '380 km', '9–10 h', 'Swat motorway, then the Lowari Tunnel. Fuel in Dir.'],
                    ['Chitral', 'Ayun', '12 km', '30 min', 'Paved. The toll post is at the valley mouth.'],
                    ['Ayun', 'Bumburet', '22 km', '1.5 h', 'Gorge track, single lane in places. Slow and fine.'],
                ],
                'map' => ['origin' => 'Chitral', 'o' => [35.85, 71.79], 'd' => [35.70, 71.70]],
                'stay' => [
                    'Family-run guesthouses in Bumburet and Rumbur, most with a dozen rooms or fewer and most between PKR 3,500 and 7,000 a night with dinner. Rumbur is the quieter valley and the better choice if the festivals are not your reason for coming.',
                ],
                'blocks' => [
                    ['Festivals', 'Chilam Joshi, Uchau, Choimus', 'Spring, harvest and winter solstice. Dates shift; confirm in the group before booking. You are a guest at someone’s ritual, not an audience at a show, and the valleys fill fast in festival weeks.'],
                    ['Places to visit', 'Kalasha Dur', 'The community museum in Bumburet, built with the community. Start here; the rest of the valley makes more sense afterwards.'],
                    ['Treks and walks', 'Valley to valley on foot', 'Footpaths link Bumburet and Rumbur over the ridge. A full day, a local guide, and the best views of the Hindu Kush you will get.'],
                    ['How to behave', 'Ask first, always', 'Photography of people only with permission, every single time. Dress modestly, buy locally, and remember the valleys are home to about four thousand people and host many times that each festival.'],
                ],
                'strip' => [
                    'Wooden houses stacked on the hillside, Rumbur',
                    'Walnut trees over the village lane',
                    'Festival dresses drying on a rail, detail',
                    'The gorge track up from Ayun',
                ],
                'tips' => [
                    ['Ask before photographing people, every single time. It is their home, not a set.', 'Sana K.'],
                    ['Rumbur is quieter than Bumburet and the guesthouse food is better. Fight me.', 'Hassan M.'],
                ],
            ],
            [
                'name' => 'Neelum Valley', 'region' => 'Azad Kashmir',
                'blurb' => 'A river-cut valley of green terraces and wooden villages.',
                'card' => ['DEST-CARD-05 · near-square', 'The river bend at Keran, green terraces', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-05 · 21:9', 'The river bend at Keran, green terraces both banks', 'Name sits lower-left'],
                'stats' => ['April to October', '1,400–2,500 m', '6–7 hours', 'Easy', 'CNIC at check posts'],
                'overview' => [
                    'Neelum is the closest of the great valleys to Islamabad, a single green corridor where the road, the villages and the border all follow one river. Wooden houses, walnut groves, terraced maize.',
                    'It rewards slowness. The distances are short, the driving is easy, and the best of it, Arang Kel above Kel, you reach on foot anyway.',
                ],
                'pull' => 'The river is the road, the map and the reason.',
                'gettingThere' => [
                    'Paved the whole way and the shortest drive on this list. The road follows the Neelum river along the Line of Control, so carry your CNIC; you will show it at check posts and it is routine. Foreign travellers currently need an NOC, arranged in advance.',
                ],
                'legs' => [
                    ['Islamabad', 'Muzaffarabad', '138 km', '3 h', 'Murree Expressway, then the valley road.'],
                    ['Muzaffarabad', 'Keran', '93 km', '3 h', 'Paved but slow. The river does the navigating.'],
                    ['Keran', 'Sharda', '45 km', '1.5 h', 'Greenest stretch of the valley.'],
                    ['Sharda', 'Kel', '20 km', '1 h', 'Road ends here. Arang Kel is a two-hour walk or a short cable car plus climb.'],
                ],
                'map' => ['origin' => 'Muzaffarabad', 'o' => [34.37, 73.47], 'd' => [34.63, 73.90]],
                'stay' => [
                    'Riverside huts at Keran run PKR 6,000 to 12,000 a night in season, guesthouses at Sharda a little less, and the basic rooms up in Arang Kel are cheaper still for those who walk up. Summer weekends fill; midweek the valley is yours.',
                ],
                'blocks' => [
                    ['Places to visit', 'Arang Kel and Sharda Peeth', 'The meadow village above Kel, reached on foot; stay the night, the day-trippers leave by four. And the ruins of a mediaeval university above the river at Sharda, five minutes from the bazaar and older than everything around it.'],
                    ['Viewpoints', 'The river at Keran', 'Both banks terraced and green, the river loud between them. The huts look straight across the water; sunset is the hour.'],
                    ['When not to go', 'Monsoon weeks', 'Late July and August bring rain, slips and closures to the valley road. If the forecast is heavy, wait; the valley does not reward urgency.'],
                ],
                'strip' => [
                    'Arang Kel meadow and wooden houses',
                    'Sharda Peeth ruins above the river',
                    'The Neelum at Keran, both banks',
                    'Cable car crossing above Kel',
                ],
                'tips' => [
                    ['Carry your CNIC. You show it at every check post and it is no drama at all.', 'Adeel M.'],
                    ['Stay the night in Arang Kel. The valley after the day-trippers leave is a different place.', 'Kiran B.'],
                ],
            ],
            [
                'name' => 'Kumrat Valley', 'region' => 'Khyber Pakhtunkhwa',
                'blurb' => 'Cedar forests and meadows along the Panjkora river.',
                'card' => ['DEST-CARD-06 · near-square', 'Cedar forest, dawn mist on the Panjkora', 'Name bottom-left, tag top-right'],
                'hero' => ['DEST-HERO-06 · 21:9', 'Cedar forest with dawn mist on the Panjkora', 'Name sits lower-left'],
                'stats' => ['May to September', '2,400 m', '8–9 h + jeep', 'Moderate, jeep track', 'None'],
                'overview' => [
                    'Kumrat is a valley of old-growth deodar cedar along the Panjkora river in Upper Dir. The forest is the destination: trees ten storeys tall, meadows between them, and the river loud through all of it.',
                    'It stayed off the map longer than the Gilgit-Baltistan valleys and still feels it. Facilities are simple, the track past Thal is rough, and that is precisely the appeal.',
                ],
                'pull' => 'You hear Kumrat before you see it. The river never stops.',
                'gettingThere' => [
                    'The Swat motorway has cut the journey down: a fast run to Chakdara, then the Dir road up to Thal. Past Thal the road becomes a river-bed jeep track into the forest. Park in Thal and hire local if your car is not built for it.',
                ],
                'legs' => [
                    ['Islamabad', 'Chakdara', '220 km', '3 h', 'Motorway the whole way.'],
                    ['Chakdara', 'Thal', '130 km', '5 h', 'Slow mountain road through Dir. Fuel at Dir town.'],
                    ['Thal', 'Kumrat forest', '12 km', '1 h', 'River-bed jeep track. 4x4 or local jeep only; a local jeep from Thal runs PKR 2,500 to 4,000 each way.'],
                ],
                'map' => ['origin' => 'Peshawar', 'o' => [34.01, 71.58], 'd' => [35.53, 72.22]],
                'status' => ['asOf' => '9 July 2026', 'text' => 'Track past Thal churned by rain but local jeeps running. Park in Thal if your car sits low.'],
                'stay' => [
                    'Camps and huts in the forest itself, mostly PKR 3,000 to 6,000 a tent with bedding, and simple hotels back in Thal for less. Camp in the forest if you can; falling asleep to the Panjkora is the point of coming.',
                ],
                'blocks' => [
                    ['Treks and walks', 'The forest itself', 'Flat, shaded trails along the river between the camps and the meadows. No guide needed, no destination required; the trees are the itinerary.'],
                    ['Viewpoints', 'Do Kala Chashma', 'The twin springs at the head of the valley, an easy jeep-and-walk morning from any of the camps.'],
                    ['Places to visit', 'Katora Lake, via Jahaz Banda', 'Across the ridge: a separate jeep leg and a proper day’s trek. Plan it as its own trip, not a Kumrat add-on.'],
                    ['When not to go', 'After heavy rain', 'The track is a river bed and behaves like one. If it has rained hard for two days, the forest will still be there next week.'],
                ],
                'strip' => [
                    'Deodar trunks in dawn mist',
                    'The Panjkora white over boulders',
                    'Camp lights under the cedars, dusk',
                    'Meadow clearing, horses grazing',
                ],
                'tips' => [
                    ['Camp in the forest, not Thal. Falling asleep to the Panjkora is the whole point.', 'Zara I.'],
                    ['The track past Thal eats low cars. Park in Thal, take a local jeep, thank me later.', 'Omar F.'],
                ],
            ],
        ];
    }

    // ------------------------------------------------------------------ partners

    private function seedPartners(): array
    {
        $out = [];
        foreach ($this->partnerData() as $i => $d) {
            $n = $i + 1;
            $thumb = $this->photo([sprintf('THUMB-%02d', $n), $d['thumbNote']], $d['thumbCaption']);
            $hero = $this->photo($d['hero'], $this->cap($d['hero'][1], $d['location']));

            $partner = Partner::create([
                'partner_category_id' => PartnerCategory::where('name', $d['category'])->value('id'),
                'region_id' => Region::where('name', $d['region'])->value('id'),
                'name' => $d['name'],
                'location' => $d['location'],
                'description' => $d['description'],
                'overview' => $this->paragraphs($d['overview']),
                'certified_since' => $this->date('1 '.$d['since']),
                'verdict' => $d['verdict'],
                'address' => $d['address'],
                'phone' => $d['phone'],
                'email' => $d['email'],
                'map_note' => $d['mapOrigin'].', '.$d['coords'],
                'cta_text' => $d['cta'],
                'hero_photo_id' => $hero->id,
                'thumb_photo_id' => $thumb->id,
                'order' => $i,
                'is_published' => true,
            ]);

            foreach ($d['offers'] as $k => [$icon, $text]) {
                $partner->offers()->create(['icon' => $icon, 'text' => $text, 'order' => $k]);
            }
            foreach ($d['gallery'] as $k => $subject) {
                $code = sprintf('PARTNER-GAL-%02d-%02d', $n, $k + 1);
                $p = $this->photo(["$code · 3:2", $subject], $subject.', '.$d['location']);
                $partner->galleryPhotos()->attach($p->id, ['order' => $k]);
            }

            $out[] = $partner;
        }

        return $out;
    }

    private function partnerData(): array
    {
        return [
            [
                'name' => 'Karakoram Expeditions', 'category' => 'Tour Operator', 'location' => 'Skardu, Gilgit-Baltistan', 'region' => 'Gilgit-Baltistan',
                'thumbNote' => 'Guides', 'thumbCaption' => 'Karakoram Expeditions guides, Skardu, Gilgit-Baltistan',
                'description' => 'Small-group treks to K2 base camp, led by local guides who grew up in these valleys.',
                'hero' => ['PARTNER-HERO-01 · 16:9', 'Trekking group on Baltoro moraine, Trango or K2 behind', 'Name sits lower-left'],
                'since' => 'March 2022',
                'verdict' => 'We have walked with these guides to Concordia and back, twice. They hire local, they pay porters properly, and when the weather turned on us at Goro II it was their judgement, not ours, that got everyone down happy. We stand behind them without hesitation.',
                'overview' => [
                    'Karakoram Expeditions has been staging treks out of Skardu since 2009, run by a family from Hushe whose fathers and grandfathers carried loads for the first expeditions to Masherbrum. Their guides grew up beneath the mountains they now lead people through.',
                    'They keep groups small, twelve at most, and they turn down more bookings than they accept in peak season. That restraint is exactly why we certify them.',
                ],
                'offers' => [
                    ['mountain', 'K2 base camp and Concordia treks, 14 to 21 days'],
                    ['mapPin', 'Shorter valley treks: Hushe, Thalle La, Amin Brakk base'],
                    ['users', 'Licensed local guides, fair-wage porter contracts'],
                    ['fileCheck', 'NOC and permit handling for restricted zones'],
                    ['car', 'Skardu transfers and expedition logistics'],
                ],
                'gallery' => [
                    'Group crossing the Baltoro, roped',
                    'Camp at Concordia, K2 in evening light',
                    'Porters and guides at the Hushe office',
                    'Mess tent dinner, headlamps on',
                    'Trango Towers from Urdukas camp',
                ],
                'address' => 'Yadgar Chowk, Skardu, Gilgit-Baltistan',
                'phone' => '+92 355 5810 214',
                'email' => 'trek@karakoramexpeditions.pk',
                'mapOrigin' => 'Skardu bazaar', 'coords' => '35.29° N, 75.64° E',
                'cta' => 'Planning a trek? Talk to them early. The good departures fill by February.',
            ],
            [
                'name' => 'Eagle Nest Hotel', 'category' => 'Hotel', 'location' => 'Duikar, Hunza', 'region' => 'Gilgit-Baltistan',
                'thumbNote' => 'Terrace', 'thumbCaption' => 'Terrace at Eagle Nest Hotel, Duikar, Hunza',
                'description' => 'Sunrise views over Rakaposhi and Lady Finger, a short drive above Karimabad.',
                'hero' => ['PARTNER-HERO-02 · 16:9', 'The Duikar terrace at sunrise, Rakaposhi on the horizon', 'Name sits lower-left'],
                'since' => 'August 2021',
                'verdict' => 'We have photographed a hundred sunrises from this terrace and it has never once been ordinary. The rooms are honest, the kitchen is better than it needs to be, and the family who run it treat our community like their own guests. This is where we send people first.',
                'overview' => [
                    'Eagle Nest sits at the top of Duikar village, the highest point in the Hunza bowl you can drive to, with a terrace that looks across to Rakaposhi, Diran and Lady Finger. It has been run by the same family since it opened.',
                    'It is not a luxury hotel and does not pretend to be. What it has is position, warmth and the best sunrise in northern Pakistan, which is worth more than a minibar.',
                ],
                'offers' => [
                    ['bed', '24 rooms, valley-facing and garden-facing'],
                    ['utensils', 'Kitchen serving Hunza staples and the famous apricot cake'],
                    ['camera', 'Sunrise terrace, open to photographers before dawn'],
                    ['car', 'Jeep pickup from Karimabad and Aliabad'],
                ],
                'gallery' => [
                    'The terrace at first light, tripods up',
                    'Valley-facing room, morning',
                    'Rakaposhi from the garden wall',
                    'Dinner service in the stone dining room',
                ],
                'address' => 'Duikar village, above Altit, Hunza',
                'phone' => '+92 346 9532 771',
                'email' => 'stay@eaglenesthunza.pk',
                'mapOrigin' => 'Karimabad', 'coords' => '36.33° N, 74.68° E',
                'cta' => 'June and July book out weeks ahead. Shoulder season you will have the terrace to yourself.',
            ],
            [
                'name' => 'Northern Jeep Service', 'category' => 'Jeep Service', 'location' => 'Skardu, Gilgit-Baltistan', 'region' => 'Gilgit-Baltistan',
                'thumbNote' => 'Jeep', 'thumbCaption' => 'Northern Jeep Service 4x4, Skardu, Gilgit-Baltistan',
                'description' => 'Reliable 4x4 transfers to Deosai, Shigar and the Shangrila lakes.',
                'hero' => ['PARTNER-HERO-03 · 16:9', 'A 4x4 fording a stream on the Deosai track, spray frozen', 'Name sits lower-left'],
                'since' => 'June 2023',
                'verdict' => 'Their drivers know every stream crossing on Deosai by name and every mood the weather has. We have used them for three seasons of shoots, in conditions we would not drive ourselves, and the jeeps come back every time. Careful people with well-kept machines.',
                'overview' => [
                    'Northern Jeep Service runs a fleet of a dozen 4x4s out of Skardu, driven by men who have spent their working lives on the Deosai, Shigar and Basho tracks. The jeeps are old Land Cruisers, maintained obsessively, because out there maintenance is safety.',
                    'They quote flat rates by route, not by negotiation, which alone puts them ahead of most of the trade.',
                ],
                'offers' => [
                    ['car', 'Deosai crossings, Skardu to Astore, full day'],
                    ['mapPin', 'Shigar, Khaplu, Basho and Kachura transfers'],
                    ['mountain', 'Trek drop-offs and pickups, Hushe and Askole'],
                    ['users', 'English-speaking drivers on request'],
                ],
                'gallery' => [
                    'Land Cruiser on the Deosai plateau track',
                    'Stream crossing, water at the axles',
                    'The workshop yard in Skardu, fleet lined up',
                    'Driver and jeep above Sadpara Lake',
                ],
                'address' => 'Airport Road, Skardu, Gilgit-Baltistan',
                'phone' => '+92 344 9218 605',
                'email' => 'bookings@northernjeep.pk',
                'mapOrigin' => 'Skardu airport', 'coords' => '35.34° N, 75.54° E',
                'cta' => 'Tell them your route and your dates. They will tell you honestly whether the track is open.',
            ],
        ];
    }

    /** The prototype lists a destination's certified partners by shared region. */
    private function attachPartnersToDestinations(array $destinations, array $partners): void
    {
        foreach ($destinations as $dest) {
            $order = 0;
            foreach ($partners as $partner) {
                if ($partner->region_id === $dest->region_id) {
                    $dest->partners()->attach($partner->id, ['order' => $order++]);
                }
            }
        }
    }

    // ------------------------------------------------------------------ stories

    private function seedStories(array $authors): void
    {
        foreach ($this->storyData() as $i => $d) {
            $card = $this->photo($d['card'], $this->cap($d['card'][1], $d['location']));
            $hero = $this->photo($d['hero'], $this->cap($d['hero'][1], $d['location']));

            $story = Story::create([
                'title' => $d['title'],
                'author_id' => $authors[$d['author']]->id,
                'story_category_id' => StoryCategory::firstOrCreate(['name' => $d['tag']], ['order' => 99])->id,
                'region_id' => Region::where('name', $d['region'])->value('id'),
                'location' => $d['location'],
                'is_featured' => $i === 0, // the Stories index leads with the first story
                'excerpt' => $d['excerpt'],
                'lead' => $d['lead'],
                'body' => $this->storyBody($d),
                'hero_photo_id' => $hero->id,
                'card_photo_id' => $card->id,
                'is_published' => true,
                'published_at' => $this->date($d['date']),
            ]);
            // Keep the prototype's stated read time rather than the computed one.
            $story->forceFill(['read_minutes' => (int) $d['readTime']])->saveQuietly();
        }
    }

    private function storyBody(array $d): string
    {
        $html = $this->blocksHtml($d['body'], $d['location']);

        [$code, $ratio, $note, $caption] = [$d['essay'][0], $d['essay'][1], $d['essay'][2], $d['essay'][3]];
        $this->photo(["$code · $ratio", ...$note], $this->cap($note[0], $d['location']));
        $html .= '<figure class="ttn-essay"><div class="ttn-slot-inline"><span class="ttn-slot__code">'.$this->h("$code · $ratio").'</span>'
            .'<span class="ttn-slot__note">'.$this->h(implode('. ', $note)).'</span></div>'
            .'<figcaption>'.$this->h($caption).'</figcaption></figure>';

        return $html.$this->blocksHtml($d['after'], $d['location']);
    }

    /** Turn the prototype's body blocks into HTML; inline images also get a Photo row. */
    private function blocksHtml(array $blocks, string $location): string
    {
        $html = '';
        foreach ($blocks as $b) {
            $html .= match ($b['t']) {
                'p' => '<p>'.$this->h($b['text']).'</p>',
                'h2' => '<h2>'.$this->h($b['text']).'</h2>',
                'h3' => '<h3>'.$this->h($b['text']).'</h3>',
                'quote' => '<blockquote><p>'.$this->h($b['text']).'</p></blockquote>',
                'list' => '<ul>'.implode('', array_map(fn ($li) => '<li>'.$this->h($li).'</li>', $b['items'])).'</ul>',
                'image' => $this->inlineFigure($b['id'], $b['subject'], $location),
            };
        }

        return $html;
    }

    private function inlineFigure(string $id, string $subject, string $location): string
    {
        $caption = $this->cap($subject, $location);
        $this->photo(["STORY-INLINE-$id · 3:2", $subject], $caption);

        return '<figure><div class="ttn-slot-inline"><span class="ttn-slot__code">STORY-INLINE-'.$id.' · 3:2</span>'
            .'<span class="ttn-slot__note">'.$this->h($subject).'</span></div>'
            .'<figcaption>'.$this->h($caption).'</figcaption></figure>';
    }

    private function storyData(): array
    {
        return [
            [
                'card' => ['STORY-CARD-01 · 16:9', 'Fairy Meadows trail detail, centre-weighted'],
                'hero' => ['STORY-HERO-01 · 21:9', 'Nanga Parbat from the meadow, evening alpenglow', 'Title sits lower-left'],
                'tag' => 'Trekking', 'title' => 'Three days on the Fairy Meadows trail', 'region' => 'Gilgit-Baltistan', 'location' => 'Fairy Meadows, Gilgit-Baltistan',
                'excerpt' => 'The walk up from Tato is steep, but the view of Nanga Parbat is the reward you came for.',
                'author' => 'Waseem', 'date' => '4 June 2026', 'readTime' => '6 min',
                'lead' => 'The walk up from Tato is steep, the tea houses are basic, and none of it matters. Fairy Meadows is the closest most of us will ever stand to a nine-summit skyline, and three days is exactly the right amount of time.',
                'body' => [
                    ['t' => 'p', 'text' => 'The jeep track from Raikot Bridge to Tato village takes about ninety minutes and has a reputation it mostly deserves. It is narrow, it is exposed, and the local drivers run it like a commute, because for them it is one. From Tato you walk: three hours of steady climbing through pine forest, the trail well worn by decades of feet.'],
                    ['t' => 'h2', 'text' => 'Day one: getting up'],
                    ['t' => 'p', 'text' => 'Leave Islamabad the night before and sleep in Chilas, or you will arrive at the meadow in the dark and miss the reveal. And the reveal is the point. You come out of the trees and Nanga Parbat is simply there, filling the southern sky, the Raikot face rising four thousand metres straight off the meadow floor.'],
                    ['t' => 'quote', 'text' => 'You come out of the trees and the mountain is simply there, filling half the sky.'],
                    ['t' => 'image', 'id' => '01A', 'subject' => 'First view of the Raikot face from the treeline, afternoon'],
                    ['t' => 'h3', 'text' => 'Where to sleep'],
                    ['t' => 'p', 'text' => 'The meadow has a handful of camps, wooden cottages and tent sites both. Book nothing; walk in, look at three, choose one. Prices are honest and the cook at the second camp past the pond makes the best dal on the mountain.'],
                    ['t' => 'h2', 'text' => 'Day two: to base camp and back'],
                    ['t' => 'p', 'text' => 'The walk to Nanga Parbat base camp is a full day out and back: through Beyal camp, past the shepherd huts, up the moraine. Carry:'],
                    ['t' => 'list', 'items' => ['Two litres of water each; there is one refill at Beyal', 'Sun cream, then more sun cream, the altitude is sincere', 'Cash for tea at the huts, they take nothing else', 'A headlamp, in case the day runs long']],
                    ['t' => 'p', 'text' => 'The glacier viewpoint is further than it looks and higher than it feels. Turn around at three whatever happens; the light on the walk back is the best of the day anyway.'],
                ],
                'essay' => ['STORY-ESSAY-01', 'full-bleed', ['Nanga Parbat at last light from the meadow, alpenglow on the summit ridge', 'Runs edge to edge of the viewport'], 'Nanga Parbat from Fairy Meadows, last light. Photo: Waseem Photography'],
                'after' => [
                    ['t' => 'h2', 'text' => 'Day three: leave slowly'],
                    ['t' => 'p', 'text' => 'Sunrise, breakfast, one last hour of doing nothing in front of the mountain. The walk down to Tato takes half the time and twice the knee cartilage. By evening you are back on the KKH, and the meadow already feels like something you made up.'],
                ],
            ],
            [
                'card' => ['STORY-CARD-02 · 16:9', 'The KKH as a ribbon, jeep at a bend'],
                'hero' => ['STORY-HERO-02 · 21:9', 'The road below the Passu cathedral ridge, morning', 'Title sits lower-left'],
                'tag' => 'Road trips', 'title' => 'Driving the Karakoram Highway, end to end', 'region' => 'Gilgit-Baltistan', 'location' => 'The KKH, Besham to Khunjerab',
                'excerpt' => 'From Besham to Khunjerab, a slow journey along the highest paved border crossing on earth.',
                'author' => 'Waseem', 'date' => '28 May 2026', 'readTime' => '9 min',
                'lead' => 'From Besham to Khunjerab is around 600 kilometres of the most consequential road in Pakistan. We drove it end to end, slowly, the way it deserves.',
                'body' => [
                    ['t' => 'p', 'text' => 'The Karakoram Highway does not warm up gently. North of Besham the Indus gorge closes in and the road becomes a ledge, with the river a grey ribbon far below. This is the stretch that earns the horror stories, and also the stretch where you understand what it took to build.'],
                    ['t' => 'h2', 'text' => 'The gorge days'],
                    ['t' => 'p', 'text' => 'Besham to Chilas is a long day of committed driving. Fuel at Dasu, tea at Sazin, and eyes on the road the whole way. After rain, ask ahead; the group thread will know which slides are cleared before the highway authority does.'],
                    ['t' => 'image', 'id' => '02A', 'subject' => 'The KKH cut into the Indus gorge wall, truck approaching'],
                    ['t' => 'h3', 'text' => 'Where the sky opens'],
                    ['t' => 'p', 'text' => 'At Thalichi the gorge exhales. Nanga Parbat appears on the right, absurdly large, and for the next hour three of the world’s great ranges, Himalaya, Karakoram, Hindu Kush, stand within a single windscreen.'],
                    ['t' => 'quote', 'text' => 'Three mountain ranges in one windscreen. No other road does this.'],
                    ['t' => 'h2', 'text' => 'Hunza and above'],
                    ['t' => 'p', 'text' => 'From Gilgit north the road is smooth and the driving becomes sightseeing: Rakaposhi at Ghulmet, the forts above Karimabad, the blue shock of Attabad. Stay two nights in Hunza minimum. The KKH rewards the unhurried.'],
                    ['t' => 'list', 'items' => ['Rakaposhi viewpoint, Ghulmet: tea with a 7,788 m backdrop', 'Attabad Lake: boats until late afternoon', 'Passu: the cathedral ridge and the old suspension bridge', 'Sost: last fuel and paperwork before the pass']],
                ],
                'essay' => ['STORY-ESSAY-02', 'full-bleed', ['The KKH as a ribbon below the Passu Cones, morning light', 'Runs edge to edge of the viewport'], 'The highway below the Passu cathedral ridge. Photo: Waseem Photography'],
                'after' => [
                    ['t' => 'h2', 'text' => 'The top'],
                    ['t' => 'p', 'text' => 'Khunjerab is 4,693 metres of wind and prayer flags and a border gate that feels like the edge of a map. You will be short of breath and grinning. Everyone is. Then you turn around, and the whole road happens again in reverse, better the second time.'],
                ],
            ],
            [
                'card' => ['STORY-CARD-03 · 16:9', 'Passu Cones, single strong subject'],
                'hero' => ['STORY-HERO-03 · 21:9', 'First light on the spires, foreground blue-dark', 'Title sits lower-left'],
                'tag' => 'Photography', 'title' => 'Chasing first light over Passu Cones', 'region' => 'Gilgit-Baltistan', 'location' => 'Passu, Gilgit-Baltistan',
                'excerpt' => 'You wake at four, you wait in the cold, and then the whole ridge turns to fire.',
                'author' => 'Waseem', 'date' => '19 May 2026', 'readTime' => '4 min',
                'lead' => 'You wake at four, you drive in the dark, you stand in a frozen field with a tripod and doubt every decision. Then the first light touches the spires, and photography makes sense again.',
                'body' => [
                    ['t' => 'p', 'text' => 'The Passu Cones, properly Tupopdan, are the most photographed skyline in Gojal, and almost everyone photographs them badly, at noon from the roadside, flat and hazy. The cones are a first-light subject. This is how to do them justice.'],
                    ['t' => 'h2', 'text' => 'The spot'],
                    ['t' => 'p', 'text' => 'Drive ten minutes north of Passu village and walk out onto the gravel bars of the river flat. The cones stand across the water with nothing between you and them. South-east aspect, which means the light comes to you.'],
                    ['t' => 'image', 'id' => '03A', 'subject' => 'Tripod on the river flat, cones dark against the pre-dawn sky'],
                    ['t' => 'h3', 'text' => 'The settings that matter'],
                    ['t' => 'list', 'items' => ['Arrive 40 minutes before sunrise, the colour starts early', 'Bracket, the contrast between lit spires and dark valley is brutal', 'A polariser does nothing at this angle, leave it in the bag', 'Gloves you can work buttons in, October mornings bite']],
                    ['t' => 'quote', 'text' => 'The whole ridge turns to fire, and it lasts four minutes.'],
                    ['t' => 'h2', 'text' => 'What actually happens'],
                    ['t' => 'p', 'text' => 'The summit catches first, a hard orange line on grey rock. Then the light walks down the spires one by one, and for a few minutes the whole serrated ridge burns while the valley stays in blue shadow. That contrast is the photograph. By full sunrise it is over, and the mountain goes back to being merely magnificent.'],
                ],
                'essay' => ['STORY-ESSAY-03', 'full-bleed', ['First light on the Passu spires, valley in blue shadow', 'Runs edge to edge of the viewport'], 'Tupopdan at first light, from the Passu river flat. Photo: Waseem Photography'],
                'after' => [
                    ['t' => 'p', 'text' => 'Afterwards, walk back to the village for breakfast. The bakery opens at seven, the chai is ready by five past, and the photographs on your screen will be the ones you came north for.'],
                ],
            ],
        ];
    }

    // ------------------------------------------------------------------ community

    private function seedCommunityPosts(array $destinations): void
    {
        $posts = [
            ['Camped at Rush Lake, the highest alpine lake I have ever seen. Three days of walking to get there and I would do it again tomorrow. Whoever in this group said the Nagar side approach is easier: you were right, thank you.', 'Sana K.', '8 July 2026', ['COMM-01 · 16:9', 'Camp at Rush Lake, candid'], 'Camp at Rush Lake, Gilgit-Baltistan'],
            ['Drove the KKH from Besham to Hunza last week. No words for the Passu Cones at sunrise.', 'Ali R.', '1 July 2026', null, null],
            ['Jeep loaded, leaving Skardu for Deosai at first light. Five years of reading this group and finally doing it myself.', 'Usman T.', '24 June 2026', ['COMM-02 · 16:9', 'Loaded jeep before departure'], 'Loaded jeep before departure, Skardu, Gilgit-Baltistan'],
            ['For everyone asking about Babusar in early June: open, but only 9am to 3pm and police were turning back sedans after rain. We crossed at noon, no problem in a 4x4. The lakes on the Naran side are still half frozen and absolutely worth the stop.', 'Mahnoor S.', '19 June 2026', null, null],
            ['Fairy Meadows with my father, 68 years old. He walked the whole way up.', 'Bilal K.', '11 June 2026', null, null],
            ['Group photo from the Khunjerab trip. Fourteen strangers from this group, one convoy, zero regrets.', 'Areeba J.', '2 June 2026', ['COMM-03 · 16:9', 'Group at a KKH viewpoint'], 'Group at a KKH viewpoint, Karakoram Highway, Gilgit-Baltistan'],
        ];
        foreach ($posts as $i => [$quote, $name, $date, $slot, $caption]) {
            $this->createPost([
                'facebook_url' => self::FB_GROUP,
                'quote' => $quote,
                'member_name' => $name,
                'posted_on' => $this->date($date),
                'photo_id' => $slot ? $this->photo($slot, $caption)->id : null,
                'show_on_home' => $i < 2, // the prototype homepage shows the first two
                'permission_confirmed' => true,
                'is_published' => true,
                'order' => $i,
            ]);
        }

        // Community tips on each destination page.
        $tips = collect($this->destinationData())->pluck('tips');
        foreach ($destinations as $i => $dest) {
            foreach ($tips[$i] as $k => [$quote, $name]) {
                $this->createPost([
                    'facebook_url' => self::FB_GROUP,
                    'quote' => $quote,
                    'member_name' => $name,
                    'destination_id' => $dest->id,
                    'show_on_home' => false,
                    'permission_confirmed' => true,
                    'is_published' => true,
                    'order' => 100 + $i * 10 + $k,
                ]);
            }
        }
    }

    // ------------------------------------------------------------------ about page

    private function seedTeam(): void
    {
        foreach (['Waseem', 'Hira', 'Bilal', 'Zoya'] as $i => $name) {
            $photo = $i === 0
                ? $this->photoCache['team-1']
                : $this->photo([sprintf('TEAM-%02d · 1:1', $i + 1), 'Head and shoulders'], "$name, To The North team portrait, Pakistan");
            TeamMember::create(['name' => $name, 'role_line' => 'Admin', 'photo_id' => $photo->id, 'order' => $i]);
        }
    }

    private function seedAboutPhotos(): void
    {
        $portfolio = [
            ['The road. KKH switchbacks', 'The KKH near Passu, Gilgit-Baltistan'],
            ['Water. Attabad or the Neelum', 'Attabad Lake, Gilgit-Baltistan'],
            ['A peak. Rakaposhi or Nanga Parbat', 'Rakaposhi from Ghulmet, Gilgit-Baltistan'],
            ['People, candid', 'Porters at Askole, Gilgit-Baltistan'],
            ['A village. Kalash or old Hunza', 'Bumburet, Kalash Valleys, Chitral'],
            ['Forest. Kumrat cedars', 'Kumrat forest, Khyber Pakhtunkhwa'],
        ];
        foreach ($portfolio as $i => [$note, $caption]) {
            $photo = $this->photo([sprintf('ABOUT-PF-%02d · 3:2', $i + 1), $note], $caption);
            AboutPhoto::create(['photo_id' => $photo->id, 'order' => $i]);
        }
    }
}
