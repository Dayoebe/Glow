# Glow FM Platform

Glow FM Platform is a Laravel 12 + Livewire application that powers the Glow FM web presence across:

- the public radio station website
- the internal admin dashboard / CMS
- a JSON API for web and mobile clients
- an optional React Native mobile app in `mobile/GlowFMRadio`

The project is built around a radio/media workflow: live stream publishing, editorial content, shows and schedules, listener engagement, staff/team profiles, and supporting business modules such as careers, Vettas reservations, newsletters, and ads.

## What This Project Includes

### Public website
- Home page with station, stream, and featured content
- News and blog publishing
- Podcasts and episodes
- Shows, OAPs, staff profiles, and weekly schedule
- Events and RSVPs
- Careers and applications
- Vettas gallery with reservation requests
- Contact and newsletter flows
- Mobile-first/PWA behavior for the public site

### Admin dashboard
- Dashboard for `admin` and `staff` users
- News, blog, podcast, event, ad, and stream management
- Show, OAP, schedule, segment, and category management
- Team, user, approval, inbox, analytics, and settings modules
- Career applications review
- Vettas gallery management and reservation management

### API layer
- Public API for content, home/search, now playing, and engagement
- Public auth endpoints for listener accounts
- Admin/staff API endpoints with bearer token access

### Mobile app
- Optional React Native client in `mobile/GlowFMRadio`
- Consumes the web API and shares the same content domain

## Tech Stack

- PHP `^8.2`
- Laravel `^12`
- Livewire `^3.7`
- Blade + Alpine.js + Tailwind CSS 4
- Vite 7
- MySQL or SQLite
- Spatie Laravel Permission
- Cloudinary Laravel SDK

## Main Functional Areas

### Content and publishing
- News
- Blog
- Podcasts
- Events
- Ads

### Broadcast and community
- Live stream controls
- Shows and schedules
- OAP and team profiles
- Listener/community placeholders and inbox tools
- Newsletter subscriptions

### Business/support modules
- Careers and application tracking
- Vettas gallery and reservations
- Approval workflow
- Admin settings

## Repository Layout

- `app/Livewire` Livewire components for public pages and admin tools
- `app/Http/Controllers/Api` API controllers for public and admin/mobile clients
- `app/Models` domain models for content, stream, team, careers, Vettas, and more
- `app/Mail` mailables used by newsletter, contact, Vettas reservation, and staff flows
- `resources/views` Blade layouts, Livewire views, and email templates
- `routes/web.php` public and dashboard web routes
- `routes/api.php` public/admin API routes
- `routes/console.php` scheduled and custom Artisan commands
- `mobile/GlowFMRadio` optional React Native app

## Requirements

- PHP 8.2+
- Composer 2+
- Node.js 20+ and npm
- MySQL 8+ or SQLite

## Quick Start

### One-command setup

```bash
composer run setup
php artisan storage:link
```

`composer run setup` does the following:

- installs Composer packages
- creates `.env` from `.env.example` if needed
- generates the application key
- runs migrations
- installs frontend dependencies
- builds frontend assets

### Start local development

```bash
composer run dev
```

This starts:

- `php artisan serve`
- a queue listener
- Laravel Pail
- Vite dev server

### Manual setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run dev
php artisan serve
```

## Environment Variables

Commonly used keys:

- `APP_NAME`
- `APP_URL`
- `DB_CONNECTION` and database credentials
- `QUEUE_CONNECTION`
- `MAIL_*` settings
- `CLOUDINARY_URL` or Cloudinary credential keys
- `FCM_SERVER_KEY`
- `MYSQLDUMP_PATH`

### Mail notes

Mail is used for:

- newsletter confirmation
- contact replies/submissions
- staff birthday emails
- Vettas reservation notifications

Vettas reservation notifications can be configured in the dashboard under Vettas page settings. The app-level fallback recipient is `MAIL_VETTAS_RESERVATIONS_TO`, which defaults to `chairman@glowfmradio.com`.

## Roles and Access

The application works with these roles:

- `admin`
- `staff`
- `corp_member`
- `intern`
- `user`

Notes:

- staff/admin users are redirected to the dashboard after login
- regular users stay on the public site
- public navbar dashboard access is only shown to users who can actually access it
- admin routes are protected by web middleware
- API admin routes require bearer token auth plus admin/staff authorization

## Database and Seeding

Run migrations:

```bash
php artisan migrate
```

Seed starter data:

```bash
php artisan db:seed
```

Important:

- if you have pulled recent changes, run migrations again to pick up newer tables such as Vettas reservations
- `RoleSeeder` creates the initial Spatie roles

### Creating a first admin user

```bash
php artisan tinker
```

```php
$user = \App\Models\User::firstOrCreate(
    ['email' => 'admin@example.com'],
    ['name' => 'Admin', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
);

$user->update(['role' => 'admin', 'is_active' => true]);
$user->assignRole('admin');
```

## Vettas Reservations

The Vettas module includes:

- public gallery and contact/booking presentation
- reservation request form on the public Vettas page
- admin reservation management screen in the dashboard
- automatic email notification when a reservation is submitted

Reservation flow:

1. A guest submits a reservation on the public Vettas page.
2. The reservation is stored in `vettas_reservations`.
3. An email alert is sent to the configured recipient.
4. Admin can review the request in the dashboard under Vettas reservations.

## Queue and Scheduler

This application uses queues and scheduled commands.

### Queue worker

```bash
php artisan queue:work
```

### Scheduler in development

```bash
php artisan schedule:work
```

### Production cron

```cron
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

### Scheduled jobs currently configured

- `staff:send-birthday-emails` daily at `07:00`
- `push:now-playing` every 5 minutes
- `push:show-starting` every 5 minutes

## API Overview

Typical local base URLs:

- `http://localhost:8000/api` when using `php artisan serve`
- `http://localhost/Glow/public/api` in common Apache/XAMPP setups

### Public API examples

- `GET /home`
- `GET /search`
- `GET /now-playing`
- `GET /news`
- `GET /blog`
- `GET /events`
- `GET /podcasts`
- `GET /shows`

### Public auth

- `POST /public/auth/register`
- `POST /public/auth/login`
- `GET /public/auth/me`
- `POST /public/auth/logout`

### Admin/staff auth

- `POST /auth/login`
- `GET /auth/me`
- `POST /auth/logout`

### Admin API

- prefix: `/admin/*`
- requires `api_token` plus `api_admin_or_staff`

Token notes:

- use `Authorization: Bearer <token>`
- tokens are stored hashed in `users.api_token`
- new login invalidates the previous token

## Progressive Web App (PWA)

The public site includes installable web app support.

Relevant files:

- `public/manifest.webmanifest`
- `public/sw.js`
- `public/offline.html`
- `public/icons/*`

Deployment checklist:

1. Set `APP_URL` correctly.
2. Serve the app over HTTPS.
3. Build frontend assets with `npm run build`.
4. Deploy `public/build` and the PWA files under `public/`.

## Mobile App

The React Native client lives in:

```bash
mobile/GlowFMRadio
```

Quick start:

```bash
cd mobile/GlowFMRadio
npm install
npm start
```

Before local testing, update the API base URL in:

- `mobile/GlowFMRadio/src/config.ts`

## Useful Commands

```bash
php artisan route:list
php artisan test
composer test
php artisan pail
php artisan queue:work
php artisan schedule:work
php artisan careers:migrate-resumes-to-private --dry-run
php artisan staff:send-birthday-emails --date=2026-03-02
```

## Deployment Notes

- run `php artisan migrate --force` during deploys that include schema changes
- build frontend assets with `npm run build`
- ensure queue workers and the scheduler are running in production
- configure mail and Cloudinary before enabling production submissions/uploads

## Miscellaneous

- sitemap: `/sitemap.xml`
- legacy sitemap alias: `/sitemap`
- ads.txt: `/ads.txt`
- uploads use Cloudinary when configured, otherwise local/public storage
