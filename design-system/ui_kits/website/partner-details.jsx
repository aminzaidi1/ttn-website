/* To The North — Partner detail content. Index-aligned with TTN_PARTNERS. */
const PS = window.TTN_SLOT;

const gal = (id, subjects) => subjects.map((s, i) => ({
  image: PS(880, 587, [`PARTNER-GAL-${id}-0${i + 1} · 3:2`, s], 24),
  caption: s,
}));

window.TTN_PARTNER_DETAILS = [
  {
    hero: PS(1920, 1080, ['PARTNER-HERO-01 · 16:9', 'Trekking group on Baltoro moraine, Trango or K2 behind', 'Name sits lower-left'], 22),
    since: 'March 2022',
    verdict: 'We have walked with these guides to Concordia and back, twice. They hire local, they pay porters properly, and when the weather turned on us at Goro II it was their judgement, not ours, that got everyone down happy. We stand behind them without hesitation.',
    overview: [
      'Karakoram Expeditions has been staging treks out of Skardu since 2009, run by a family from Hushe whose fathers and grandfathers carried loads for the first expeditions to Masherbrum. Their guides grew up beneath the mountains they now lead people through.',
      'They keep groups small, twelve at most, and they turn down more bookings than they accept in peak season. That restraint is exactly why we certify them.',
    ],
    offers: [
      { icon: 'mountain', text: 'K2 base camp and Concordia treks, 14 to 21 days' },
      { icon: 'mapPin', text: 'Shorter valley treks: Hushe, Thalle La, Amin Brakk base' },
      { icon: 'users', text: 'Licensed local guides, fair-wage porter contracts' },
      { icon: 'fileCheck', text: 'NOC and permit handling for restricted zones' },
      { icon: 'car', text: 'Skardu transfers and expedition logistics' },
    ],
    gallery: gal('01', [
      'Group crossing the Baltoro, roped',
      'Camp at Concordia, K2 in evening light',
      'Porters and guides at the Hushe office',
      'Mess tent dinner, headlamps on',
      'Trango Towers from Urdukas camp',
    ]),
    address: 'Yadgar Chowk, Skardu, Gilgit-Baltistan',
    phone: '+92 355 5810 214',
    email: 'trek@karakoramexpeditions.pk',
    map: { origin: 'Skardu bazaar', coords: '35.29° N, 75.64° E' },
    cta: 'Planning a trek? Talk to them early. The good departures fill by February.',
  },
  {
    hero: PS(1920, 1080, ['PARTNER-HERO-02 · 16:9', 'The Duikar terrace at sunrise, Rakaposhi on the horizon', 'Name sits lower-left'], 22),
    since: 'August 2021',
    verdict: 'We have photographed a hundred sunrises from this terrace and it has never once been ordinary. The rooms are honest, the kitchen is better than it needs to be, and the family who run it treat our community like their own guests. This is where we send people first.',
    overview: [
      'Eagle Nest sits at the top of Duikar village, the highest point in the Hunza bowl you can drive to, with a terrace that looks across to Rakaposhi, Diran and Lady Finger. It has been run by the same family since it opened.',
      'It is not a luxury hotel and does not pretend to be. What it has is position, warmth and the best sunrise in northern Pakistan, which is worth more than a minibar.',
    ],
    offers: [
      { icon: 'bed', text: '24 rooms, valley-facing and garden-facing' },
      { icon: 'utensils', text: 'Kitchen serving Hunza staples and the famous apricot cake' },
      { icon: 'camera', text: 'Sunrise terrace, open to photographers before dawn' },
      { icon: 'car', text: 'Jeep pickup from Karimabad and Aliabad' },
    ],
    gallery: gal('02', [
      'The terrace at first light, tripods up',
      'Valley-facing room, morning',
      'Rakaposhi from the garden wall',
      'Dinner service in the stone dining room',
    ]),
    address: 'Duikar village, above Altit, Hunza',
    phone: '+92 346 9532 771',
    email: 'stay@eaglenesthunza.pk',
    map: { origin: 'Karimabad', coords: '36.33° N, 74.68° E' },
    cta: 'June and July book out weeks ahead. Shoulder season you will have the terrace to yourself.',
  },
  {
    hero: PS(1920, 1080, ['PARTNER-HERO-03 · 16:9', 'A 4x4 fording a stream on the Deosai track, spray frozen', 'Name sits lower-left'], 22),
    since: 'June 2023',
    verdict: 'Their drivers know every stream crossing on Deosai by name and every mood the weather has. We have used them for three seasons of shoots, in conditions we would not drive ourselves, and the jeeps come back every time. Careful people with well-kept machines.',
    overview: [
      'Northern Jeep Service runs a fleet of a dozen 4x4s out of Skardu, driven by men who have spent their working lives on the Deosai, Shigar and Basho tracks. The jeeps are old Land Cruisers, maintained obsessively, because out there maintenance is safety.',
      'They quote flat rates by route, not by negotiation, which alone puts them ahead of most of the trade.',
    ],
    offers: [
      { icon: 'car', text: 'Deosai crossings, Skardu to Astore, full day' },
      { icon: 'mapPin', text: 'Shigar, Khaplu, Basho and Kachura transfers' },
      { icon: 'mountain', text: 'Trek drop-offs and pickups, Hushe and Askole' },
      { icon: 'users', text: 'English-speaking drivers on request' },
    ],
    gallery: gal('03', [
      'Land Cruiser on the Deosai plateau track',
      'Stream crossing, water at the axles',
      'The workshop yard in Skardu, fleet lined up',
      'Driver and jeep above Sadpara Lake',
    ]),
    address: 'Airport Road, Skardu, Gilgit-Baltistan',
    phone: '+92 344 9218 605',
    email: 'bookings@northernjeep.pk',
    map: { origin: 'Skardu airport', coords: '35.34° N, 75.54° E' },
    cta: 'Tell them your route and your dates. They will tell you honestly whether the track is open.',
  },
];
