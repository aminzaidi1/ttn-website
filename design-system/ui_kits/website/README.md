# To The North — Website UI kit

High-fidelity, interactive recreation of the To The North editorial website. Photography-led, near-black, gold-accented.

## Run
Open `index.html`. The design-system bundle (`../../_ds_bundle.js`) and global tokens (`../../styles.css`) must be compiled (they are generated automatically by the design-system compiler).

## Files
- `index.html` — app shell + client-side router (Home / Stories / Post / Destinations / Destination / TTN Certified / Partner / Community / About / 404) + Tweaks
- `chrome.jsx` — `Nav`, `Hero`, `SectionHeader`, `Section`, `Footer`, `SearchOverlay`, `ProgressBar` (exported to `window` as `TTN_*`)
- `screens.jsx` — index screens, story article, About, 404
- `destination-detail.jsx` / `partner-detail.jsx` — detail screens (StatsBar, SimpleMap, photo strip, certification block usage)
- `data.jsx`, `story-details.jsx`, `destination-details.jsx`, `partner-details.jsx` — content
- `slots.js` — labelled placeholder image slots keyed to `/PHOTOGRAPHY-BRIEF.md`

## Notes
- Composes the design-system primitives (`Button`, `Tag`, `StoryCard`, `DestinationCard`, `PartnerCard`, `CommunityQuote`, `NewsletterSignup`, `Divider`, `Icon`) from `window.ToTheNorthDesignSystem_dc220a` — it does not re-implement them.
- Imagery is **placeholder** only — labelled slots stating ratio, subject and composition; see `/PHOTOGRAPHY-BRIEF.md` for the shot list.
- Click the hero CTA or any story card to open a post; use the nav to move between sections.
