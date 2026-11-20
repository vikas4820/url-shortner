# URL Shortener

A Laravel 13 web application for creating and sharing short URLs with signed-in users. Users belong to companies, and their roles determine which links they can create, view, and follow. The web interface uses Blade, Tailwind CSS, and Vite. SQLite is the default database.

## Project overview

- **Authentication and user management.** Users sign in and out with Laravel sessions. The dashboard and user-management pages apply role and company rules. The database seeder creates the SuperAdmin account using a parameterized SQL insert.
- **Invitations.** Authorized users create an invitation for an email address and role in their company. The application displays a shareable link that expires after 24 hours. Recipients open it while signed out and create their account with the assigned role. SuperAdmins can invite Managers, Sales users, and Members. Admins can invite Sales users.
- **Short links.** Sales users can create links to HTTP or HTTPS destinations and set an optional expiration date. Each link has a unique short code. Successful redirects increment the link's click count.
- **Access control.** Spatie Laravel Permission supplies roles and permissions. Laravel policies apply company and ownership checks when users open protected records. Guests are redirected to sign in when opening a short link.

### Short-link permissions

| Role | Can create | Links visible in the list and available to follow |
| --- | --- | --- |
| SuperAdmin | No | No access |
| Admin | No | Links from other companies |
| Manager | No | All links in their own company |
| Sales | Yes | Links created by other users in their own company |
| Member | No | Links created by other users in their own company |

Following a link also requires it to be unexpired.

## Local setup

### Prerequisites

- PHP 8.4 with the PDO SQLite driver and Composer 2. PHP 8.4 is required by the locked Pest development dependency.
- Node.js 22.12 or newer with npm.
- Git if cloning the repository. You can also extract a downloaded copy and open the project root.

### Install and run

Run the commands from the directory containing `artisan` and `composer.json`. Use a fresh checkout for the initial setup and keep an existing `.env` if you have already configured this copy.

```bash
composer install
cp .env.example .env
php artisan key:generate
php -r "is_file('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
npm ci
npm run build
php artisan serve --host=localhost
```

On Windows PowerShell, use `Copy-Item .env.example .env` for the copy step. Open [http://localhost:8000](http://localhost:8000) while the server is running.

The example configuration uses SQLite and Laravel's local defaults. If you change the server address or port, update `APP_URL` in `.env` to match. If you already completed the setup, start the server again with `php artisan serve --host=localhost`. The initial seed step assumes an empty database.

### Demo accounts

These credentials are for the seeded local database.

| Role | Company | Email | Password |
| --- | --- | --- | --- |
| SuperAdmin | System | `superadmin@example.com` | `SuperAdmin123!` |
| Admin | Acme | `admin@acme.com` | `password` |
| Manager | Acme | `manager@acme.com` | `password` |
| Sales | Acme | `sales@acme.com` | `password` |
| Member | Acme | `member@acme.com` | `password` |
| Admin | Global Tech | `admin@global.com` | `password` |

To try the link flow, sign in as Acme Sales and create a short link. Copy the resulting link, then open it as the Acme Manager in a separate browser session. Open the same link in a signed-out session to see the login requirement.

## Tests

```bash
php artisan test
```

The test configuration uses an in-memory SQLite database. Feature tests cover login, user authorization, invitation creation and acceptance, role permissions, and short-link visibility and redirects.

## Code layout

| Path | Contents |
| --- | --- |
| `routes/web.php` | Session-authenticated web routes, guest invitation routes, and rate limits |
| `app/Http/Controllers` and `app/Http/Requests` | Request handling and validation |
| `app/Policies` and `app/Services` | Authorization, invitation processing, and short-link logic |
| `app/Models` and `database/migrations` | Companies, users, invites, short links, and their relationships |
| `database/seeders` | Local demo companies, accounts, roles, invitations, and links |
| `resources/views` | Blade layouts and pages |
| `tests/Feature` | Feature and authorization tests |

## AI usage disclosure

I used OpenAI Codex to assist with the user interface design and to write this README.
