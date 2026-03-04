# Glow FM Platform

Glow is a Laravel 12 + Livewire application that powers:
- A public radio station website
- An internal admin CMS/dashboard
- A JSON API consumed by mobile clients
- Optional React Native mobile app code in `mobile/GlowFMRadio`

## Core Features
- Public pages for news, blog, podcasts, events, careers, shows, schedule, OAPs, and staff profiles
- Admin modules for content management, team management, settings, inbox/newsletter, ads, stream controls, and analytics
- Role-based access for `admin`, `staff`, `corp_member`, `intern`, and `user`
- Listener and admin API authentication with bearer tokens
- Engagement endpoints (comments, likes, reactions, bookmarks, shares, RSVPs, subscriptions)
- Cloudinary-backed uploads with local storage fallback
- Automated tasks for birthday emails and push notifications

## Tech Stack
- PHP `^8.2`
- Laravel `^12`
- Livewire `^3.7`
- Blade + Tailwind CSS 4 + Alpine.js
- Vite 7
- MySQL or SQLite
- Spatie Laravel Permission
- Cloudinary Laravel SDK

## Repository Layout
- `app/Livewire` Livewire UI components (public and admin)
- `app/Http/Controllers/Api` API endpoints for web/mobile clients
- `app/Models` Domain models (News, Blog, Event, Podcast, Show, Team, Career, etc.)
- `routes/web.php` Web routes (public + admin)
- `routes/api.php` API routes
- `routes/console.php` Scheduled and custom Artisan commands
- `mobile/GlowFMRadio` React Native mobile app

## Requirements
- PHP 8.2+
- Composer 2+
- Node.js 20+ and npm
- MySQL 8+ (or SQLite for quick local setup)

## Quick Start
1. Install dependencies and bootstrap the app:

```bash
composer run setup
```

2. Create the storage symlink:

```bash
php artisan storage:link
```

3. Run local development services (Laravel server, queue listener, logs, Vite):

```bash
composer run dev
```

By default, the app loads environment values from `.env` (copied from `.env.example` by `composer run setup` if missing).

## Manual Setup (Alternative)
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
Minimum commonly-used keys:
- `APP_NAME`
- `APP_URL`
- `DB_CONNECTION` and DB credentials
- `QUEUE_CONNECTION` (defaults to `database`)
- `MAIL_*` settings for contact/newsletter/birthday emails
- `CLOUDINARY_URL` or (`CLOUDINARY_CLOUD_NAME`, `CLOUDINARY_API_KEY`, `CLOUDINARY_API_SECRET`)
- `FCM_SERVER_KEY` for push notifications
- `MYSQLDUMP_PATH` (optional) for admin database download route

## Database and Roles
- Run migrations with `php artisan migrate`
- Seed starter data with `php artisan db:seed`
- `RoleSeeder` creates `admin` and `staff` Spatie roles
- If you need a first admin user, create/update one in Tinker and assign role:

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

## Queue and Scheduler
The app uses queued jobs and scheduled tasks.

Run queue worker:
```bash
php artisan queue:work
```

Run scheduler in development:
```bash
php artisan schedule:work
```

Production cron entry:
```cron
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

Configured scheduled commands include:
- `staff:send-birthday-emails` (daily at 07:00 app timezone)
- `push:now-playing` (every 5 minutes)
- `push:show-starting` (every 5 minutes)

## API Overview
Base URL:
- `http://localhost:8000/api` when using `php artisan serve`
- `http://localhost/Glow/public/api` in common Apache/XAMPP setups

Public content endpoints:
- `GET /home`, `GET /search`, `GET /now-playing`
- `GET /news`, `GET /blog`, `GET /events`, `GET /podcasts`, `GET /shows`

Public auth (listener accounts):
- `POST /public/auth/register`
- `POST /public/auth/login`
- `GET /public/auth/me` (requires bearer token)
- `POST /public/auth/logout` (requires bearer token)

Admin/staff auth:
- `POST /auth/login`
- `GET /auth/me` (requires bearer token)
- `POST /auth/logout` (requires bearer token)

Admin API:
- Prefix: `/admin/*`
- Requires `api_token` + `api_admin_or_staff`

Token notes:
- Tokens are sent via `Authorization: Bearer <token>`
- Tokens are stored hashed in `users.api_token`
- Logging in issues a new token and replaces the old one

## Mobile App
React Native app lives in `mobile/GlowFMRadio`.

Quick run:
```bash
cd mobile/GlowFMRadio
npm install
npm start
```

Before local mobile testing, update API URL in:
- `mobile/GlowFMRadio/src/config.ts`

## Useful Commands
- `php artisan route:list`
- `php artisan test`
- `composer test`
- `php artisan pail`
- `php artisan careers:migrate-resumes-to-private --dry-run`
- `php artisan staff:send-birthday-emails --date=2026-03-02`

## Notes
- `sitemap` is available at `/sitemap`
- `ads.txt` is served at `/ads.txt`
- File uploads use Cloudinary when configured, otherwise local/public storage
