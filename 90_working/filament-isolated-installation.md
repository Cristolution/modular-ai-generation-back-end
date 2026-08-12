# Filament — Zero-Risk Isolated Installation Plan

**Status:** Not started. Holding for later.
**Owner:** TBD.
**Prereq:** Frontend presentation seeders land first; we'll revisit
Filament only after the MGF seeders and the frontend are stable.

> This document is parked in `90_working/` (NOT in `00_docs/`) on
> purpose. `00_docs/` holds canonical architecture + contract docs
> that describe what the system IS. This file describes work that
> HAS NOT BEEN DONE YET and is intentionally out of scope of the
> current shipping build.

## Why "isolated"

The default Filament install wires the panel to `App\Models\User`
because that's the conventional name. That would:

- require `User` to implement `FilamentUserContract`
- require `User->canAccessPanel()` to gate access
- require `User` to expose a `password` column matching Filament's hash
  cast (vs. our existing `password_hash`)
- share middleware/session state between `/admin/*` and `/api/v1/*`

**We refuse all of that.** Filament must live behind its own guard,
its own model, and its own table. The existing `users` table, the
existing `App\Models\User`, the existing `web` and `sanctum` guards,
and the existing seeders are NOT TOUCHED.

## What this plan touches (additive only)

| File                                                | Type                |
|-----------------------------------------------------|---------------------|
| `app/Models/Admin.php`                              | NEW                 |
| `app/Providers/Filament/AdminPanelProvider.php`      | NEW                 |
| `database/migrations/..._create_admins_table.php`    | NEW (one table only)|
| `database/seeders/AdminSeeder.php`                  | NEW                 |
| `config/auth.php`                                   | APPEND one `'admin'` guard + one `'admins'` provider. No existing lines edited. |
| `composer.json` / `composer.lock`                   | Auto-edited by `composer require` (acceptable). |

## What this plan does NOT touch

- `App\Models\User` — never opened
- `users` table — no migration edits
- `password_hash` column — never read or written
- `web` guard — unchanged
- `sanctum` guard — unchanged
- Routes under `/api/v1/*` — unchanged
- Routes under any other prefix — unchanged
- `database/seeders/ProjectSeeder.php` — unchanged
- `database/seeders/TemplateSeeder.php` — unchanged
- `database/seeders/Concerns/MgfFileBuilders.php` — unchanged
- `data.json` / `style.css` / `layout.css` / `slide-NN.html` — unchanged
- `--mgf-*` tokens / `mgf-*` classes — unchanged
- All existing tests — unchanged

## Execution steps (run in this order, each one independently verifiable)

### Step 1. Install Filament via Composer

```bash
composer require filament/filament:"^3.2"
```

This will modify `composer.json` and `composer.lock` only. No application
code is touched by Composer. Verify with `composer show filament/filament`.

### Step 2. Create the isolated Admin model

Create `app/Models/Admin.php`:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Admin model — isolated from App\Models\User.
 *
 * Lives in its own `admins` table, authenticated by its own `admin`
 * guard. The existing `App\Models\User` model, the existing `users`
 * table, and the existing `password_hash` column are NOT touched.
 *
 * Filament is told (via the AdminPanelProvider) to use the `admin`
 * guard, which resolves to this model — never to App\Models\User.
 */
class Admin extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'admins';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // The `admins` table is admin-only by definition;
        // anyone whose row is here can access the panel.
        return true;
    }
}
```

### Step 3. Create the migration

```bash
php artisan make:migration create_admins_table
```

Then in the generated migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
```

Then:

```bash
php artisan migrate
```

This creates ONLY the `admins` table. No other tables are touched.

### Step 4. Wire the `admin` guard (additive only)

Open `config/auth.php`. Add (do not edit existing lines):

```php
// inside 'guards' array:
'admin' => [
    'driver'   => 'session',
    'provider' => 'admins',
],

// inside 'providers' array:
'admins' => [
    'driver' => 'eloquent',
    'model'  => App\Models\Admin::class,
],
```

Existing `'web'`, `'sanctum'` guards and `'users'` provider are
unchanged.

### Step 5. Create the admin panel provider

Create `app/Providers/Filament/AdminPanelProvider.php`:

```php
<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('admin')                // ← the key isolation switch
            ->login()
            ->brandName('MGF Admin')
            ->colors([
                'primary' => '#2F80FF',
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

Register the provider by adding to `bootstrap/providers.php`
(Laravel 11 style) or `config/app.php` providers array (Laravel 10
style) — whichever this project uses. The new entry is purely
additive.

### Step 6. Create the dev admin seeder

Create `database/seeders/AdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends BaseSeeder
{
    protected function seed(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@mgf.local'],
            [
                'name'     => 'MGF Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
```

Add the seeder call to `DatabaseSeeder::run()` as a final entry —
additive, no reordering of existing seeders.

### Step 7. Smoke test

```bash
php artisan migrate:fresh --seed      # existing 9 seeders still pass
php artisan serve                       # in another shell
```

Manually verify in the browser (no automated test at this stage):

| URL                                     | Expected                                                   |
|-----------------------------------------|------------------------------------------------------------|
| `GET http://127.0.0.1:8000/api/v1/types`        | Same JSON as before, status 200.                    |
| `GET http://127.0.0.1:8000/api/v1/templates`    | Same JSON as before, status 200, paginated.        |
| `GET http://127.0.0.1:8000/admin`               | 302 redirect to `/admin/login`.                    |
| `POST http://127.0.0.1:8000/admin/login`        | Login with `admin@mgf.local` / `password` → 302 to `/admin`. |
| `GET http://127.0.0.1:8000/admin` (after login) | Filament dashboard renders (empty — no resources yet). |

### Step 8. Add resources one at a time (future work)

When we're ready to expose admin CRUD on `Project` / `Template` / `File`,
those come later as `app/Filament/Resources/ProjectResource.php`, etc.
Each one is its own review. They continue to use the existing Eloquent
models and existing `files` table — Filament just gets a UI on top.

## What this plan is NOT

- It's NOT a recommendation to install Filament right now. The user
  wants to land more MGF seeders first.
- It's NOT pre-approved infra. It's a parked plan awaiting a future
  go-ahead.
- It's NOT part of the shipping build. When this plan executes, all
  the files added live under `app/Models/Admin.php`,
  `app/Providers/Filament/`, `database/migrations/..._create_admins_table.php`,
  `database/seeders/AdminSeeder.php`, plus an additive append to
  `config/auth.php`.

## When to revisit this plan

When all three of these are true:

1. The frontend has stabilized the presentation formats that need
   backend support.
2. The MGF seeders (`pitch` / `summary` / `minimal` archetypes and
   their variants) are complete enough that we don't expect
   substantial schema or file-shape changes.
3. We want a UI for non-developer content review / publishing — i.e.
   the bottleneck is admin, not engineering.

Until then, this file stays in `90_working/` and is not part of any
release.
