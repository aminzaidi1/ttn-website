# To The North

The website for To The North (TTN), Pakistan's northern tourism community. A photography-led editorial site: stories, destinations, TTN Certified partners, featured community posts and a newsletter list, with an admin where the team edits everything.

Built with Laravel 13, Filament 5, Blade, MariaDB and Apache. No queue workers, no Redis, no scheduled jobs, so it runs on ordinary shared hosting.

## Run it on your computer

You need PHP 8.3 or newer, Composer and MariaDB. XAMPP or Laragon give you all three plus Apache. Node is not needed: the built CSS and JavaScript are in the repository.

1. Get the code and install PHP packages.

```bash
git clone https://github.com/aminzaidi1/ttn-website.git
```

```bash
cd ttn-website
```

```bash
composer install
```

2. Create your settings file and the app key.

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

3. Create an empty MariaDB database called `ttn` (phpMyAdmin, or the command below), then check the `DB_` lines in `.env` match your username and password.

```bash
mysql -u root -e "CREATE DATABASE ttn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
```

4. Build the tables, load the placeholder content and link the uploads folder.

```bash
php artisan migrate --seed
```

```bash
php artisan storage:link
```

5. Start it.

```bash
php artisan serve
```

The site is at http://localhost:8000 and the admin at http://localhost:8000/admin.

To serve it through Apache instead, point a virtual host's document root at the `public` folder. `public/.htaccess` handles the rest (clean URLs, compression, cache headers). `mod_rewrite` must be on; `mod_headers` and `mod_deflate` are used when present.

## Signing in

| Role | Email | Password |
|---|---|---|
| Admin | admin@tothenorth.test | change-me-now |
| Editor | editor@tothenorth.test | change-me-now |

Change both passwords under Setup, Users before anyone else can reach the site. Admins can do everything. Editors can do everything except Setup (regions, categories, users, redirects).

## What editors can change

Everything. Nothing needs a developer.

- **Content:** stories, destinations (with reorderable "What to do" blocks and route legs, an optional dated road status, photo strip and partners), certified partners (verdict, offers, gallery, contact), community posts (pasted Facebook links).
- **Library:** photos, authors, team, About page photos.
- **Site:** every heading and line of fixed text (Site text), navigation and footer links, the privacy, terms and contact pages.
- **Audience:** newsletter subscribers, with CSV export. Addresses are stored only. Nothing is sent yet.

Rules the admin enforces so the design system holds without anyone policing it:

- Em dashes and emoji are rejected in any text field.
- A photo must have a caption naming the location.
- A partner cannot be published without a verdict and a certified-since date.
- A community post cannot be published until "permission confirmed" is ticked.
- Road status shows only when it has both text and a date, and the date is always shown.
- Changing a page's address creates a redirect, so old links and search results keep working.

## Photography

The site ships with labelled placeholder slots, one for each frame in `design-system/PHOTOGRAPHY-BRIEF.md`. To replace one, open Library, Photos, find the slot by its code (for example `HOME-01`) and upload the file. The site makes the smaller sizes itself. No AI-generated or stock images, by decision.

## The placeholder content is not real

The stories, destinations, partners, verdicts, prices, member names and community quotes come from the design prototype. Replace all of it before launch. In particular, do not publish invented community posts or partner verdicts to the real community.

## Performance and search engines

- Public pages are cached whole (`TTN_PAGE_CACHE=true` in `.env`) and the cache clears itself whenever anything is saved in the admin. Set it to `false` while editing templates.
- Images are resized to WebP at upload and served at the size the screen needs.
- Fonts are self-hosted. One CSS file, one small JavaScript file.
- `/sitemap.xml`, `/robots.txt` and `/feed.xml` are generated from the content. Every page has a title, description, canonical URL, sharing tags and structured data, editable per record under SEO.
- Before going live, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` to the real address and `TTN_STYLEGUIDE=false`.

## For developers

- `design-system/` is the visual spec and its rules are binding. Tokens in `resources/css/tokens/` are copied from it unchanged (only `fonts.css` differs, to self-host the same fonts). Never hardcode a token value.
- `/styleguide` renders every component.
- `docs/PLAN.md` records the plan and the decisions behind the build.
- `npm install` then `npm run build` rebuilds the CSS and JavaScript after changes. Commit `public/build`.
- `php artisan test` runs the tests, including checks that no colour is hardcoded and no copy contains an em dash.
- `php artisan migrate:fresh --seed` resets the database to the placeholder content.
