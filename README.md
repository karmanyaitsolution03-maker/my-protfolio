# Rishabh Parekh — AI Command Center (Laravel Dynamic Version)

Aa zip mā **dynamic Laravel version** che — badhu content (skills, projects, experience,
achievements, settings, contact messages) **database** māthi āve che, ane ek **admin panel**
che jethi code touch karyā vagar update kari shakāy.

> Aa "overlay" package che — fresh Laravel project upar copy karvānu che.
> (Vendor/framework files zip mā nathi — composer e install karshe.)

---

## ⚙️ Setup Steps (5 minute)

### 1. Fresh Laravel project banāvo (Laravel 11)
```bash
composer create-project laravel/laravel portfolio
cd portfolio
```

### 2. Aa zip nā folders project mā copy karo
Zip māthi nīche nā folders/files ne project root mā **copy/overwrite** karo:

```
app/        →  app/         (Models + Controllers)
database/   →  database/    (migrations + seeders)
resources/  →  resources/   (views — portfolio + admin)
routes/web.php  →  routes/web.php   (REPLACE karvāno)
```

### 3. .env mā āṭlu add karo
```env
ADMIN_PASSWORD=tamāro-strong-password
```
Laravel 11 default **SQLite** vāpre che — kāi DB setup ni jarūr nathi.
MySQL joiye to `.env` mā `DB_CONNECTION=mysql` + credentials set karo.

### 4. Migrate + Seed
```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\PortfolioSeeder
```
(Athvā `DatabaseSeeder.php` mā `$this->call(PortfolioSeeder::class);` add karine `php artisan migrate --seed`)

### 5. Run!
```bash
php artisan serve
```
- **Site:**  http://127.0.0.1:8000
- **Admin:** http://127.0.0.1:8000/admin/login  (password = ADMIN_PASSWORD)

---

## 🛠 Admin Panel māthi shu manage thāy?

| Section | Shu update thāy |
|---|---|
| **Settings** | Name, designation, tagline, email, LinkedIn, location, about text, response time |
| **Skill Categories / Skills** | Module name, icon, wide/normal, skill levels (%), order |
| **Experiences** | Company, role, period, LIVE flag, responsibilities (JSON array) |
| **Projects** | Title, kicker, description, color, architecture diagram (JSON), metrics, tags |
| **Achievements** | Animated counter ke text value + label |
| **Messages** | Contact form thi āvelā messages (DB mā save thāy che) |

### Project "arch" JSON example (animated data-flow diagram):
```json
[
  {"label": "WEB + MOBILE", "sub": "CLIENTS"},
  {"label": "LARAVEL API", "sub": "AUTH · RBAC", "hot": true},
  {"label": "MYSQL", "sub": "CORE DB"}
]
```
`"hot": true` vāḷā node ne project nā accent color ni border maḷe.

---

## 📌 Important — pahelā badlo:
1. **Settings → email** : `rishabh.parekh@example.com` → tamāro real email
2. **Settings → linkedin** : real LinkedIn URL
3. `ADMIN_PASSWORD` strong rākho

## 📧 Contact form
Messages **database mā save** thāy che (Admin → Messages).
Email notification pan joiye to `app/Http/Controllers/ContactController.php` mā
commented Mail code uncomment karo ane `.env` mā `MAIL_*` set karo.

## 📄 Resume download
`/resume` route DB māthi live `.txt` subject-file generate kare che.
Real **PDF resume** māṭe: PDF ne `public/resume.pdf` mā mūko ane
`routes/web.php` mā resume route ne āma badlo:
```php
Route::get('/resume', fn () => response()->download(public_path('resume.pdf')))->name('resume.download');
```

## 🔐 Note on admin auth
Simple session + env-password auth che (single-admin portfolio māṭe enough).
Multi-user joiye to Laravel Breeze add kari ne `gate()` check ne
`auth` middleware thi replace karo.

---

### File map
```
app/Http/Controllers/PortfolioController.php   # home page + resume
app/Http/Controllers/ContactController.php     # contact POST (JSON)
app/Http/Controllers/Admin/AdminController.php # login + settings + generic CRUD
app/Models/*.php                               # 7 models
database/migrations/...create_portfolio_tables.php
database/seeders/PortfolioSeeder.php           # tamāro current content seeded
resources/views/portfolio.blade.php            # full cinematic frontend (dynamic)
resources/views/admin/*.blade.php              # admin panel
routes/web.php
```

Happy shipping! 🚀
