# PAFFAR

PAFFAR is a hybrid social-professional network for entrepreneurs, combining LinkedIn-style networking with multi-vendor e-commerce and course hosting. Built with Laravel and MySQL.

## Phase 1 (this build)

- **Auth & profiles** — Laravel Breeze auth, extended profiles (headline, bio, industry, business name, website, location, avatar/cover, skills).
- **Networking/feed** — posts, likes, comments, following, a people directory, and public profile pages.
- **Marketplace** — seller application/approval workflow, store management, product CRUD, browsing, session-based cart, checkout, order history (buyer and seller sides), reviews.
- **Course hosting** — tutor application/approval workflow, course CRUD with lessons, course submission/admin approval, public catalog, enrollment, and a lesson player.
- **Admin** — dashboard with platform stats and a verification queue for pending sellers, tutors, and courses.

## Later phases (also in this build)

- **Direct messaging** — 1-on-1 conversations between members, with unread tracking.
- **Groups / forums** — topic-based groups with membership and discussion threads.
- **Events** — organizer-created events with RSVP and capacity limits.
- **Ride booking** — members can offer rides (optionally tied to an event) and book seats.
- **Supply chain / logistics** — per-order shipment tracking (carrier, tracking number, status, ship/delivery dates) managed by sellers, visible to buyers.
- **Advertising platform** — members submit ads for admin review; approved ads appear as "Sponsored" placements on the feed and marketplace.

## Future phases (not in this build)

These were scoped out and are not implemented:

- PAFFAR wallet / payments
- Project workspaces
- Mobile app
- AI-driven recommendations

## Local development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

`.env.example` is configured for MySQL. SQLite also works for local development if you prefer (set `DB_CONNECTION=sqlite` and create `database/database.sqlite`).

The seeder creates an admin account (`admin@example.com` / `password`) and a regular test account (`test@example.com` / `password`).

## Deploying to cPanel / shared Apache hosting

Upload the whole project (not just `public/`) to a directory that Apache serves
directly — e.g. `public_html/`. You do **not** need shell access or to be able
to point the document root at the `public/` folder:

- The root `.htaccess` rewrites every request into `public/`, where Laravel's
  own `public/.htaccess` takes over.
- The root `index.php` is a fallback front controller for the rare host that
  ignores `mod_rewrite` on the initial request.
- `bootstrap/ensure-env.php` runs before Composer's autoloader on every
  request. If there's no `.env` yet, it copies `.env.example` to `.env` and
  generates an `APP_KEY`, so the very first page load works without ever
  running an `artisan` command from a shell.

Composer dependencies (`vendor/`) and built frontend assets (`public/build/`)
must already be present in the upload — run `composer install --no-dev` and
`npm run build` locally first.

### First-visit installer

Until `storage/installed` exists, every request redirects to a web-based
installer at `/install`, which walks through:

1. **Database** — enter MySQL host/port/database/username/password from your
   hosting control panel. The installer test-connects, writes `.env`, and
   runs migrations.
2. **Admin account** — create the admin user you'll be logged in as.
3. **Demo data** — optionally import ~30 demo accounts, vendor stores with
   full product catalogs, order history, courses, posts/likes/comments,
   reviews, and a few pending seller/tutor/course applications, so the
   verification queue and every page have realistic content to show. Skip
   it for a completely empty, production-ready platform.

Once installation finishes, `/install` redirects to `/` and can't be re-run
unless `storage/installed` is deleted.
