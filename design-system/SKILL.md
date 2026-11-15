---
name: tothenorth-design
description: Use this skill to generate well-branded interfaces and assets for To The North (TTN), Pakistan's northern tourism editorial platform, either for production or throwaway prototypes/mocks/etc. Contains essential design guidelines, colors, type, fonts, assets, and UI kit components for prototyping.
user-invocable: true
---

Read the README.md file within this skill, and explore the other available files.

If creating visual artifacts (slides, mocks, throwaway prototypes, etc), copy assets out and create static HTML files for the user to view. If working on production code, you can copy assets and read the rules here to become an expert in designing with this brand.

If the user invokes this skill without any other guidance, ask them what they want to build or design, ask some questions, and act as an expert designer who outputs HTML artifacts _or_ production code, depending on the need.

## Quick reference
- **Surfaces:** near-black only. Alternate `#0A0A0A` and `#111111`, never a third. Never pure white, never blue.
- **Accent:** gold `#F5A623` (hover amber `#C47D0E`). One gold element per section.
- **Type:** Playfair Display (titles, quotes), Inter (body, UI). Headlines sentence case. Body line-height 1.7, measure 68ch. Secondary text longer than one line uses `#B4B4AE`, not `#8A8A8A`.
- **Shape:** sharp. Radii 4/6/pill. Borders not shadows. Certified = gold border.
- **Photography:** full-bleed, the founder's own work, only the standard dark gradient. `object-fit: cover`.
- **Voice:** authentic, grounded, "we" to "you", no hype, no em dashes, no emoji. Captions name the location.
- **Icons:** Lucide line icons via the `Icon` component.

## Files
- `styles.css` + `tokens/` — link `styles.css` to inherit all tokens.
- `components/core/` — Button, Tag, Icon, StoryCard, DestinationCard, PartnerCard, CertificationBlock, CommunityQuote, NewsletterSignup, Divider, EmptyState.
- `ui_kits/website/` — interactive site recreation: all screens (index + detail pages, search, 404) plus kit components StatsBar, SimpleMap, SearchOverlay, ReadingProgress (`window.TTN_*`).
- `assets/` — logos (dark surfaces only) and placeholder imagery.
- `PHOTOGRAPHY-BRIEF.md` — the shot list every image slot is waiting on.
- `AUDIT.md` — compliance audit: fixed violations and documented exceptions.
