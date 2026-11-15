/* To The North — Destination detail content. Index-aligned with TTN_DESTINATIONS. */
const DS = window.TTN_SLOT;

const strip = (id, subjects) => subjects.map((s, i) => ({
  image: DS(880, 587, [`DEST-STRIP-${id}-0${i + 1} · 3:2`, s], 24),
  caption: s.split(',')[0],
}));

window.TTN_DEST_DETAILS = [
  {
    hero: DS(1920, 823, ['DEST-HERO-01 · 21:9', 'Rakaposhi from Duikar at dawn, valley in shadow, summit lit', 'Name sits lower-left'], 22),
    stats: { season: 'April to October', altitude: '2,438 m', drive: '14–16 hours', difficulty: 'Easy, paved', permits: 'None for locals' },
    overview: [
      'Hunza is the valley most people picture when they imagine northern Pakistan: stone lanes climbing to Baltit Fort, apricot terraces stacked above the river, and Rakaposhi filling the southern sky.',
      'It is also the easiest of the great valleys to travel. The Karakoram Highway runs straight through it, rooms range from family homestays to proper hotels, and the people of Hunza have been hosting travellers for centuries.',
    ],
    pull: 'Every valley has a view. Hunza has a horizon.',
    gettingThere: [
      'The drive from Islamabad is long but entirely paved. Most travellers break it overnight at Besham or Chilas. Leave before dawn on day two and you get the light coming up the Indus gorge, which is worth the alarm.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Besham', dist: '270 km', time: '6–7 h', note: 'Hazara Motorway to Mansehra, then the N-35.' },
      { from: 'Besham', to: 'Chilas', dist: '260 km', time: '6 h', note: 'The roughest stretch. Landslide delays are common after rain; check the group for current status.' },
      { from: 'Chilas', to: 'Gilgit', dist: '130 km', time: '3 h', note: 'The gorge opens up. Nanga Parbat viewpoint at Thalichi.' },
      { from: 'Gilgit', to: 'Karimabad', dist: '100 km', time: '2.5 h', note: 'Smooth KKH. Stop at Ghulmet for the Rakaposhi viewpoint.' },
    ],
    map: { origin: 'Gilgit', o: [35.92, 74.31], d: [36.32, 74.65], coords: '36.32° N, 74.65° E' },
    status: { asOf: '12 July 2026', text: 'KKH open through to Khunjerab. Short holds at the Attabad tunnels for works traffic, mornings only.' },
    stay: [
      'Karimabad keeps you close to the forts and the bazaar; Duikar, twenty minutes up the hill, gets the sunrise. A valley-facing room in Duikar runs PKR 8,000 to 18,000 in June and July, roughly half that in shoulder season, and homestays in Karimabad start around 3,500. In high summer book ahead; spring and autumn you can turn up and choose.',
    ],
    blocks: [
      { kind: 'Places to visit', h: 'The two forts', p: 'Baltit and Altit are both restored and both worth the entry. Go to Altit first, it is quieter, then walk the old lanes between them.' },
      { kind: 'Viewpoints', h: 'Sunrise at Duikar', p: 'The classic for a reason. Rakaposhi, Diran and Golden Peak light up one by one. Arrive forty minutes before first light.' },
      { kind: 'Food to try', h: 'Chapshuro and apricot cake', p: 'Chapshuro from the bazaar ovens in Aliabad, walnut and apricot cake up in Duikar. Neither costs more than a few hundred rupees and both are better than they need to be.' },
      { kind: 'When not to go', h: 'Deep winter', p: 'January and February the valley is cold, half the hotels close, and the light is short. Unless you are coming for snow photography, wait for the blossom in late March.' },
    ],
    strip: strip('01', [
      'Baltit Fort above the bazaar, morning',
      'Boats on Attabad Lake, turquoise water',
      'Husseini suspension bridge, walker mid-span',
      'Apricot blossom terraces, late March',
    ]),
    tips: [
      { quote: 'Fuel up in Gilgit and carry cash. Cards work almost nowhere past Aliabad.', name: 'Usman T.' },
      { quote: 'Book Duikar early for June and July. The sunrise rooms go first.', name: 'Mahnoor S.' },
    ],
  },
  {
    hero: DS(1920, 823, ['DEST-HERO-02 · 21:9', 'Katpana dunes with the Indus behind, late afternoon', 'Name sits lower-left'], 22),
    stats: { season: 'April to October', altitude: '2,228 m', drive: '16–18 hours', difficulty: 'Easy to moderate', permits: 'NOC for some treks' },
    overview: [
      'Skardu sits where the Indus spreads wide between the Karakoram and the Deosai plateau. Every expedition to K2, and to the dozens of eight-thousand-metre neighbours, has staged out of this town.',
      'You do not need a rope to justify the trip. Cold deserts, the Kachura lakes, Shigar\u2019s restored fort and the road up to Deosai all sit within a day of the bazaar.',
    ],
    pull: 'Every expedition to K2 has walked out of this town.',
    gettingThere: [
      'By road it is the longest of the classic drives, and the Jaglot to Skardu section is the wildest gorge driving in the country, rebuilt and mostly excellent. There is also a one-hour flight from Islamabad that is spectacular when it flies and cancelled when it does not.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Chilas', dist: '530 km', time: '12–13 h', note: 'Break the journey here or at Besham.' },
      { from: 'Chilas', to: 'Jaglot', dist: '80 km', time: '2 h', note: 'Turn east onto the Skardu road at the Indus junction.' },
      { from: 'Jaglot', to: 'Skardu', dist: '165 km', time: '4 h', note: 'Rebuilt and fast, but respect the drops. No night driving.' },
    ],
    map: { origin: 'Gilgit', o: [35.92, 74.31], d: [35.30, 75.63], coords: '35.30° N, 75.63° E' },
    status: { asOf: '10 July 2026', text: 'Jaglot to Skardu road open and fast. Deosai track open from the Sadpara side; afternoons bring cloud, cross early.' },
    stay: [
      'Skardu town has the widest range of rooms in Baltistan, from backpacker guesthouses around PKR 2,500 a night to the fort hotel at Shigar an hour away, which runs 30,000 and up and earns it. The Kachura lakes are the quiet option, twenty minutes out.',
    ],
    blocks: [
      { kind: 'Places to visit', h: 'Katpana desert and Shigar', p: 'Dunes of pale sand at 2,300 metres, best in the last hour of light. Then Shigar: the 400-year-old fort is now a heritage hotel; come for lunch and the walled garden even if you sleep elsewhere.' },
      { kind: 'Treks and walks', h: 'Deosai in a day', p: 'The second-highest plateau on earth, an hour and a half up from town. A jeep from Skardu runs somewhere between PKR 8,000 and 14,000 for the day depending on the season and how hard you bargain. July for wildflowers, September for gold grass. Leave by six.' },
      { kind: 'Viewpoints', h: 'Sadpara Lake bend', p: 'Ten minutes up the Deosai road, the whole lake and the valley behind it. Free, unsigned, and better than most ticketed viewpoints in the country.' },
      { kind: 'When not to go', h: 'November to March', p: 'The flight barely flies, Deosai is shut under snow, and the town half-hibernates. Baltistan in winter is for people who already know it in summer.' },
    ],
    strip: strip('02', [
      'Katpana dunes at last light',
      'Upper Kachura Lake from the trail',
      'Shigar Fort courtyard, midday',
      'Deosai plateau in July wildflower',
    ]),
    tips: [
      { quote: 'The flight gets cancelled more than it flies. Book refundable and keep two buffer days.', name: 'Ahmed R.' },
      { quote: 'Deosai in one day from Skardu works, but leave by six and take warm layers even in July.', name: 'Fatima N.' },
    ],
  },
  {
    hero: DS(1920, 823, ['DEST-HERO-03 · 21:9', 'Satrangi Lake at full midday saturation, pines framing', 'Name sits lower-left'], 22),
    stats: { season: 'May to September', altitude: '2,880 m', drive: '13–14 h + jeep', difficulty: 'Moderate, jeep track', permits: 'None' },
    overview: [
      'Naltar is a pine-forested side valley above Gilgit, famous for three small lakes whose colour does not look real until you are standing at the shoreline. In winter it is Pakistan\u2019s oldest ski station.',
      'The valley is close enough to Gilgit and Hunza to visit in a day, which is exactly what most people do. Stay a night and you get the lakes to yourself before the jeeps arrive.',
    ],
    pull: 'The lakes do not look real until you are standing in front of them.',
    gettingThere: [
      'The paved road ends at Nomal. From there a rough, narrow jeep track climbs to Naltar Bala and on to the lakes. Hire a local jeep in Gilgit or Nomal; the fare is the best money of the trip.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Gilgit', dist: '590 km', time: '13–14 h', note: 'Standard KKH run, broken at Besham or Chilas.' },
      { from: 'Gilgit', to: 'Naltar Bala', dist: '34 km', time: '1.5 h', note: 'Jeep track past Nomal. A local jeep from Gilgit runs PKR 6,000 to 9,000 return. Do not attempt in a sedan.' },
      { from: 'Naltar Bala', to: 'The lakes', dist: '13 km', time: '45 min', note: 'Track ends at Satrangi. Walk the last stretch.' },
    ],
    map: { origin: 'Gilgit', o: [35.92, 74.31], d: [36.17, 74.18], coords: '36.17° N, 74.18° E' },
    status: { asOf: '8 July 2026', text: 'Jeep track open to Satrangi. Rough after last week’s rain past Naltar Bala; local jeeps running normally.' },
    stay: [
      'A handful of simple guesthouses sit in Naltar Bala among the pines, most between PKR 3,000 and 6,000 a night. Most visitors day-trip from Gilgit or Hunza, so rooms are rarely a problem outside July weekends.',
    ],
    blocks: [
      { kind: 'Viewpoints', h: 'The three lakes', p: 'Satrangi, Blue and Bodlok, strung along the upper valley. Morning light is calmest for reflections; by eleven the jeeps arrive and the surface goes busy.' },
      { kind: 'Treks and walks', h: 'Pakora Pass', p: 'For the ambitious: a two-day trek over to Ishkoman valley. Guide required, arranged in Naltar Bala.' },
      { kind: 'When not to go', h: 'Winter, unless you ski', p: 'December to February the valley is snowbound and the track is for locals only. The exception is the ski lifts: rental gear exists, expectations should be modest, the snow is real.' },
    ],
    strip: strip('03', [
      'Satrangi Lake colour bands, midday',
      'Pine forest track above Naltar Bala',
      'Winter lift line under fresh snow',
      'Jeep fording the Naltar stream',
    ]),
    tips: [
      { quote: 'Do not take a sedan past Nomal. The jeep fare is the best money you will spend.', name: 'Bilal K.' },
      { quote: 'Go early. By eleven the lakes are busy and the light has gone flat.', name: 'Areeba J.' },
    ],
  },
  {
    hero: DS(1920, 823, ['DEST-HERO-04 · 21:9', 'Bumburet village lanes under walnut trees, spring morning', 'Name sits lower-left'], 22),
    stats: { season: 'April to October', altitude: '1,900–2,200 m', drive: '10–12 hours', difficulty: 'Moderate', permits: 'Valley entry toll' },
    overview: [
      'The three Kalash valleys of Bumburet, Rumbur and Birir hold the last practising community of the Kalasha, whose language, festivals and wooden architecture predate everything around them.',
      'This is the one destination on this list where the culture, not the landscape, is the point. Come as a guest. The valleys are small, the communities are smaller, and how you behave matters.',
    ],
    pull: 'Chilam Joshi is not a performance. You are a guest at someone\u2019s spring.',
    gettingThere: [
      'The Lowari Tunnel keeps Chitral open year-round. From Chitral town the valleys are a short but slow drive up a gorge track from Ayun, fine in season for any careful driver.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Chitral', dist: '380 km', time: '9–10 h', note: 'Swat motorway, then the Lowari Tunnel. Fuel in Dir.' },
      { from: 'Chitral', to: 'Ayun', dist: '12 km', time: '30 min', note: 'Paved. The toll post is at the valley mouth.' },
      { from: 'Ayun', to: 'Bumburet', dist: '22 km', time: '1.5 h', note: 'Gorge track, single lane in places. Slow and fine.' },
    ],
    map: { origin: 'Chitral', o: [35.85, 71.79], d: [35.70, 71.70], coords: '35.70° N, 71.70° E' },
    stay: [
      'Family-run guesthouses in Bumburet and Rumbur, most with a dozen rooms or fewer and most between PKR 3,500 and 7,000 a night with dinner. Rumbur is the quieter valley and the better choice if the festivals are not your reason for coming.',
    ],
    blocks: [
      { kind: 'Festivals', h: 'Chilam Joshi, Uchau, Choimus', p: 'Spring, harvest and winter solstice. Dates shift; confirm in the group before booking. You are a guest at someone’s ritual, not an audience at a show, and the valleys fill fast in festival weeks.' },
      { kind: 'Places to visit', h: 'Kalasha Dur', p: 'The community museum in Bumburet, built with the community. Start here; the rest of the valley makes more sense afterwards.' },
      { kind: 'Treks and walks', h: 'Valley to valley on foot', p: 'Footpaths link Bumburet and Rumbur over the ridge. A full day, a local guide, and the best views of the Hindu Kush you will get.' },
      { kind: 'How to behave', h: 'Ask first, always', p: 'Photography of people only with permission, every single time. Dress modestly, buy locally, and remember the valleys are home to about four thousand people and host many times that each festival.' },
    ],
    strip: strip('04', [
      'Wooden houses stacked on the hillside, Rumbur',
      'Walnut trees over the village lane',
      'Festival dresses drying on a rail, detail',
      'The gorge track up from Ayun',
    ]),
    tips: [
      { quote: 'Ask before photographing people, every single time. It is their home, not a set.', name: 'Sana K.' },
      { quote: 'Rumbur is quieter than Bumburet and the guesthouse food is better. Fight me.', name: 'Hassan M.' },
    ],
  },
  {
    hero: DS(1920, 823, ['DEST-HERO-05 · 21:9', 'The river bend at Keran, green terraces both banks', 'Name sits lower-left'], 22),
    stats: { season: 'April to October', altitude: '1,400–2,500 m', drive: '6–7 hours', difficulty: 'Easy', permits: 'CNIC at check posts' },
    overview: [
      'Neelum is the closest of the great valleys to Islamabad, a single green corridor where the road, the villages and the border all follow one river. Wooden houses, walnut groves, terraced maize.',
      'It rewards slowness. The distances are short, the driving is easy, and the best of it, Arang Kel above Kel, you reach on foot anyway.',
    ],
    pull: 'The river is the road, the map and the reason.',
    gettingThere: [
      'Paved the whole way and the shortest drive on this list. The road follows the Neelum river along the Line of Control, so carry your CNIC; you will show it at check posts and it is routine. Foreign travellers currently need an NOC, arranged in advance.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Muzaffarabad', dist: '138 km', time: '3 h', note: 'Murree Expressway, then the valley road.' },
      { from: 'Muzaffarabad', to: 'Keran', dist: '93 km', time: '3 h', note: 'Paved but slow. The river does the navigating.' },
      { from: 'Keran', to: 'Sharda', dist: '45 km', time: '1.5 h', note: 'Greenest stretch of the valley.' },
      { from: 'Sharda', to: 'Kel', dist: '20 km', time: '1 h', note: 'Road ends here. Arang Kel is a two-hour walk or a short cable car plus climb.' },
    ],
    map: { origin: 'Muzaffarabad', o: [34.37, 73.47], d: [34.63, 73.90], coords: '34.63° N, 73.90° E' },
    stay: [
      'Riverside huts at Keran run PKR 6,000 to 12,000 a night in season, guesthouses at Sharda a little less, and the basic rooms up in Arang Kel are cheaper still for those who walk up. Summer weekends fill; midweek the valley is yours.',
    ],
    blocks: [
      { kind: 'Places to visit', h: 'Arang Kel and Sharda Peeth', p: 'The meadow village above Kel, reached on foot; stay the night, the day-trippers leave by four. And the ruins of a mediaeval university above the river at Sharda, five minutes from the bazaar and older than everything around it.' },
      { kind: 'Viewpoints', h: 'The river at Keran', p: 'Both banks terraced and green, the river loud between them. The huts look straight across the water; sunset is the hour.' },
      { kind: 'When not to go', h: 'Monsoon weeks', p: 'Late July and August bring rain, slips and closures to the valley road. If the forecast is heavy, wait; the valley does not reward urgency.' },
    ],
    strip: strip('05', [
      'Arang Kel meadow and wooden houses',
      'Sharda Peeth ruins above the river',
      'The Neelum at Keran, both banks',
      'Cable car crossing above Kel',
    ]),
    tips: [
      { quote: 'Carry your CNIC. You show it at every check post and it is no drama at all.', name: 'Adeel M.' },
      { quote: 'Stay the night in Arang Kel. The valley after the day-trippers leave is a different place.', name: 'Kiran B.' },
    ],
  },
  {
    hero: DS(1920, 823, ['DEST-HERO-06 · 21:9', 'Cedar forest with dawn mist on the Panjkora', 'Name sits lower-left'], 22),
    stats: { season: 'May to September', altitude: '2,400 m', drive: '8–9 h + jeep', difficulty: 'Moderate, jeep track', permits: 'None' },
    overview: [
      'Kumrat is a valley of old-growth deodar cedar along the Panjkora river in Upper Dir. The forest is the destination: trees ten storeys tall, meadows between them, and the river loud through all of it.',
      'It stayed off the map longer than the Gilgit-Baltistan valleys and still feels it. Facilities are simple, the track past Thal is rough, and that is precisely the appeal.',
    ],
    pull: 'You hear Kumrat before you see it. The river never stops.',
    gettingThere: [
      'The Swat motorway has cut the journey down: a fast run to Chakdara, then the Dir road up to Thal. Past Thal the road becomes a river-bed jeep track into the forest. Park in Thal and hire local if your car is not built for it.',
    ],
    legs: [
      { from: 'Islamabad', to: 'Chakdara', dist: '220 km', time: '3 h', note: 'Motorway the whole way.' },
      { from: 'Chakdara', to: 'Thal', dist: '130 km', time: '5 h', note: 'Slow mountain road through Dir. Fuel at Dir town.' },
      { from: 'Thal', to: 'Kumrat forest', dist: '12 km', time: '1 h', note: 'River-bed jeep track. 4x4 or local jeep only; a local jeep from Thal runs PKR 2,500 to 4,000 each way.' },
    ],
    map: { origin: 'Peshawar', o: [34.01, 71.58], d: [35.53, 72.22], coords: '35.53° N, 72.22° E' },
    status: { asOf: '9 July 2026', text: 'Track past Thal churned by rain but local jeeps running. Park in Thal if your car sits low.' },
    stay: [
      'Camps and huts in the forest itself, mostly PKR 3,000 to 6,000 a tent with bedding, and simple hotels back in Thal for less. Camp in the forest if you can; falling asleep to the Panjkora is the point of coming.',
    ],
    blocks: [
      { kind: 'Treks and walks', h: 'The forest itself', p: 'Flat, shaded trails along the river between the camps and the meadows. No guide needed, no destination required; the trees are the itinerary.' },
      { kind: 'Viewpoints', h: 'Do Kala Chashma', p: 'The twin springs at the head of the valley, an easy jeep-and-walk morning from any of the camps.' },
      { kind: 'Places to visit', h: 'Katora Lake, via Jahaz Banda', p: 'Across the ridge: a separate jeep leg and a proper day\u2019s trek. Plan it as its own trip, not a Kumrat add-on.' },
      { kind: 'When not to go', h: 'After heavy rain', p: 'The track is a river bed and behaves like one. If it has rained hard for two days, the forest will still be there next week.' },
    ],
    strip: strip('06', [
      'Deodar trunks in dawn mist',
      'The Panjkora white over boulders',
      'Camp lights under the cedars, dusk',
      'Meadow clearing, horses grazing',
    ]),
    tips: [
      { quote: 'Camp in the forest, not Thal. Falling asleep to the Panjkora is the whole point.', name: 'Zara I.' },
      { quote: 'The track past Thal eats low cars. Park in Thal, take a local jeep, thank me later.', name: 'Omar F.' },
    ],
  },
];
