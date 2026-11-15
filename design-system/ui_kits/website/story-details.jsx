/* To The North — Story article content. Index-aligned with TTN_STORIES.
   Body blocks: p, h2, h3, quote, image (src+caption), list (items). */
const SS = window.TTN_SLOT;

const inlineImg = (id, subject) => ({
  t: 'image',
  src: SS(880, 587, [`STORY-INLINE-${id} · 3:2`, subject], 24),
  caption: subject.split(',')[0],
});

window.TTN_STORY_DETAILS = [
  {
    lead: 'The walk up from Tato is steep, the tea houses are basic, and none of it matters. Fairy Meadows is the closest most of us will ever stand to a nine-summit skyline, and three days is exactly the right amount of time.',
    body: [
      { t: 'p', text: 'The jeep track from Raikot Bridge to Tato village takes about ninety minutes and has a reputation it mostly deserves. It is narrow, it is exposed, and the local drivers run it like a commute, because for them it is one. From Tato you walk: three hours of steady climbing through pine forest, the trail well worn by decades of feet.' },
      { t: 'h2', text: 'Day one: getting up' },
      { t: 'p', text: 'Leave Islamabad the night before and sleep in Chilas, or you will arrive at the meadow in the dark and miss the reveal. And the reveal is the point. You come out of the trees and Nanga Parbat is simply there, filling the southern sky, the Raikot face rising four thousand metres straight off the meadow floor.' },
      { t: 'quote', text: 'You come out of the trees and the mountain is simply there, filling half the sky.' },
      inlineImg('01A', 'First view of the Raikot face from the treeline, afternoon'),
      { t: 'h3', text: 'Where to sleep' },
      { t: 'p', text: 'The meadow has a handful of camps, wooden cottages and tent sites both. Book nothing; walk in, look at three, choose one. Prices are honest and the cook at the second camp past the pond makes the best dal on the mountain.' },
      { t: 'h2', text: 'Day two: to base camp and back' },
      { t: 'p', text: 'The walk to Nanga Parbat base camp is a full day out and back: through Beyal camp, past the shepherd huts, up the moraine. Carry:' },
      { t: 'list', items: ['Two litres of water each; there is one refill at Beyal', 'Sun cream, then more sun cream, the altitude is sincere', 'Cash for tea at the huts, they take nothing else', 'A headlamp, in case the day runs long'] },
      { t: 'p', text: 'The glacier viewpoint is further than it looks and higher than it feels. Turn around at three whatever happens; the light on the walk back is the best of the day anyway.' },
    ],
    essay: {
      src: SS(1920, 900, ['STORY-ESSAY-01 · full-bleed', 'Nanga Parbat at last light from the meadow, alpenglow on the summit ridge', 'Runs edge to edge of the viewport'], 22),
      caption: 'Nanga Parbat from Fairy Meadows, last light. Photo: Waseem Photography',
    },
    after: [
      { t: 'h2', text: 'Day three: leave slowly' },
      { t: 'p', text: 'Sunrise, breakfast, one last hour of doing nothing in front of the mountain. The walk down to Tato takes half the time and twice the knee cartilage. By evening you are back on the KKH, and the meadow already feels like something you made up.' },
    ],
    authorBio: 'Founder of To The North. Photographing the northern valleys since 2018.',
  },
  {
    lead: 'From Besham to Khunjerab is around 600 kilometres of the most consequential road in Pakistan. We drove it end to end, slowly, the way it deserves.',
    body: [
      { t: 'p', text: 'The Karakoram Highway does not warm up gently. North of Besham the Indus gorge closes in and the road becomes a ledge, with the river a grey ribbon far below. This is the stretch that earns the horror stories, and also the stretch where you understand what it took to build.' },
      { t: 'h2', text: 'The gorge days' },
      { t: 'p', text: 'Besham to Chilas is a long day of committed driving. Fuel at Dasu, tea at Sazin, and eyes on the road the whole way. After rain, ask ahead; the group thread will know which slides are cleared before the highway authority does.' },
      inlineImg('02A', 'The KKH cut into the Indus gorge wall, truck approaching'),
      { t: 'h3', text: 'Where the sky opens' },
      { t: 'p', text: 'At Thalichi the gorge exhales. Nanga Parbat appears on the right, absurdly large, and for the next hour three of the world\u2019s great ranges, Himalaya, Karakoram, Hindu Kush, stand within a single windscreen.' },
      { t: 'quote', text: 'Three mountain ranges in one windscreen. No other road does this.' },
      { t: 'h2', text: 'Hunza and above' },
      { t: 'p', text: 'From Gilgit north the road is smooth and the driving becomes sightseeing: Rakaposhi at Ghulmet, the forts above Karimabad, the blue shock of Attabad. Stay two nights in Hunza minimum. The KKH rewards the unhurried.' },
      { t: 'list', items: ['Rakaposhi viewpoint, Ghulmet: tea with a 7,788 m backdrop', 'Attabad Lake: boats until late afternoon', 'Passu: the cathedral ridge and the old suspension bridge', 'Sost: last fuel and paperwork before the pass'] },
    ],
    essay: {
      src: SS(1920, 900, ['STORY-ESSAY-02 · full-bleed', 'The KKH as a ribbon below the Passu Cones, morning light', 'Runs edge to edge of the viewport'], 22),
      caption: 'The highway below the Passu cathedral ridge. Photo: Waseem Photography',
    },
    after: [
      { t: 'h2', text: 'The top' },
      { t: 'p', text: 'Khunjerab is 4,693 metres of wind and prayer flags and a border gate that feels like the edge of a map. You will be short of breath and grinning. Everyone is. Then you turn around, and the whole road happens again in reverse, better the second time.' },
    ],
    authorBio: 'Founder of To The North. Photographing the northern valleys since 2018.',
  },
  {
    lead: 'You wake at four, you drive in the dark, you stand in a frozen field with a tripod and doubt every decision. Then the first light touches the spires, and photography makes sense again.',
    body: [
      { t: 'p', text: 'The Passu Cones, properly Tupopdan, are the most photographed skyline in Gojal, and almost everyone photographs them badly, at noon from the roadside, flat and hazy. The cones are a first-light subject. This is how to do them justice.' },
      { t: 'h2', text: 'The spot' },
      { t: 'p', text: 'Drive ten minutes north of Passu village and walk out onto the gravel bars of the river flat. The cones stand across the water with nothing between you and them. South-east aspect, which means the light comes to you.' },
      inlineImg('03A', 'Tripod on the river flat, cones dark against the pre-dawn sky'),
      { t: 'h3', text: 'The settings that matter' },
      { t: 'list', items: ['Arrive 40 minutes before sunrise, the colour starts early', 'Bracket, the contrast between lit spires and dark valley is brutal', 'A polariser does nothing at this angle, leave it in the bag', 'Gloves you can work buttons in, October mornings bite'] },
      { t: 'quote', text: 'The whole ridge turns to fire, and it lasts four minutes.' },
      { t: 'h2', text: 'What actually happens' },
      { t: 'p', text: 'The summit catches first, a hard orange line on grey rock. Then the light walks down the spires one by one, and for a few minutes the whole serrated ridge burns while the valley stays in blue shadow. That contrast is the photograph. By full sunrise it is over, and the mountain goes back to being merely magnificent.' },
    ],
    essay: {
      src: SS(1920, 900, ['STORY-ESSAY-03 · full-bleed', 'First light on the Passu spires, valley in blue shadow', 'Runs edge to edge of the viewport'], 22),
      caption: 'Tupopdan at first light, from the Passu river flat. Photo: Waseem Photography',
    },
    after: [
      { t: 'p', text: 'Afterwards, walk back to the village for breakfast. The bakery opens at seven, the chai is ready by five past, and the photographs on your screen will be the ones you came north for.' },
    ],
    authorBio: 'Founder of To The North. Photographing the northern valleys since 2018.',
  },
];
