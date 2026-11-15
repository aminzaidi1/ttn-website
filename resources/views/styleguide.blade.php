{{-- Working reference: every component in every variant, to compare against design-system/. Not a public page. --}}
@php
    $colors = [
        ['--ttn-black', 'TTN Black', 'Primary surface'], ['--ttn-deep', 'TTN Deep', 'Alternate surface, cards'],
        ['--ttn-gold', 'TTN Gold', 'The one accent'], ['--ttn-amber', 'Amber', 'Gold hover'],
        ['--ttn-white', 'White', 'Body text'], ['--ttn-grey-light', 'Light grey', 'Secondary text over one line'],
        ['--ttn-grey', 'Grey', 'Short secondary text'], ['--ttn-slate', 'Slate', 'Borders, slot fill'],
        ['--ttn-border-lift', 'Border lift', 'Hover border'], ['--ttn-green', 'Green', 'Nature content only'],
        ['--ttn-error', 'Error', 'Form errors only'],
    ];
    $spaces = ['--space-1', '--space-2', '--space-3', '--space-4', '--space-6', '--space-8', '--space-12', '--space-16'];
    $slot = fn ($code, $note, $caption = 'Placeholder') => new \App\Models\Photo(['slot_code' => $code, 'slot_note' => $note, 'caption' => $caption]);
@endphp
<x-ttn.layout title="Styleguide" noindex>
    <x-ttn.section>
        <div class="ttn-eyebrow" style="margin-bottom: var(--space-2)">Working reference</div>
        <h1 class="ttn-h1">Styleguide</h1>
        <p class="ttn-lede" style="margin-top: var(--space-2)">Every component in every variant, built from the design tokens. Compare against the design system. Imagery is labelled placeholder slots until the real photographs arrive.</p>

        <div class="sg-block" style="margin-top: var(--space-6)">
            <h2 class="ttn-h2">Colour</h2>
            <p class="sg-note">Two surfaces only, alternating. One gold element per section.</p>
            <div class="sg-swatches">
                @foreach ($colors as [$var, $name, $use])
                    <div class="sg-swatch"><div class="sg-swatch__chip" style="background: var({{ $var }})"></div><div class="sg-swatch__meta"><strong>{{ $name }}</strong>{{ $var }}<br>{{ $use }}</div></div>
                @endforeach
            </div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Type</h2>
            <p class="sg-note">Playfair Display for anything editorial. Inter for body and interface.</p>
            <span class="sg-label">Hero title</span>
            <div class="ttn-hero__title">The mountains are waiting. So is the road.</div>
            <span class="sg-label">Page title</span>
            <div class="ttn-h1">Three days on the Fairy Meadows trail</div>
            <span class="sg-label">Section title</span>
            <div class="ttn-h2">Explore destinations</div>
            <span class="sg-label">Pull quote</span>
            <div class="ttn-pullquote">Every valley has a view. Hunza has a horizon.</div>
            <span class="sg-label">Lede</span>
            <p class="ttn-lede">The walk up from Tato is steep, the tea houses are basic, and none of it matters. Fairy Meadows is the closest most of us will ever stand to a nine-summit skyline.</p>
            <span class="sg-label">Body, long form</span>
            <div class="ttn-prose">
                <p>The jeep track from Raikot Bridge to Tato village takes about ninety minutes and has a reputation it mostly deserves. It is narrow, it is exposed, and the local drivers run it like a commute, because for them it is one.</p>
                <h2>Day one: getting up</h2>
                <p>Leave Islamabad the night before and sleep in Chilas, or you will arrive at the meadow in the dark and <a href="#">miss the reveal</a>.</p>
                <blockquote><p>You come out of the trees and the mountain is simply there, filling half the sky.</p></blockquote>
                <ul><li>Carry cash. There is no ATM after Chilas.</li><li>A valley-facing room runs PKR 8,000 to 18,000 in high season.</li></ul>
            </div>
            <span class="sg-label">Eyebrow, meta, caption</span>
            <div class="sg-row"><span class="ttn-eyebrow">Our story</span><span class="ttn-meta"><x-ttn.icon name="mapPin" :size="14" />Kharfaq Lake, Gilgit-Baltistan</span><span class="ttn-credit">Photo: Waseem Photography</span></div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Spacing</h2>
            <p class="sg-note">8px base unit.</p>
            @foreach ($spaces as $s)<div class="sg-space"><i style="width: var({{ $s }})"></i>{{ $s }}</div>@endforeach
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Button</h2>
            <p class="sg-note">Primary is gold fill. Secondary is the gold ghost. Text is the gold link.</p>
            <div class="sg-row"><x-ttn.button size="lg" iconRight="arrowRight">Read the story</x-ttn.button><x-ttn.button>Subscribe</x-ttn.button><x-ttn.button size="sm">Subscribe</x-ttn.button><x-ttn.button disabled>Disabled</x-ttn.button></div>
            <div class="sg-row"><x-ttn.button variant="secondary" size="lg">Join the group</x-ttn.button><x-ttn.button variant="secondary">Contact</x-ttn.button><x-ttn.button variant="secondary" size="sm">Clear filter</x-ttn.button><x-ttn.button variant="secondary" disabled>Disabled</x-ttn.button></div>
            <div class="sg-row"><x-ttn.button variant="text" iconRight="arrowRight" href="#">View all stories</x-ttn.button><x-ttn.gold-link href="#">Read the full story</x-ttn.gold-link></div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Tag</h2>
            <p class="sg-note">Grids use the grey category pill. Region and gold are single use: one hero, or one featured card.</p>
            <div class="sg-row"><x-ttn.tag>Trekking</x-ttn.tag><x-ttn.tag variant="region">Gilgit-Baltistan</x-ttn.tag><x-ttn.tag variant="certified">TTN Certified</x-ttn.tag><x-ttn.tag variant="gold">Featured story</x-ttn.tag></div>
            <span class="sg-label">Filter pills</span>
            <x-ttn.filter-pills :items="[['label' => 'All', 'href' => '#', 'active' => true], ['label' => 'Gilgit-Baltistan', 'href' => '#'], ['label' => 'Khyber Pakhtunkhwa', 'href' => '#'], ['label' => 'Azad Kashmir', 'href' => '#'], ['label' => 'Chitral', 'href' => '#']]" />
            <span class="sg-label">Placeholder copy marker</span>
            <x-ttn.placeholder-chip />
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Icon</h2>
            <p class="sg-note">Lucide line icons, 2px stroke. 16px inline, 20px interface, 24px feature.</p>
            <div class="sg-icons">@foreach (\App\Support\Icons::names() as $name)<div><x-ttn.icon :name="$name" :size="24" />{{ $name }}</div>@endforeach</div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Divider</h2>
            <x-ttn.divider />
            <div style="height: var(--space-4)"></div>
            <x-ttn.divider label="Latest" />
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Photo and placeholder slot</h2>
            <p class="sg-note">Every image is a library photo. Until the real frame arrives it renders as a labelled slot naming the brief code and what it is waiting for.</p>
            <div class="ttn-g3">
                <figure><x-ttn.photo :photo="$slot('STORY-CARD-01', '16:9. Fairy Meadows trail detail, centre-weighted')" ratio="16/9" /><figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />Fairy Meadows, Gilgit-Baltistan</figcaption></figure>
                <figure><x-ttn.photo :photo="$slot('DEST-STRIP-01-01', '3:2. Baltit Fort above the bazaar')" ratio="3/2" /><figcaption class="ttn-caption"><x-ttn.icon name="mapPin" :size="12" />Baltit Fort, Hunza Valley</figcaption></figure>
                <figure><x-ttn.photo :photo="$slot('FOUNDER-01', '3:4. Waseem in the field, camera in hand')" ratio="3/4" style="max-width: 240px" /></figure>
            </div>
        </div>
    </x-ttn.section>

    <x-ttn.section alt>
        <div class="sg-block" style="border-top: 0; padding-top: 0">
            <h2 class="ttn-h2">Story card</h2>
            <p class="sg-note">Hover lifts the border and turns the title gold.</p>
            <div class="ttn-g3" data-reveal-group>
                <x-ttn.story-card data-reveal :photo="$slot('STORY-CARD-01', '16:9. Fairy Meadows trail detail')" tag="Trekking" title="Three days on the Fairy Meadows trail" excerpt="The walk up from Tato is steep, but the view of Nanga Parbat is the reward you came for." author="Waseem" date="4 June 2026" readTime="6 min" />
                <x-ttn.story-card data-reveal :photo="$slot('STORY-CARD-02', '16:9. A jeep at a bend on the KKH')" tag="Road trips" title="Driving the Karakoram Highway, end to end" excerpt="Twelve hundred kilometres, one road, and more reasons to stop than you will have days for. Here is how we would do it again." author="Waseem" date="28 May 2026" readTime="9 min" />
                <x-ttn.story-card data-reveal :photo="$slot('STORY-CARD-03', '16:9. Tripod at dawn')" tag="Photography" title="Chasing first light over Passu Cones" author="Waseem" date="19 May 2026" />
            </div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Destination card</h2>
            <p class="sg-note">Full-bleed image under the standard gradient. Hover scales the image to 1.02.</p>
            <div class="ttn-g3" data-reveal-group>
                <x-ttn.destination-card data-reveal :photo="$slot('DEST-CARD-01', 'Near square. Hunza, centre-weighted')" name="Hunza Valley" region="Gilgit-Baltistan" />
                <x-ttn.destination-card data-reveal :photo="$slot('DEST-CARD-04', 'Near square. Bumburet lanes')" name="Kalash Valleys" region="Chitral" />
                <x-ttn.destination-card data-reveal :photo="$slot('DEST-CARD-05', 'Near square. The river at Keran')" name="Neelum Valley" region="Azad Kashmir" />
            </div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Partner card</h2>
            <p class="sg-note">The gold border is the certified mark.</p>
            <div class="ttn-g3">
                <x-ttn.partner-card name="Karakoram Expeditions" category="Tour Operator" location="Skardu, Gilgit-Baltistan" description="Small-group treks to K2 base camp, led by local guides who grew up in these valleys." />
                <x-ttn.partner-card name="Eagle Nest Hotel" category="Hotel" location="Duikar, Hunza" description="Sunrise views over Rakaposhi and Lady Finger, a short drive above Karimabad." />
                <x-ttn.partner-card name="Northern Jeep Service" category="Jeep Service" location="Skardu, Gilgit-Baltistan" description="Reliable 4x4 transfers to Deosai, Shigar and the Shangrila lakes." />
            </div>
        </div>
    </x-ttn.section>

    <x-ttn.section>
        <div class="sg-block" style="border-top: 0; padding-top: 0">
            <h2 class="ttn-h2">Certification block</h2>
            <p class="sg-note">The signed TTN verdict. The single most important component on the site.</p>
            <x-ttn.certification-block since="March 2022" verdict="We have sent our own families here. The rooms are simple, the sunrise is not, and in seven years they have never let a traveller of ours down." style="max-width: var(--w-article)" />
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Community quote</h2>
            <p class="sg-note">A real Facebook post, credited and dated, linking out to the original.</p>
            <div class="ttn-g3">
                <x-ttn.community-quote quote="Took the family to Hunza in an Alto. The KKH is smooth all the way to Karimabad now. Do not let anyone tell you that you need a jeep." name="Hamza R." date="2 June 2026" href="https://facebook.com/ttnpak" />
                <x-ttn.community-quote :photo="$slot('COMM-03', '16:9. Group at a KKH viewpoint')" quote="Group photo from the Khunjerab trip. Fourteen strangers from this group, one convoy, zero regrets." name="Areeba J." date="2 June 2026" href="https://facebook.com/ttnpak" />
                <x-ttn.community-quote quote="Deosai opened last week. Snow walls on both sides at the top." name="Bilal K." />
            </div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Newsletter signup</h2>
            <p class="sg-note">Three variants. All store the address and nothing else.</p>
            <span class="sg-label">Embedded</span>
            <x-ttn.newsletter-signup variant="embedded" source="styleguide" />
            <span class="sg-label">Inline, end of a story</span>
            <x-ttn.newsletter-signup variant="inline" source="styleguide" style="max-width: var(--w-article)" />
            <span class="sg-label">Footer</span>
            <div class="sg-narrow"><x-ttn.newsletter-signup variant="footer" source="styleguide" /></div>
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Empty state</h2>
            <p class="sg-note">For a filter with no results. No gold, by design.</p>
            <x-ttn.empty-state actionHref="#" />
        </div>

        <div class="sg-block">
            <h2 class="ttn-h2">Motion</h2>
            <p class="sg-note">Content fades up once as it enters the viewport, staggered inside a grid. Hero text settles in on load. Nothing loops, nothing moves with the scroll except the reading progress line on articles. Visitors who ask for reduced motion get none of it.</p>
            <div class="ttn-g4" data-reveal-group>
                @foreach (['One', 'Two', 'Three', 'Four'] as $n)<div data-reveal class="sg-swatch"><div class="sg-swatch__meta"><strong>{{ $n }}</strong>Fades up, 70ms after the last</div></div>@endforeach
            </div>
        </div>
    </x-ttn.section>

    <x-ttn.hero page eyebrow="Hero pattern" title="The mountains are waiting. So is the road." sub="Stories, destinations and trusted partners from the north of Pakistan." ctaLabel="Read the story" ctaHref="#" location="Attabad Lake, Gilgit-Baltistan" code="HOME-01 · Hero" note="Attabad Lake, golden hour. Headline sits lower-left." />
</x-ttn.layout>
