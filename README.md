# PAFFAR

PAFFAR is a hybrid social-professional network for entrepreneurs, combining LinkedIn-style networking with multi-vendor e-commerce and course hosting. Built with Laravel and MySQL.

## Phase 1 (this build)

- **Auth & profiles** — Laravel Breeze auth, extended profiles (headline, bio, industry, business name, website, location, avatar/cover, skills).
- **Networking/feed** — posts, likes, comments, following, a people directory, and public profile pages.
- **Marketplace** — seller application/approval workflow, store management, product CRUD, browsing, session-based cart, checkout, order history (buyer and seller sides), reviews.
- **Course hosting** — tutor application/approval workflow, course CRUD with lessons, course submission/admin approval, public catalog, enrollment, and a lesson player.
- **Admin** — dashboard with platform stats and a verification queue for pending sellers, tutors, and courses.

## Future phases (not in this build)

These were scoped out of the Phase 1 MVP and are not implemented:

- Ride booking
- Supply chain / logistics
- Advertising platform
- PAFFAR wallet / payments
- Project workspaces
- Direct messaging
- Groups / forums
- Events
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
