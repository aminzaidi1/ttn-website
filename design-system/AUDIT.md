# To The North — Compliance audit

Audit of the website UI kit (`ui_kits/website/`) against the design-system rules, page by page. Date: July 2026. Every violation found is listed with its fix; items judged compliant after review are documented so the reasoning is on record.

## Violations found and fixed

### 1. Home hero had three gold elements
`chrome.jsx` Hero: gold eyebrow, gold primary CTA, gold location caption with gold MapPin — three golds in one section.
**Fix:** eyebrow now light grey, location caption now grey. The CTA is the hero's one gold element.

### 2. Partner "What they offer" had five gold elements
Every service row rendered its icon in gold.
**Fix:** icons now grey. The section carries no gold, which is allowed; the rule is a maximum, not a quota.

### 3. Em dash used as list bullet
`PostBody` bulleted lists used an em dash character as the marker, violating the no-em-dash rule.
**Fix:** replaced with a 5px grey square marker. No em dashes remain in any copy or UI text (checked by grep; code comments and doc files excluded).

### 4. Broken hover token on gold text links
`GoldLink` hovered to `var(--ttn-gold-hover)`, a token that does not exist, so hover silently did nothing.
**Fix:** hovers to `var(--ttn-amber)` per the hover spec (gold → amber).

### 5. Search result rows had no hover state
**Fix:** bottom border lifts `#1E1E1E` → `#333333` on hover, matching the border-lift spec.

### 6. Filter pills had no hover state
**Fix:** inactive pills lift border to `#333333` and text to white on hover; active pill unchanged.

### 7. About portfolio images had no location captions
Six portfolio images rendered bare, violating "every image has a location caption."
**Fix:** each now carries a MapPin + location caption; section keeps its credit line.

### 8. Story pages had no location caption
The article hero/meta identified author and credit but never the location.
**Fix:** each story now declares a location, shown in the meta bar with a MapPin.

### 9. Card tags repeated gold across grids (revised after review)
Initially flagged as compliant on the "self-contained unit" argument; that reasoning was rejected: a three-column grid rendered up to nine gold tags in one section, defeating the scarcity the rule protects.
**Fix:** StoryCard and DestinationCard tags are now grey pills on slate. Gold tags are reserved for single use: the featured lead story on the Stories index (giving that section exactly one gold) and the region tag on a destination detail hero. Documented in Tag, StoryCard and DestinationCard prompt files.

### 10. Article measure exceeded 68ch (revised after review)
Initially listed under "verified clean" while the saved tweak sat at 770px (~72ch). That was a violation, not a clean pass.
**Fix:** saved value reset to 720px, the Tweaks slider is capped at 720px, and the applied value is clamped so no stored tweak can exceed the rule.

## Flagged, reviewed, judged compliant

- **CertificationBlock: gold border + certified badge.** Two gold-touched elements, but they form one composite certification mark — splitting them would weaken the mark. Documented in the component's prompt.md as the section's single gold element. (Reasoning reviewed and upheld.)
- **Footer: gold top border + gold newsletter button.** The 1px gold top border is site chrome (explicitly specified), not an in-section element; the newsletter button is the content's one gold. Same reasoning for the nav (gold active underline + ghost Subscribe): chrome carries persistent wayfinding gold.
- **PartnerCard: gold border + gold category line + certified tag.** Pre-existing core component; the gold border *is* the certified mark per the system readme.
- **Avatar `border-radius: 50%`.** Circles are the specified 1:1 avatar treatment, not a fourth radius.
- **Tweaks panel shadows/blur.** Prototyping tool chrome, not part of the site.
- **Community masonry images without captions.** Member-posted content, not Waseem Photography; locations appear in the post text. The photography rules govern editorial imagery.
- **404 image without caption.** Deliberate: the page is fictional wayfinding, and a location caption would fight the joke. Credit is covered by the site-wide footer line.

## Verified clean (no action needed)

- **Two surfaces:** every page alternates `#0A0A0A` / `#111111` only. Slate (`#1E1E1E`) appears solely as placeholder-image fill and borders, never as a section surface.
- **No drop shadows** anywhere in site code.
- **Sentence case** throughout; only the certified badge, region tags and eyebrow labels are uppercase.
- **No emoji.**
- **Credit lines** present on all photography-heavy sections (photo strips, galleries, portfolio, essay breaks, hero meta bars) plus the site-wide footer line.
- **Radii:** only 4px, 6px, pill, and avatar circles.
- **Measure:** `--w-article` is 720px ≈ 66–68ch at 18px Inter; the Tweaks slider is capped at 720px so the rule cannot be exceeded.

## Known debt

- The stories index region filter only has Gilgit-Baltistan content; other regions show the empty state until more stories exist.
- Partner data covers Gilgit-Baltistan only, so non-GB destination pages show no certified partners (by design, but thin).
