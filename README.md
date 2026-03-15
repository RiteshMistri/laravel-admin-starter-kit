<h1 align="center">
  <br>
  <a href="https://github.com/RiteshMistri/laravel-admin-starter-kit">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Admin Starter Kit">
  </a>
  <br>
  Laravel Admin Starter Kit
  <br>
</h1>

<h4 align="center">A full-featured, production-ready Laravel Admin Panel Starter Kit with Role-Based Access Control, Module-Based Permissions, and a modern TailAdmin UI — built to save you days of boilerplate work.</h4>

<p align="center">
  <a href="https://github.com/RiteshMistri/laravel-admin-starter-kit/blob/main/LICENSE">
    <img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License">
  </a>
  <img src="https://img.shields.io/badge/Laravel-12.x-red?logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?logo=php" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/TailwindCSS-4.x-cyan?logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-lightblue?logo=alpine.js" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Spatie%20Permissions-7.x-green" alt="Spatie Permissions">
</p>

<p align="center">
  <a href="#-key-features">Key Features</a> •
  <a href="#-demo-credentials">Demo</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-role--permission-system">Permissions</a> •
  <a href="#-folder-structure">Structure</a> •
  <a href="#-contributing">Contributing</a>
</p>

---

## 🚀 Key Features

- **🔐 3-Tier Role System** — Super Admin, Admin, and User roles, each with different access levels
- **🛡️ Module-Based Permissions** — Fine-grained, dotted permissions (e.g., `user-management.read`, `user-management.add`) using Spatie's Laravel Permission package
- **✅ Permissions Matrix UI** — Intuitive table-based permission assignment with master "Select All" column headers per action
- **👑 Super Admin Bypass** — Super Admin automatically bypasses all permission checks via `Gate::before` — no need to manually assign permissions
- **👤 User Management** — Full CRUD for users with role assignment and per-user permission overrides
- **🎨 TailAdmin UI** — Beautiful, responsive admin UI built with TailwindCSS v4 and Alpine.js
- **🔑 Laravel Breeze Auth** — Authentication scaffolding (Login, Register, Password Reset, Email Verification)
- **📊 Dashboard & Charts** — Pre-built dashboard with ApexCharts integration
- **📅 Calendar** — FullCalendar.js integration for event management
- **🧩 UI Components** — Pre-built Alerts, Badges, Buttons, Avatars, Images, Videos pages
- **⚡ Vite Asset Bundling** — Fast dev server and optimized production builds

---

## 🖥️ Demo Credentials

After seeding, you can log in with these default accounts:

| Role        | Email               | Password   |
|-------------|---------------------|------------|
| Super Admin | `admin@admin.com`   | `password` |
| Admin       | `admin2@admin.com`  | `password` |

> **Note:** The Super Admin account is hidden from the Users list for security reasons.

---

## 🛠️ Tech Stack

| Layer        | Technology                          |
|--------------|-------------------------------------|
| Framework    | Laravel 12.x                        |
| Language     | PHP 8.2+                            |
| Auth         | Laravel Breeze                      |
| Permissions  | Spatie Laravel Permission 7.x       |
| Frontend CSS | TailwindCSS 4.x + @tailwindcss/forms|
| Frontend JS  | Alpine.js 3.x                       |
| Asset Build  | Vite + laravel-vite-plugin          |
| Charts       | ApexCharts                          |
| Calendar     | FullCalendar.js                     |
| Date Picker  | Flatpickr                           |
| HTTP Client  | Axios                               |
| Database     | MySQL / SQLite (configurable)       |

---

## 📦 Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18.x & npm
- MySQL or another supported database

### Steps

**1. Clone the repository**

```bash
git clone https://github.com/RiteshMistri/laravel-admin-starter-kit.git
cd laravel-admin-starter-kit
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node.js dependencies**

```bash
npm install
```

**4. Set up the environment file**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Configure your database**

Open `.env` and update your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_admin_starter
DB_USERNAME=root
DB_PASSWORD=
```

**6. Run database migrations and seed default data**

```bash
php artisan migrate --seed
```

This will automatically:
- Create all tables
- Create the `super admin`, `admin`, and `user` roles
- Create module-based permissions (`user-management.read`, etc.)
- Create default Super Admin (`admin@admin.com`) and Admin (`admin2@admin.com`) accounts

**7. Build assets**

```bash
# For development (with hot reload)
npm run dev

# For production
npm run build
```

**8. Start the development server**

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) and log in with the demo credentials above.

---

## 🔑 Role & Permission System

This starter kit implements a **3-tier access control system**:

### Roles

| Role        | Description |
|-------------|-------------|
| `super admin` | Full unrestricted access to everything. Bypasses all permission checks via `Gate::before`. Hidden from user lists. |
| `admin`       | Can access admin panel features based on assigned permissions. Gets all module permissions by default. |
| `user`        | Basic access. Gets `read` permission on each module by default. |

### Module-Based Permissions

Permissions follow a dotted `module.action` syntax:

```
user-management.read
user-management.add
user-management.edit
user-management.delete
```

> Add new modules by simply adding entries to `RolesAndPermissionsSeeder.php` following the same pattern. The UI will automatically group them in the Permissions Matrix.

### Permissions Matrix UI

The admin panel provides a **Permissions Matrix Table** for visual, user-friendly permission assignment:

- ✅ **Column Header Checkbox** — Check "Read" in the header to select all `Read` checkboxes across all modules at once
- ✅ **Smart Auto-Select** — Selecting `Add`, `Edit`, or `Delete` automatically checks `Read` for that module (you can't add without seeing)
- ✅ **One-Way Binding** — Unchecking an individual row does not affect the column header state

### Super Admin Gate Bypass

In `app/Providers/AppServiceProvider.php`:

```php
Gate::before(function ($user, $ability) {
    if ($user->hasRole('super admin')) {
        return true;
    }
});
```

---

## 📂 Folder Structure

Key files and directories to know about:

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/
│   │           └── UserController.php      # Admin User CRUD
│   └── Providers/
│       └── AppServiceProvider.php          # Gate::before (Super Admin bypass)
│
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── RolesAndPermissionsSeeder.php   # Roles, Permissions, Default Users
│
├── resources/
│   └── views/
│       ├── admin/
│       │   └── users/
│       │       ├── index.blade.php         # User list (paginated)
│       │       ├── create.blade.php        # Create user + permissions matrix
│       │       └── edit.blade.php          # Edit user + permissions matrix
│       ├── layouts/
│       │   └── app.blade.php               # Main layout wrapper
│       └── pages/
│           └── dashboard/                  # Dashboard views
│
├── routes/
│   ├── web.php                             # App + Admin routes
│   └── auth.php                            # Breeze auth routes
│
├── tailwind.config.js
├── vite.config.js
└── .env.example
```

---

## 🧪 Running Tests

```bash
php artisan test
```

---

## ⚙️ Adding New Modules / Permissions

1. Open `database/seeders/RolesAndPermissionsSeeder.php`
2. Add your new permissions to the `$permissions` array:

```php
$permissions = [
    // Existing
    'user-management.read',
    'user-management.add',
    'user-management.edit',
    'user-management.delete',

    // New module example
    'product-management.read',
    'product-management.add',
    'product-management.edit',
    'product-management.delete',
];
```

3. Re-run the seeder:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

The Permissions Matrix UI will automatically display the new module grouped correctly.

---

## 🤝 Contributing

Contributions, issues and feature requests are welcome! Here's how to get started:

1. **Fork** the repository
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Open a **Pull Request**

Please make sure to update tests as appropriate.

---

## 📄 License

This project is open-sourced software licensed under the [MIT License](LICENSE).

---

## 🙏 Credits

- [Laravel](https://laravel.com) — The PHP Framework for Web Artisans
- [TailAdmin](https://tailadmin.com) — Tailwind CSS Admin Dashboard Template
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) — Role & Permission management
- [Alpine.js](https://alpinejs.dev) — Lightweight reactive JavaScript framework
- [TailwindCSS](https://tailwindcss.com) — Utility-first CSS framework

---

---

## 👨‍💻 About the Author

**Ritesh Mistri** — Laravel Developer passionate about building clean, scalable, and developer-friendly web applications.

- 🌐 Website: [riteshmistri.site](https://riteshmistri.site/)
- 🔗 LinkedIn: [linkedin.com/in/ritesh-mistri777](https://www.linkedin.com/in/ritesh-mistri777/)
- 💻 GitHub: [github.com/RiteshMistri](https://github.com/RiteshMistri)

---

<p align="center">Built to help developers save time — by <a href="https://riteshmistri.site/">Ritesh Mistri</a></p>
