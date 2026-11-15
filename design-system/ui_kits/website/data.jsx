/* To The North — Website UI kit: sample content.
   Imagery is labelled placeholder slots (see slots.js + /PHOTOGRAPHY-BRIEF.md).
   Each slot names its brief ID, ratio and intended subject. */
const S = window.TTN_SLOT;

const PH = {
  hero: S(1920, 1080, [
    'HOME-01 · Hero · full-viewport',
    'Attabad Lake, golden hour',
    'Headline sits lower-left. Keep lower-left quiet.',
  ], 22),
  portrait: S(760, 1014, [
    'FOUNDER-01 · 3:4',
    'Waseem in the field, camera in hand',
    'Open shade, mountains soft behind',
  ], 20),
  team: S(240, 240, ['TEAM · 1:1', 'Head and shoulders'], 17),
  community: S(1160, 652, [
    'COMM · 16:9',
    'Candid traveller moment, named location',
  ], 19),
  portfolio: [
    { src: S(800, 533, ['ABOUT-PF-01 · 3:2', 'The road. KKH switchbacks'], 28), caption: 'The KKH near Passu, Gilgit-Baltistan' },
    { src: S(800, 533, ['ABOUT-PF-02 · 3:2', 'Water. Attabad or the Neelum'], 28), caption: 'Attabad Lake, Gilgit-Baltistan' },
    { src: S(800, 533, ['ABOUT-PF-03 · 3:2', 'A peak. Rakaposhi or Nanga Parbat'], 28), caption: 'Rakaposhi from Ghulmet, Gilgit-Baltistan' },
    { src: S(800, 533, ['ABOUT-PF-04 · 3:2', 'People, candid'], 28), caption: 'Porters at Askole, Gilgit-Baltistan' },
    { src: S(800, 533, ['ABOUT-PF-05 · 3:2', 'A village. Kalash or old Hunza'], 28), caption: 'Bumburet, Kalash Valleys, Chitral' },
    { src: S(800, 533, ['ABOUT-PF-06 · 3:2', 'Forest. Kumrat cedars'], 28), caption: 'Kumrat forest, Khyber Pakhtunkhwa' },
  ],
};

const STORIES = [
  {
    image: S(800, 450, ['STORY-CARD-01 · 16:9', 'Fairy Meadows trail detail, centre-weighted'], 26),
    hero: S(1920, 820, ['STORY-HERO-01 · 21:9', 'Nanga Parbat from the meadow, evening alpenglow', 'Title sits lower-left'], 22),
    tag: 'Trekking', title: 'Three days on the Fairy Meadows trail', region: 'Gilgit-Baltistan', location: 'Fairy Meadows, Gilgit-Baltistan',
    excerpt: 'The walk up from Tato is steep, but the view of Nanga Parbat is the reward you came for.',
    author: 'Waseem', date: '4 June 2026', readTime: '6 min',
  },
  {
    image: S(800, 450, ['STORY-CARD-02 · 16:9', 'The KKH as a ribbon, jeep at a bend'], 26),
    hero: S(1920, 820, ['STORY-HERO-02 · 21:9', 'The road below the Passu cathedral ridge, morning', 'Title sits lower-left'], 22),
    tag: 'Road trips', title: 'Driving the Karakoram Highway, end to end', region: 'Gilgit-Baltistan', location: 'The KKH, Besham to Khunjerab',
    excerpt: 'From Besham to Khunjerab, a slow journey along the highest paved border crossing on earth.',
    author: 'Waseem', date: '28 May 2026', readTime: '9 min',
  },
  {
    image: S(800, 450, ['STORY-CARD-03 · 16:9', 'Passu Cones, single strong subject'], 26),
    hero: S(1920, 820, ['STORY-HERO-03 · 21:9', 'First light on the spires, foreground blue-dark', 'Title sits lower-left'], 22),
    tag: 'Photography', title: 'Chasing first light over Passu Cones', region: 'Gilgit-Baltistan', location: 'Passu, Gilgit-Baltistan',
    excerpt: 'You wake at four, you wait in the cold, and then the whole ridge turns to fire.',
    author: 'Waseem', date: '19 May 2026', readTime: '4 min',
  },
];

const DESTINATIONS = [
  { image: S(800, 820, ['DEST-CARD-01 · near-square', 'Rakaposhi from Duikar, dawn', 'Name bottom-left, tag top-right'], 26),
    name: 'Hunza Valley', region: 'Gilgit-Baltistan', blurb: 'Apricot orchards, ancient forts and Rakaposhi on the horizon.' },
  { image: S(800, 820, ['DEST-CARD-02 · near-square', 'Katpana dunes with the Indus, late sun', 'Name bottom-left, tag top-right'], 26),
    name: 'Skardu', region: 'Gilgit-Baltistan', blurb: 'The gateway to the Karakoram and the great cold deserts.' },
  { image: S(800, 820, ['DEST-CARD-03 · near-square', 'Satrangi Lake blue-green water, pines', 'Name bottom-left, tag top-right'], 26),
    name: 'Naltar Valley', region: 'Gilgit-Baltistan', blurb: 'Pine forests and three lakes the colour of stained glass.' },
  { image: S(800, 820, ['DEST-CARD-04 · near-square', 'Bumburet village lanes, spring morning', 'Name bottom-left, tag top-right'], 26),
    name: 'Kalash Valleys', region: 'Chitral', blurb: 'Living traditions in the high valleys of the Hindu Kush.' },
  { image: S(800, 820, ['DEST-CARD-05 · near-square', 'The river bend at Keran, green terraces', 'Name bottom-left, tag top-right'], 26),
    name: 'Neelum Valley', region: 'Azad Kashmir', blurb: 'A river-cut valley of green terraces and wooden villages.' },
  { image: S(800, 820, ['DEST-CARD-06 · near-square', 'Cedar forest, dawn mist on the Panjkora', 'Name bottom-left, tag top-right'], 26),
    name: 'Kumrat Valley', region: 'Khyber Pakhtunkhwa', blurb: 'Cedar forests and meadows along the Panjkora river.' },
];

const COMMUNITY = [
  { quote: 'Camped at Rush Lake, the highest alpine lake I have ever seen. Three days of walking to get there and I would do it again tomorrow. Whoever in this group said the Nagar side approach is easier: you were right, thank you.', name: 'Sana K.', date: '8 July 2026', fb: 'https://facebook.com/groups/tothenorth', image: S(800, 450, ['COMM-01 · 16:9', 'Camp at Rush Lake, candid'], 26) },
  { quote: 'Drove the KKH from Besham to Hunza last week. No words for the Passu Cones at sunrise.', name: 'Ali R.', date: '1 July 2026', fb: 'https://facebook.com/groups/tothenorth' },
  { quote: 'Jeep loaded, leaving Skardu for Deosai at first light. Five years of reading this group and finally doing it myself.', name: 'Usman T.', date: '24 June 2026', fb: 'https://facebook.com/groups/tothenorth', image: S(800, 450, ['COMM-02 · 16:9', 'Loaded jeep before departure'], 26) },
  { quote: 'For everyone asking about Babusar in early June: open, but only 9am to 3pm and police were turning back sedans after rain. We crossed at noon, no problem in a 4x4. The lakes on the Naran side are still half frozen and absolutely worth the stop.', name: 'Mahnoor S.', date: '19 June 2026', fb: 'https://facebook.com/groups/tothenorth' },
  { quote: 'Fairy Meadows with my father, 68 years old. He walked the whole way up.', name: 'Bilal K.', date: '11 June 2026', fb: 'https://facebook.com/groups/tothenorth' },
  { quote: 'Group photo from the Khunjerab trip. Fourteen strangers from this group, one convoy, zero regrets.', name: 'Areeba J.', date: '2 June 2026', fb: 'https://facebook.com/groups/tothenorth', image: S(800, 450, ['COMM-03 · 16:9', 'Group at a KKH viewpoint'], 26) },
];

const PARTNERS = [
  { name: 'Karakoram Expeditions', category: 'Tour Operator', location: 'Skardu, Gilgit-Baltistan', region: 'Gilgit-Baltistan', thumb: S(160, 160, ['THUMB', 'Guides'], 15), description: 'Small-group treks to K2 base camp, led by local guides who grew up in these valleys.' },
  { name: 'Eagle Nest Hotel', category: 'Hotel', location: 'Duikar, Hunza', region: 'Gilgit-Baltistan', thumb: S(160, 160, ['THUMB', 'Terrace'], 15), description: 'Sunrise views over Rakaposhi and Lady Finger, a short drive above Karimabad.' },
  { name: 'Northern Jeep Service', category: 'Jeep Service', location: 'Skardu, Gilgit-Baltistan', region: 'Gilgit-Baltistan', thumb: S(160, 160, ['THUMB', 'Jeep'], 15), description: 'Reliable 4x4 transfers to Deosai, Shigar and the Shangrila lakes.' },
];

const REGIONS = ['All', 'Gilgit-Baltistan', 'Khyber Pakhtunkhwa', 'Azad Kashmir', 'Chitral'];

Object.assign(window, { TTN_STORIES: STORIES, TTN_DESTINATIONS: DESTINATIONS, TTN_COMMUNITY: COMMUNITY, TTN_PARTNERS: PARTNERS, TTN_REGIONS: REGIONS, TTN_PH: PH });
