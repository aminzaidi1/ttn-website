# Brief for building public pages

You are porting screens from the React UI kit (`design-system/ui_kits/website/`) to server-rendered Blade pages in this Laravel app. The kit screens are the visual spec. The design system rules (`design-system/readme.md`, `design-system/AUDIT.md`) are binding.

## Read first
- `design-system/readme.md` and `design-system/AUDIT.md` (rules: two surfaces, one gold element per section, no shadows, radii 4/6/pill, sentence case, no em dashes, no emoji, captions name the location).
- `resources/css/ttn.css` (all shared classes), `resources/views/components/ttn/*.blade.php` (components and their props), `resources/views/styleguide.blade.php` (usage examples), `resources/js/app.js` (available behaviours).
- `app/Models/*` and the migration in `database/migrations/2026_11_15_000000_create_ttn_tables.php`.
- `routes/web.php` (routes already exist; controllers are stubs for you to fill in).
- The kit screen(s) you are assigned, in `design-system/ui_kits/website/*.jsx`.

## Hard rules
- **Never hardcode a token value.** Colours, font families, radii, spacing steps, content widths and gutters always come from `var(--…)` tokens (see `resources/css/tokens/`). No hex colours, no `rgba()` literals, no font-family names in your CSS or inline styles. Literal px font sizes that have no token are acceptable where the kit uses them. If you need a translucent black, use `color-mix(in srgb, var(--ttn-black) NN%, transparent)` or `var(--hero-overlay)`.
- **Mobile first.** Base CSS is the 390px phone layout; add columns with `min-width` media queries at 768 / 1024 / 1280. No horizontal page scroll at 360px. Tap targets at least 44px high. The kit is written desktop-down with `max-width` queries: invert it.
- **Use the existing components and classes.** Do not restyle or duplicate them. `<x-ttn.layout>`, `<x-ttn.section>` (`alt` for the Deep surface; sections must alternate Black/Deep and never use a third surface), `<x-ttn.section-header>`, `<x-ttn.hero>`, `<x-ttn.photo>`, `<x-ttn.story-card>`, `<x-ttn.destination-card>`, `<x-ttn.partner-card>`, `<x-ttn.certification-block>`, `<x-ttn.community-quote>`, `<x-ttn.newsletter-signup>`, `<x-ttn.divider>`, `<x-ttn.empty-state>`, `<x-ttn.filter-pills>`, `<x-ttn.tag>`, `<x-ttn.button>`, `<x-ttn.icon>`, `<x-ttn.gold-link>`, `<x-ttn.placeholder-chip>`. Shared type classes: `ttn-h1`, `ttn-h2`, `ttn-eyebrow`, `ttn-lede`, `ttn-meta`, `ttn-caption`, `ttn-credit`, `ttn-prose`, `ttn-pullquote`, grids `ttn-g2/g3/g4`, `ttn-split`, `ttn-inner`.
- **Do not edit** `resources/css/ttn.css`, `resources/css/app.css`, anything in `resources/views/components/ttn/`, `resources/js/app.js`, `routes/web.php`, models, migrations, seeders (other than your own settings file, below), or the admin (`app/Filament`). If a shared component is missing something you need, work around it in your page CSS and tell me in your report.
- **Your page CSS** goes only in the file(s) named in your task under `resources/css/pages/` (already imported by `app.css`). Prefix your classes so they cannot collide (given in your task).
- **One gold element per section, maximum.** Card tags in grids are the grey `category` pill. Gold tags (`region`, `gold`) are single use (a hero, the one featured story).
- **Images:** every image is a `Photo` model rendered through `<x-ttn.photo :photo="…" ratio="16/9" />`. Photos currently have no files, so they render as labelled placeholder slots; that is correct and intended. Never use external or generated images. Editorial images carry a caption that names the location (`$photo->caption`) with a mapPin icon (`ttn-caption`), and photo-heavy sections carry the credit line (`$photo->credit` / "Photo: Waseem Photography").
- **Everything editors may want to change must come from the database.** Content comes from models. Fixed page strings (headings, intro lines, button labels, and so on) come from `setting('your.key', 'Default text from the kit')`, and every key you use must be registered in your own settings file `database/seeders/settings/NN-yourname.php` (file name given in your task; format: see `database/seeders/settings/00-site.php`: rows of `[group, key, label, value, type?, help?]`, group being a plain name such as "Homepage"). Then run `php artisan db:seed --class=SiteSeeder --force` (safe to re-run). Copy text word for word from the kit; do not rewrite it.
- **Queries:** only published content on public pages (`Model::published()`), eager load relations (`with(...)`) so there are no N+1 queries, order by the `order` column or `published_at`.
- **SEO:** pass `title`, `description`, `image` (when a real photo exists: `$photo->url(1280)`), `type="article"` for stories, and `schema` (array of JSON-LD arrays) to `<x-ttn.layout>`: `Article` for stories, `TouristDestination` for destinations, `LocalBusiness` for partners, `BreadcrumbList` on detail pages, `ItemList` on index pages where natural. Use `seo_title` / `seo_description` when filled, with sensible fallbacks. One `<h1>` per page; real heading hierarchy.
- **Motion:** add `data-reveal` to blocks that should fade up when scrolled into view, and `data-reveal-group` on a grid whose direct children carry `data-reveal` (they stagger). Do not put `data-reveal` on the first screen's main heading or hero (the hero has its own load animation). No other animation, no parallax. Article-type pages (story, destination, partner) pass `progress` to the layout for the reading progress line.
- **Filters** are plain links with a query string (`?region=slug`, `?category=slug`) so each filtered view has a URL; use `<x-ttn.filter-pills>`; show `<x-ttn.empty-state actionHref="…">` when a filter has no results.
- Each page type has its own ending as in the kit. Do not close every page with a three-card related grid.
- Copy rules in anything you write: no em dashes, sentence case, no emoji.
- Do not run `git` commands. Do not run `migrate:fresh` (the database is shared with other workers and already seeded with the kit's placeholder content; if a table you need looks empty or duplicated, wait a minute and check again, then tell me).

## Checking your work (required)
A dev server is running at http://127.0.0.1:8000 (if not, start `php artisan serve --port=8000` in the background). After changing CSS run `npm run build` (other workers also run it; if the result looks stale, run it again).

Screenshot every page you build at phone and desktop width with:
`node /tmp/claude-0/-home-user/48ff1812-8db2-5c15-a783-decaf9434f58/scratchpad/shot.js <url> /tmp/<your-prefix> both`
It writes `/tmp/<your-prefix>-mobile-N.png` and `-desktop-N.png` (1600px tall slices) and prints page height, whether there is horizontal overflow, and console errors. **Look at the screenshots** (Read the PNG files) and compare with the kit screen's structure. Fix: horizontal overflow, console errors, cramped or broken mobile layout, wrong section alternation, more than one gold element in a section, text contrast problems. Also check `storage/logs/laravel.log` for errors and that a missing slug returns 404.

## Report back (concise)
Files created or changed, settings keys added, anything in the kit that did not translate cleanly and what you did about it, and anything you want changed in a shared component.
