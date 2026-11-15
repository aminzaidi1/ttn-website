# To The North: build plan

Status: approved and built. The site was built in one pass at the owner's request, using the recommendations in section 0. Written 15 Nov 2026 after reading `design-system/readme.md`, `SKILL.md`, `AUDIT.md`, `PHOTOGRAPHY-BRIEF.md`, the 11 components and every screen in `design-system/ui_kits/website/`.

## 0. Decisions I need from you

These are the only things that block or change the build. Each has my recommendation.

| # | Question | Recommendation |
|---|---|---|
| 1 | **Higgsfield vs "no AI-generated photos".** Your message enables Higgsfield for photos and video, but the settled decisions and the brief both say no AI or stock photography, and the design system says imagery is the founder's work only. | Keep the settled rule. No generated imagery of places, people or partners anywhere on the site. An invented Hunza on a site whose authority is "we have been there" costs more trust than an honest labelled slot. I will not use Higgsfield unless you name a specific non-photographic use. |
| 2 | **"Very great scroll animations" vs the design system.** The system says: fades and short colour or border transitions only, no parallax, no decorative loops, and the reading progress line is "the one animation the brand earns". | A restrained motion layer that stays inside the rule: sections and cards fade up once as they enter (opacity plus 12px rise, 0.4s, `ease`), grids stagger by 60ms, hero text settles in on load, stat numbers on About count up once. No parallax, no pinning, no loops. Honours `prefers-reduced-motion`. I will put it on `/styleguide` with a toggle so you can judge it before it goes site-wide. If you want more than this, that is a design-system change and you should make it deliberately. |
| 3 | **GitHub repo.** The GitHub token in this session is invalid, so I cannot create or push to a repo. This container is temporary: without a repo, Phase 1 work is lost when the session ends. | Reconnect GitHub at https://claude.ai/connect-github, create an empty private repo (suggest `ttn-website`), and tell me its name. I attach it and push from the first commit. Until then I can only hand you a zip. |
| 4 | **Content blocks table shape.** You settled "what to do sections and route legs are ordered blocks in a `content_blocks` table". Legs carry different fields (from, to, distance, time). | One `content_blocks` table with a `type` column (`section` or `route_leg`) and an `order` column, shown to editors as two separate drag-to-reorder lists. Matches your decision literally and keeps one mechanism. |
| 5 | **Fonts.** The design system ships no font files. `tokens/fonts.css` pulls Playfair Display and Inter from Google's CDN. | Self-host the same two families as `.woff2` (subset, preloaded, `font-display: swap`). Faster on Pakistani mobile networks and no third-party request. This replaces the one `@import` line in `fonts.css`; every other token file is copied byte for byte. |
| 6 | **Stack confirmation.** The brief lists Laravel + Filament as "awaiting Amin's explicit yes". | I am treating this message as the yes. Laravel 13 and Filament 5 are current stable and both run on PHP 8.3. |

## 1. Stack and environment

- Laravel 13, Filament 5, PHP 8.3, MariaDB, Blade, Apache (`public/.htaccess`). Vite builds one CSS file and one small JS file. No front-end framework: vanilla JS only, a few KB.
- No queue workers, no Redis, no cron requirement. Queue driver `sync`, cache driver `file`, sessions `file`. Everything runs on shared hosting later.
- **This container has no MariaDB or Apache and they are not installable here.** I develop and browser-test with `php artisan serve` and SQLite, keeping migrations MariaDB-safe (no SQLite-only features, explicit index names, tested string lengths). `.env.example` ships configured for MariaDB, and the README gives the exact localhost steps for XAMPP or Laragon. First thing to do on your machine: `php artisan migrate --seed` against MariaDB. I will say plainly in the Phase 1 report that MariaDB itself was not exercised here.

## 2. Data model

All tables have `id`, timestamps. Published content has `slug` (unique), `is_published`, `published_at`, `seo_title`, `seo_description`. Every ordered list uses an integer `order` column. No JSON columns for editable content.

| Table | Key columns | Notes |
|---|---|---|
| `users` | name, email, password, `role` (admin, editor) | Admin manages users and settings. Editor manages content. Filament login only, no public accounts. |
| `photos` | path, `caption` (required, names the location), alt, credit (default "Photo: Waseem Photography"), `slot_code` (e.g. `HOME-01`), `slot_note`, width, height | One library for all imagery. A photo row with no file renders the labelled placeholder slot from `slot_code` and `slot_note`, exactly like the kit. When Waseem's frame arrives an editor uploads it and the slot becomes the photo. Variants (480, 800, 1280, 1920 WebP plus original) are generated on upload, synchronously. |
| `regions` | name, slug, order | Gilgit-Baltistan, Khyber Pakhtunkhwa, Azad Kashmir, Chitral. Drives the filters. |
| `destinations` | region_id, name, blurb, overview (rich text), pull_quote, getting_there, where_to_stay, five stats (`stat_season`, `stat_altitude`, `stat_drive`, `stat_difficulty`, `stat_permits`), `lat`, `lng`, `origin_name`, `origin_lat`, `origin_lng`, `road_status_text` (nullable), `road_status_as_of` (nullable date), hero_photo_id, card_photo_id, order | Road status renders only when both text and date are set. Prices live in the prose fields. |
| `content_blocks` | destination_id, `type` (section, route_leg), `order`; section: kind, heading, body; leg: from_place, to_place, distance, duration, note | Kinds are free text with suggestions (Places to visit, Food to try, Viewpoints, Treks and walks, When not to go, Festivals, How to behave), since the kit already uses kinds outside the fixed five. |
| `destination_photo` | destination_id, photo_id, order | The photo strip. |
| `authors` | name, slug, role_line, bio, photo_id | Stories end on the author card. Not logins. |
| `story_categories` | name, slug, order | Trekking, Road trips, Photography and so on. Editable. |
| `stories` | title, author_id, story_category_id, region_id, destination_id (nullable), location, is_featured, excerpt, lead, `body` (one rich-text field), hero_photo_id, card_photo_id, read_minutes | Read time is computed from the body on save, overridable. Only one story is featured at a time (saving a new one unsets the old). |
| `partner_categories` | name, slug, order | Hotels, Tour operators, Jeep services, Guides. |
| `partners` | partner_category_id, region_id, name, location, description, overview, `certified_since` (date), `verdict`, signature, address, phone, email, website, cta_text, hero_photo_id, thumb_photo_id, order | A partner cannot be published without a verdict and a certified-since date. The verdict is the product. |
| `partner_offers` | partner_id, icon, text, order | Icon chosen from the curated icon set. |
| `partner_photo` | partner_id, photo_id, order | Gallery. |
| `destination_partner` | destination_id, partner_id, order | Certified partners shown at the foot of a destination. |
| `community_posts` | facebook_url, quote, member_name, posted_on, photo_id (nullable), destination_id (nullable, for destination tips), show_on_home, `permission_confirmed`, order | Cannot be published until `permission_confirmed` is ticked. Enforces the launch rule in the brief. |
| `subscribers` | email (unique), source (home, story, footer, nav), created_at | Storage only. CSV export in admin. Honeypot plus rate limit on the form. |
| `team_members` | name, role_line, photo_id, order | About page. |
| `nav_items` | location (header, footer_explore, footer_community), label, url, order, is_visible | Nav and footer links. |
| `settings` | group, key, value (text) | One row per editable string: homepage hero and section headings, About copy, stats band (four label and value pairs), certified criteria block, footer lines, social URLs, member count, 404 copy, default SEO text. |
| `pages` | slug, title, body | Privacy, terms, contact. |
| `redirects` | from_path, to_path | Written automatically when an editor changes a slug, so old links and Google results keep working. |

## 3. Admin (Filament)

Navigation groups, in editor language:

- **Content:** Stories, Destinations, Certified partners, Community posts.
- **Library:** Photos, Authors, Team.
- **Site:** Homepage, About page, Navigation, Footer, Pages, Site settings (admin only).
- **Audience:** Subscribers (read only, export).
- **Setup (admin only):** Regions, Story categories, Partner categories, Users.

Behaviours worth stating:

- Destination form has two drag-to-reorder repeaters (What to do, Route legs) plus the photo strip and partner pickers.
- Story body uses Filament's rich editor limited to what the design supports: H2, H3, bold, italic, link, quote, bulleted list, image with caption. No colours, no font sizes, no tables.
- **Copy-rule validation on every text field:** em dashes and emoji are rejected on save with a plain message. Photo captions are required. This makes the copy rules enforceable without a developer.
- "Preview" and "View on site" links on every record. Draft and published states. Dashboard widgets: drafts waiting, posts missing permission, photo slots still empty (count against the 42-frame brief), subscriber count.
- Saving any content clears the page cache (see section 7).

## 4. Blade components

The 11 core components, ported one to one. Props keep the React names. Inline styles and JS hover handlers become classes in one stylesheet (`resources/css/ttn.css`) that references tokens only.

| Blade component | From | Variants and notes |
|---|---|---|
| `<x-ttn.button>` | Button | primary, secondary, text; sm, md, lg; renders `a` or `button`; icon slots. |
| `<x-ttn.tag>` | Tag | category, region, certified, gold. |
| `<x-ttn.icon>` | Icon | Inlined Lucide paths, same curated set. |
| `<x-ttn.story-card>` | StoryCard | Grey tag only. |
| `<x-ttn.destination-card>` | DestinationCard | Hover scale 1.02. |
| `<x-ttn.partner-card>` | PartnerCard | Gold border. |
| `<x-ttn.certification-block>` | CertificationBlock | since, verdict, signature. |
| `<x-ttn.community-quote>` | CommunityQuote | Optional image. |
| `<x-ttn.newsletter-signup>` | NewsletterSignup | embedded, footer, inline. Real form posting to `/newsletter`; works without JS, enhanced with fetch for the inline success state. |
| `<x-ttn.divider>` | Divider | Plain or labelled. |
| `<x-ttn.empty-state>` | EmptyState | Clear-filter link instead of a JS callback. |

Kit patterns, also as components: `layout` (base), `nav`, `footer`, `hero`, `section`, `section-header`, `photo` (responsive `<picture>` or labelled slot), `placeholder-chip`, `filter-pills`, `stats-bar`, `road-status`, `route-legs`, `content-block`, `simple-map`, `photo-strip`, `sub-nav`, `search-overlay`, `reading-progress`, `gold-link`, `certified-row`, `featured-story`, `author-card`. Phase 1 builds the 11 plus layout, nav, footer, section, section-header, photo and placeholder-chip. The rest arrive with the pages that use them.

## 5. Route map

| URL | Page | Kit screen |
|---|---|---|
| `/` | Home | HomeScreen |
| `/stories` and `/stories?region=slug` | Stories index | StoriesScreen |
| `/stories/{slug}` | Story article | PostScreen |
| `/destinations` and `?region=slug` | Destinations index | DestinationsScreen |
| `/destinations/{slug}` | Destination detail | DestinationDetailScreen |
| `/certified` and `?category=slug` | TTN Certified | CertifiedScreen |
| `/certified/{slug}` | Partner detail | PartnerDetailScreen |
| `/community` | Community | CommunityScreen |
| `/about` | About | AboutScreen |
| `/search?q=` | Search results page (no-JS fallback) | SearchOverlay |
| `/search.json?q=` | Live results for the overlay | SearchOverlay |
| `POST /newsletter` | Subscribe | NewsletterSignup |
| `/privacy`, `/terms`, `/contact` | Simple pages | Not in kit: built on the article column |
| `/sitemap.xml`, `/robots.txt`, `/feed.xml` | SEO | n/a |
| `/styleguide` | Component reference | noindex, local and logged-in admins only |
| `/admin` | Filament | n/a |
| anything else | 404, "This road does not go anywhere." | NotFoundScreen |

Filters are real URLs, not client state, so they are shareable, cacheable and crawlable.

## 6. Mobile first

Most readers are on phones, and the kit is written desktop-down (`max-width` queries). I invert it: base styles are the 360px layout, `min-width` queries at the system's 768, 1024 and 1280 breakpoints add columns. Tap targets at least 44px, the mobile menu and search are reachable by thumb, no horizontal scroll at 360px, images sized by `srcset` so a phone never downloads the 1920px frame. Every Phase 1 check is done at 390px first, then 1280px.

## 7. Performance (target: 50,000 visits a month)

50,000 visits a month averages well under one request a second, so the risk is slow pages on mobile data, not server load. Plan:

- **Full-page cache** for anonymous GET requests, stored on disk, cleared on any admin save. A cached page is served without touching the database.
- **Images:** WebP variants generated at upload, `srcset` and `sizes`, explicit width and height (no layout shift), lazy loading below the fold, the hero image preloaded with `fetchpriority="high"`.
- **Assets:** one CSS file, one deferred JS file under 10 KB, hashed filenames with one-year cache headers, gzip or brotli in `.htaccess`. Two font families, four files, preloaded.
- **Database:** indexes on slug, `published_at`, region and order columns; eager loading everywhere; no N+1 (enforced in development).
- **Budgets I will test against:** Lighthouse mobile performance 90+, LCP under 2.5s on throttled 4G, CLS under 0.05, home page under 150 KB before photos.
- Cloudflare in front of images remains the plan at hosting time. Out of scope now.

## 8. SEO

- `robots.txt` (blocks `/admin`, `/styleguide`, `/search`), dynamic `sitemap.xml` with last-modified dates, RSS feed for stories.
- Per-page title, meta description, canonical URL, Open Graph and Twitter cards, all editable per record with sensible fallbacks.
- JSON-LD: `Organization` and `WebSite` (with search action) site-wide, `Article` on stories, `TouristDestination` on destinations, `LocalBusiness` on partners, `BreadcrumbList` on detail pages.
- Semantic HTML: one `h1` per page, real headings, `figure` and `figcaption`, descriptive alt text from the photo library.
- Slug changes create 301 redirects automatically.
- The questions the Facebook group asks ("can an Alto reach Babusar Top", budgets, permits) are the real search opportunity. The content model already supports answering them inside destinations and stories; the plan-your-trip hubs stay a later phase as the brief says.

## 9. Build order

**Phase 1 (this approval covers only this):**
1. Laravel and Filament scaffold, first migrations (users with roles, plus framework tables), git from the first commit.
2. Tokens copied unchanged into `resources/css/tokens/`, `styles.css` linked as the entry point, fonts self-hosted (pending decision 5).
3. Logo clean-up: crop, remove black, transparent PNG at 1x and 2x, SVG if the trace is clean, favicon set.
4. Base layout, Nav (with mobile menu), Footer, the 11 components.
5. `/styleguide`: every component in every variant and state, beside the token swatches, plus the motion preview.
6. Browser check of each step at 390px and 1280px against the kit, with screenshots, and a written list of anything that did not translate.

**Phase 2:** full data model, Filament resources, copy-rule validation, seeders that load the kit's placeholder content clearly marked as placeholder.
**Phase 3:** public pages in this order: Home, Stories and article, Destinations and detail, Certified and partner, Community, About, 404, simple pages.
**Phase 4:** search overlay, newsletter storage, motion layer.
**Phase 5:** SEO, page cache, image pipeline tuning, Lighthouse passes, accessibility pass, MariaDB and Apache verification on your machine, backup notes.

How I will work: I make the design and architecture calls and review every screen in the browser myself. Mechanical work (porting a component, writing a Filament resource from the schema above) goes to sub-agents, and I check their output against the kit before it is committed.

## 10. What does not translate cleanly from the design system

Found while reading. None block Phase 1; I will fix each as noted unless you object.

1. **Logo files appear swapped.** `logo-primary.jpeg` is the simple road wordmark and `logo-mark.jpeg` is the mountain version, the reverse of the readme. The kit's nav uses `logo-mark.jpeg`, so the mountain logo is what actually shows in the header. I will name the cleaned files by what they are (`logo-mountain`, `logo-road`) and use the mountain one in the nav to match the kit.
2. **The logo lettering has a grey extruded shadow** baked in, on a brand with a "no drop shadows" rule. It is part of the artwork so I will keep it, but it means a vector trace may not be clean. If it is not, you get PNG only and a note; I will not ship a rough SVG.
3. **At 40px nav height the mountain logo's fine lines get thin on phones.** I will check legibility at 390px and may propose the road wordmark for mobile.
4. **Hardcoded values in the components.** The rule is "never hardcode a token value". The React components contain many literal sizes that have no token (22px card titles, 20px verdict text, 13px meta) and one literal gradient on DestinationCard that differs from `--hero-overlay`. I will use tokens wherever one exists and keep the non-tokenised sizes as named component-level variables in one place, so nothing is a magic number and no colour is ever literal.
5. **Embedded newsletter block has two golds** (gold eyebrow and gold button) in one section, against the one-gold rule that the audit enforced on the hero. I will make the eyebrow light grey, the same fix the audit applied to the hero. Say so if you want it left as designed.
6. **Small bugs in the kit:** `--radius-btn` does not exist (should be `--radius-button`), `--space-6` is given a 40px fallback though the token is 48px, and the Icon set defines phone, mail and externalLink twice. Fixed in the port.
7. **Dead links in the kit:** the nav "Subscribe" button does nothing, the footer "Contact" goes to a page that does not exist, and privacy and terms are in the brief but not designed. Subscribe will jump to the footer signup; Contact, privacy and terms get simple pages on the article column.
8. **Type scale tokens are fixed desktop sizes** (`--fs-hero: 88px`). The kit already overrides them with `clamp()` inline. I will do the same in the component CSS and leave the tokens untouched.
9. **Story bodies.** The kit builds articles from typed blocks; the settled decision is one rich-text field. The editor toolbar is restricted so output can only contain what the design styles. Pull quotes are the editor's quote button.
10. **Community masonry** uses CSS columns, which read top to bottom per column, not left to right by date. Fine on mobile (one column). On desktop the newest posts run down the first column. Acceptable, flagged.
11. **Search** is instant in the kit because all data is in memory. On the real site it is a small request per keystroke (debounced), with a normal results page when JS is off.

## 11. Not in scope

Hosting, domain, deployment, email sending, user accounts, forums, a price component, AI photography, Urdu, plan-your-trip hubs, monetisation features.
