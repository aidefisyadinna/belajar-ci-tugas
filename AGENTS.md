# AGENTS.md

CodeIgniter 4.7 e-commerce app (PHP 8.2+). MySQL (`db_ecommerce`), SQLite3 `:memory:` for tests.

## Commands

```bash
php spark serve                    # dev server at localhost:8080
php spark migrate                  # run migrations
php spark db:seed UserSeeder       # seed a specific seeder
php spark test                     # run all tests
./vendor/bin/phpunit               # run all tests (direct)
./vendor/bin/phpunit tests/unit/HealthTest.php --filter testBaseUrlHasBeenSet  # single test
php spark make:controller Foo      # generate controller
php spark make:model Foo           # generate model
php spark make:migration AddXToY   # generate migration
```

## Architecture

- **Auth**: custom session filter (`App\Filters\Auth`). Checks `session('isLoggedIn')`. Routes use `['filter' => 'auth']`.
- **Routes**: all explicit in `app/Config/Routes.php`. No auto-routing.
- **PSR-4**: `App\` → `app/`, `Config\` → `app/Config/`
- **Cart**: `service('cart')` from `jason-napolitano/codeigniter4-cart-module`
- **Views**: extend `layout`, use `$this->section('content')` / `$this->endSection()`
- **Transaction status**: 0=Menunggu Pembayaran, 1=Sudah Dibayar, 2=Sedang Dikirim, 3=Selesai, 4=Dibatalkan
- **Soft deletes** on `ProductModel` and `UserModel`
- **Ongkir**: RajaOngkir Komerce API. API key in env as `COST_KEY`.
- **OAuth**: Google & Facebook login via `App\Controllers\OAuthController`. Routes: `auth/google`, `auth/facebook`. Callbacks auto-login or auto-register users.
- **Laporan**: `App\Controllers\LaporanController` handles pendapatan, export PDF/Excel, produk terlaris, piutang, arus kas, laba rugi.
- **Beban**: `App\Models\BebanModel` for `beban` table (kas keluar). Used by arus kas & laba rugi.
- **Env**: copy `env` to `.env`, set DB creds, API keys, and OAuth credentials (`GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `FACEBOOK_APP_ID`, `FACEBOOK_APP_SECRET`)

## Key dirs

| Path | Purpose |
|------|---------|
| `app/Controllers/` | Request handlers |
| `app/Models/` | extend `CodeIgniter\Model` |
| `app/Database/Migrations/` | Timestamp-prefixed migration files |
| `app/Database/Seeds/` | Seeder classes |
| `app/Config/Routes.php` | All route definitions |
| `app/Config/Database.php` | DB config (tests group auto-selected in `testing` env) |
| `app/Filters/Auth.php` | Custom session auth |
| `public/img/` | Product image uploads |
| `public/NiceAdmin/` | Admin template static assets |
| `vendor/codeigniter4/framework/` | CI4 framework source |

## TUGAS_REFERENSI.md & TUGAS_AKHIR_REFERENSI.md

- `TUGAS_REFERENSI.md` — original PDF-based task specs (profile, upload bukti, admin status, API, laporan)
- `TUGAS_AKHIR_REFERENSI.md` — additional feature specs: **3A** Produk Terlaris, **3B** Piutang, **3C** Arus Kas, **3D** Laba Rugi
- `TUGAS_AKHIR_REFERENSI_3E.md` — **3E** OAuth Google & Facebook login
