# To The North — Design System

Design system for **To The North (TTN)**, Pakistan's premier northern tourism community. Founded in 2018 by Waseem Photography, grown to 466,000 members on Facebook. The website is a **photography-led editorial platform** — a publisher, not a social network. Content is authored and curated by the TTN team. Authority comes from seven years of authentic, community-earned credibility and the founder's own photography of northern Pakistan.

**Mission:** Promote tourism and mountaineering in the Northern Areas of Pakistan.

## Sources provided
- Written brand brief (colours, type, spacing, components, page notes) — the source of truth for this system.
- Two logo files (uploaded): primary mountain+road wordmark, and simpler road-only mark.
- Reference site for **content architecture only**: Atlas Obscura (atlasobscura.com). Not a visual reference.
- No codebase or Figma was provided.

## Index / manifest
- `styles.css` — global entry point (consumers link this one file). `@import` list only.
- `tokens/` — `fonts.css`, `colors.css`, `typography.css`, `spacing.css`, `layout.css`.
- `assets/` — logos (`logo-primary.jpeg`, `logo-mark.jpeg`) and placeholder imagery (`placeholder-*.jpg`).
- `guidelines/` — foundation specimen cards (Colors, Type, Spacing, Brand).
- `components/core/` — reusable primitives + their cards.
- `ui_kits/website/` — interactive recreation of the marketing site. Imagery renders as labelled placeholder slots (`slots.js`) keyed to the brief.
- `PHOTOGRAPHY-BRIEF.md` — shot list for the founder: every image slot, ratio, subject, composition, light, resolution.
- `AUDIT.md` — compliance audit of the site against these rules: violations fixed, exceptions documented.
- `SKILL.md` — Agent-Skill manifest.

### Components (`window.ToTheNorthDesignSystem_dc220a`)
`Button`, `Tag`, `Icon`, `StoryCard`, `DestinationCard`, `PartnerCard`, `CertificationBlock`, `CommunityQuote`, `NewsletterSignup`, `Divider`, `EmptyState`.

- **CertificationBlock** — the signed TTN endorsement at the top of every partner page. Gold border on Deep, "Certified since" line, Playfair italic verdict in first person plural, grey signature. The block is the product of TTN Certified.
- **EmptyState** — for filtered views with no results. Grey Mountain icon, one line in the TTN voice, ghost clear-filter button. Contains no gold by design.

### Website kit patterns (`ui_kits/website/`, exported as `window.TTN_*`)
Kit-level components built from the primitives — promotable to `components/core/` when a second surface needs them:
- **StatsBar** (`destination-detail.jsx`) — the at-a-glance strip on destination pages: five icon/label/value stats on Deep with thin top and bottom borders; 2-column below 1024px.
- **RoadStatus** (`destination-detail.jsx`) — dated road-conditions strip below the stats bar. Fail-safe by design: renders nothing at all when there is no status data (never an empty or stale state); when present it always shows "Road status as of [date]. Conditions change fast. Check the group before you drive." The date is the honesty mechanism.
- **Content blocks** (destination "What to do") — an ordered list of arbitrary `{kind, heading, body}` blocks (Places to visit, Food to try, Viewpoints, Treks and walks, When not to go…), composable from the admin dashboard. Different destinations show different kinds in different orders; never a fixed three-beat template.
- **SimpleMap** (`TTN_DDMap`) — dark editorial schematic projected from real lat/lon per destination (origin ring, dashed route, gold pin), captioned "schematic, not to scale". Destination pages only; partner pages use plain address/phone/email.
- **Placeholder marker** (`TTN_Placeholder`, `chrome.jsx`) — dashed grey chip marking blocks of dummy copy, same discipline as the labelled image slots. Remove as real content lands.
- **SearchOverlay** (`TTN_SearchOverlay`) — full-screen TTN Black search: Playfair input with gold bottom border, live results grouped Stories / Destinations / Partners, Esc to close.
- **ReadingProgress** (`TTN_ProgressBar`) — 2px gold line fixed to the viewport top, fills with scroll. The one animation the brand earns; rendered on article-type pages only.
- **Screens** — Home, Stories (featured + grid), Story article (ends on the author card), Destinations (editorial rows), Destination detail (stats bar, road status, sticky sub-nav, route legs with price ranges, schematic map, content blocks, photo strip, community tips; closes on certified partners then related destinations), TTN Certified (registry rows + criteria block), Partner detail (certification block, offers list, staggered gallery; closes on the contact CTA), Community (masonry, dated, links out), About (founder, stats band, vision/mission, gallery, team), 404 ("This road does not go anywhere."). Each page type has its own distinct ending — do not close every page with a 3-card related grid.

---

## CONTENT FUNDAMENTALS

**Voice:** The voice of someone who has actually been to these places and has the photographs to prove it. Authentic, grounded, proud of Pakistan. Never hype, never corporate.

- **Person:** Speaks as "we" (the TTN team) to "you" (the traveller). Community posts are first-person from named members.
- **Casing:** Headlines are **sentence case**, never all-caps. The only uppercase is the `TTN Certified` badge, region tags, and small eyebrow labels (with letter-spacing).
- **No em dashes** in body copy or UI text. Use periods or commas. (This README is internal documentation, not product copy.)
- **Tone examples:**
  - Hero: "The mountains are waiting. So is the road."
  - Story excerpt: "The walk up from Tato is steep, but the view of Nanga Parbat is the reward you came for."
  - Newsletter success: "You're in. See you in the north."
  - Community stat: "466,000 travellers and counting."
- **Captions always include location** — e.g. "Kharfaq Lake, Gilgit-Baltistan". Photo credit always shown — "Photo: Waseem Photography".
- **Emoji:** none. Not part of the brand.
- **Vibe:** editorial, earned, local. It should read like it was made by someone who has driven the KKH, not by a design agency.

---

## VISUAL FOUNDATIONS

**Colour.** The site lives in near-black. Two surfaces only per page, alternating: `#0A0A0A` (TTN Black) and `#111111` (TTN Deep). Gold (`#F5A623`) is the only warm accent — it should feel like sunlight on a peak, used with restraint. **One gold element per section, never two.** White (`#F5F5F0`) is reserved for primary body text; grey (`#8A8A8A`) handles short secondary text — captions, metadata, labels. When secondary text runs longer than one line (excerpts, blurbs, subtitles), use light grey (`#B4B4AE`, `--ttn-grey-light`) for legibility. Green (`#1A3A1A`) only for nature/environment-tagged content. **Never** pure white `#FFFFFF` backgrounds, **never** blue in any form.

**Type.** Two families. **Playfair Display** (700/900, and 400 italic) for anything editorial — hero titles, section headers, card titles, pull quotes. **Inter** (400/500/600) for all body, UI, labels, captions. Display: line-height 1.1–1.2, letter-spacing -0.02em at large sizes. Body: line-height 1.7, max measure 68 characters. UI labels and buttons: letter-spacing 0.04em. Eyebrows/section labels: uppercase, 0.1em.

**Spacing.** 8px base unit, geometric scale up to 128px hero padding. Sections separated by `space-12` (96px); hero vertical padding `space-16` (128px).

**Backgrounds & imagery.** Photography is the soul of the brand — the founder's own work, always full-bleed in hero contexts, never floated or boxed. Aspect ratios: 16:9 (cards), 3:2 (editorial), 1:1 (avatars), full-viewport (heroes). The only treatment allowed is the standard dark legibility gradient: `linear-gradient(to bottom, rgba(10,10,10,0.30), rgba(10,10,10,0.75))`. No filters. `object-fit: cover` only, never stretched. Imagery colour vibe: natural, warm where the light is warm — no presets, no grain, no duotone.

**Shape & depth.** Sharp brand. Small radii only: 4px (buttons, inputs), 6px (cards), pill (tags). **No drop shadows anywhere** — use 1px borders instead (`#1E1E1E` subtle, lifting to `#333333` on hover). The TTN Certified card uses a 1px **gold** border as a mark of quality.

**Borders & dividers.** 1px `#1E1E1E` rules. Labelled section dividers center an uppercase grey label (0.1em tracking) between two rules.

**Hover / press.** Primary button: gold → amber (`#C47D0E`). Ghost button: transparent → gold fill, gold text → black. Text links: underline on hover only. Story card: border lifts to `#333333`, title shifts to gold. Destination card: image scales to 1.02. Transitions are short, easing `ease`, ~0.15–0.4s. No bounces. Press states are subtle (colour shift, not large scale).

**Transparency & blur.** Used only for the hero overlay gradient. No frosted-glass/backdrop-blur panels. Nav is solid `#0A0A0A` with a bottom border, sticky on scroll.

**Animation.** Restrained. Fades and short colour/border transitions. No infinite decorative loops, no parallax gimmicks. Let the photography carry the weight.

**Layout.** 12-column grid. Max content 1280px, standard 1100px, article 720px. Gutters 24px (mobile) / 40px (desktop). Cards never get borders on hero photography. Section backgrounds alternate the two surfaces — never introduce a third.

---

## ICONOGRAPHY

- **Set:** Lucide (line icons, 2px stroke), consistent with the brand's Next.js ecosystem. Shipped as a curated React `Icon` component (`components/core/Icon.jsx`) with inlined Lucide paths — no external icon dependency at runtime.
- **Sizes:** 16px inline, 20px standalone UI, 24px feature.
- **Colour:** TTN Grey for UI, TTN Gold for accents/highlights.
- **Glyphs in use:** MapPin, Mountain, Camera, Users, Star, ArrowRight, Menu, X, ChevronDown, ExternalLink, Phone, Mail, Check, Facebook, Clock.
- **No emoji. No unicode glyphs as icons.** Location captions pair a MapPin with text.
- To extend the set, add the Lucide path to `Icon.jsx` (keep the 24×24 viewBox / 2px stroke).

---

## CAVEATS / SUBSTITUTIONS
- **Fonts** load from the Google Fonts CDN (these are the exact specified families, Playfair Display + Inter). No self-hosted binaries were provided, so the compiler reports 0 font files — this is expected; the webfonts still load via `tokens/fonts.css`. Provide `.woff2` files if you need fully offline/self-hosted fonts.
- **Imagery** throughout cards and UI kit is neutral placeholder art, clearly labelled. Replace with the founder's photography (`assets/placeholder-*.jpg`) for any real use.
- Logos are JPEGs on black; transparent PNG/SVG versions would be ideal for flexible placement.
