# Task Manager API

A Laravel-based backend API for a task manager application, currently focused on **SPA authentication using Laravel Sanctum** (cookie/session-based auth).

## Overview

This project provides the backend foundation for a task manager app and currently includes:

- User registration
- User login
- Authenticated user retrieval
- User logout
- Health check endpoint

Authentication is implemented with **Laravel Sanctum for first-party SPAs**, using session cookies and CSRF protection.

---

## Tech Stack

- PHP `^8.3`
- Laravel `^13.0`
- Laravel Sanctum `^4.0`
- SQLite (default local setup)
- Pest (testing)

---

## Authentication Model (Sanctum SPA)

This API uses Sanctum's **stateful SPA authentication** flow:

1. SPA requests CSRF cookie from `/sanctum/csrf-cookie`
2. SPA sends credentials to `/api/login` (or `/api/register`)
3. Laravel creates an authenticated session cookie
4. Protected routes under `auth:sanctum` are accessible
5. SPA can log out via `/api/logout`

Relevant setup in this project:

- Stateful API middleware enabled in `bootstrap/app.php`
- Sanctum config in `config/sanctum.php`
- CORS + credentials support in `config/cors.php`

---

## API Endpoints

Base API prefix: `/api`

| Method | Endpoint        | Auth Required        | Description                       |
|--------|-----------------|----------------------|-----------------------------------|
| GET    | `/api/health`   | No                   | Basic health check                |
| POST   | `/api/register` | No                   | Register a user and start session |
| POST   | `/api/login`    | No                   | Log in and start session          |
| GET    | `/api/user`     | Yes (`auth:sanctum`) | Get current authenticated user    |
| POST   | `/api/logout`   | Yes (`auth:sanctum`) | Log out and invalidate session    |

Sanctum CSRF endpoint:

| Method | Endpoint               | Description                           |
|--------|------------------------|---------------------------------------|
| GET    | `/sanctum/csrf-cookie` | Issues CSRF token cookie for SPA auth |

---

## Request Examples

### Register

`POST /api/register`

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

### Login

`POST /api/login`

```json
{
  "email": "jane@example.com",
  "password": "secret123",
  "remember": true
}
```

---

## Local Setup

```bat
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

If you are also running frontend assets:

```bat
npm install
npm run dev
```

---

## Environment Notes (SPA Auth)

For cross-origin SPA login to work, verify these environment values in `.env`:

- `APP_URL`
- `SESSION_DOMAIN`
- `SANCTUM_STATEFUL_DOMAINS`

Also ensure your frontend HTTP client sends credentials (`withCredentials: true` in Axios, or `credentials: 'include'` in fetch).

---

## Testing

```bat
php artisan test
```

or

```bat
composer test
```

---

## Security Notes

- CSRF cookie must be fetched before login/register in SPA flow.
- Keep `.env` secrets private.
- Use secure cookie/session settings in production (HTTPS, proper domain config).

---

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
