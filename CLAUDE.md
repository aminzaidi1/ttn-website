# To The North website

Photography-led editorial tourism site for northern Pakistan. Laravel 13, Filament 5 admin, Blade, MariaDB, Apache. A publisher: no public accounts, no forums.

## Binding rules
- `design-system/` is the visual spec (readme.md, AUDIT.md, ui_kits/website). Its rules are binding.
- Never hardcode a token value. Colours, fonts, radii, spacing and widths come from `resources/css/tokens/` (copied unchanged from the design system; only `fonts.css` differs, to self-host the same fonts).
- Two surfaces only (TTN Black, TTN Deep), alternating. One gold element per section. No shadows. No blue. No white backgrounds.
- Copy: no em dashes, sentence case, no emoji, captions name the location. Enforced in the admin by `App\Rules\CopyRules`.
- No AI-generated or stock photography. Missing photos render as labelled placeholder slots (`Photo` with no file).
- Mobile first: base CSS is the phone layout, `min-width` queries add columns.
- Everything editors may want to change lives in the database (content models, `settings`, `nav_items`, `pages`). Nothing should need a developer.
- No queue workers, no Redis, no cron. Must run on plain shared hosting.

## Layout
- `app/Models` content model. `Destination::blocks` and `::routeLegs` are both rows in `content_blocks` (`type` + `order`).
- `app/Filament` the admin. `app/Support/PageCache.php` + `app/Http/Middleware/CachePage.php` full-page cache, cleared on any model save.
- `resources/views/components/ttn` the design system components. `resources/css/ttn.css` shared styles, `resources/css/pages/*` per-page styles.
- `/styleguide` renders every component.
- `docs/PLAN.md` the build plan and decisions. `README.md` setup.

## Commands
- `php artisan migrate:fresh --seed` rebuild the database with placeholder content.
- `npm run build` rebuild CSS and JS (the built files in `public/build` are committed so the site runs without Node).
- `php artisan test` run the tests.
