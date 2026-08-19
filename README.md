# ChibuzoConnect

**ChibuzoConnect** (formerly EventPlug) is a full-stack event management and ticketing platform built with Laravel. It allows event managers to create and sell tickets for their events online, while giving attendees a smooth, guest-friendly checkout experience — no forced account creation required.

🔗 **Live site:** [chibuzoconnect.com](https://chibuzoconnect.com)

---

## Features

### For Event Managers
- **Three-step event creation wizard** for quick, guided event setup
- Support for **in-person, online, and hybrid** events
- **Ticket management** with multiple ticket tiers, pricing, and perks
- **Promo codes** for discounted ticket sales
- **Waitlists** for sold-out events
- **QR code check-in** system for fast, contactless entry on event day
- Flexible **commission handling** — choose whether the platform's 5% fee is added on top of the ticket price (attendee pays) or deducted from earnings (event manager absorbs it)
- Payout settlement within 3 business days after an event concludes

### For Attendees
- **Guest checkout** — no account required to purchase tickets
- Secure payments via **Paystack** (currently in test mode, pending Paystack business activation review)
- Instant **email confirmation** with QR code ticket
- **In-app notifications** for event updates

### Platform / Admin
- **Role-based authentication** (Admin, Event Manager)
- **Filament-powered admin panel** for managing users, events, transactions, and platform settings
- **OTP-based email verification** — custom six-box input with auto-advance, backspace navigation, paste support, and shake animation on error
- **Queue-based email delivery** for reliable, non-blocking notifications
- Webhook-driven payment confirmation via Paystack, with race-condition-safe order processing

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13 (PHP 8.4) |
| Admin Panel | Filament |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Auth | Laravel Breeze |
| Payments | Paystack |
| Database | MySQL |
| Queue | Database driver, processed via Supervisor |
| Asset Bundling | Vite |
| Hosting | Hostinger (shared hosting) |

---

## How Payments Work

1. **Onboarding** — Event managers sign up and verify their email via OTP. No manual vetting is currently required beyond email verification.
2. **Checkout** — Attendees purchase tickets through a guest-friendly checkout flow, processed via Paystack.
3. **Fund holding** — All payments settle into ChibuzoConnect's central Paystack account first; funds are not split directly to event managers at the point of sale.
4. **Commission** — ChibuzoConnect takes a 5% commission per transaction. Event managers choose whether this is passed on to attendees or deducted from their own earnings.
5. **Settlement** — Event managers are paid out to their registered bank account within 3 business days of their event ending.

---

## Local Development Setup

This project runs locally using [Laravel Herd](https://herd.laravel.com/) on Windows.

```bash
# Clone the repository
git clone https://github.com/TheTechGuy77777/event-management-system.git
cd event-management-system

# Install PHP dependencies
composer install

# Install JS dependencies and build assets
npm install
npm run build

# Copy environment file and configure
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run:
php artisan migrate --seed

# Create the storage symlink
php artisan storage:link

# Serve the app (if not using Herd's automatic domain)
php artisan serve
```

### Required `.env` values
- `DB_*` — MySQL connection details
- `PAYSTACK_PUBLIC_KEY` / `PAYSTACK_SECRET_KEY` — Paystack API credentials
- `MAIL_*` — SMTP credentials for OTP and notification emails
- `ADMIN_NAME` / `ADMIN_EMAIL` / `ADMIN_PASSWORD` — used by the admin seeder to create the initial admin account

---

## Deployment

ChibuzoConnect is deployed on Hostinger shared hosting via SSH. Since Hostinger's shared plans don't allow setting a custom document root, the deployed structure splits the Laravel application root and the public web root into two separate directories: `public_html` serves as the actual web root (containing `index.php`, compiled assets, and a symlink into app storage), while the rest of the Laravel application lives in a sibling `laravel_app` directory. `index.php` and the storage/build directories are connected via symlinks so Laravel can resolve paths correctly across the split.

Deploys are handled through a `deploy.sh` script (run on the server) that pulls the latest code, installs dependencies, runs migrations, and rebuilds caches. Frontend asset changes (CSS/JS) require running `npm run build` locally and uploading the compiled `build/` output separately, since Node isn't available on the server.

### Challenges & Debugging

Getting this structure right on shared hosting surfaced a number of real-world deployment issues, each traced back to root cause and resolved:
- A missing `.env` file on first deploy (not tracked in git, as expected) causing a generic 500 error with no visible stack trace
- A database name mismatch between `.env` and the actual provisioned database, caught by reading raw MySQL access errors from the terminal
- `MissingAppKeyException` after `.env` was regenerated, resolved via `php artisan key:generate`
- Broken symlinks (`storage`, `build`) after restructuring the app into split directories, requiring manual relinking so Laravel's `public_path()` and `storage_path()` resolve correctly
- Vite's `ViteManifestNotFoundException`, resolved by symlinking the compiled `public/build` directory back into Laravel's default expected path
- Hostinger's Git auto-deploy feature silently overwriting the manually restructured `public_html` on a routine push, resolved by disconnecting the auto-deploy integration in favor of a controlled, script-based deploy process

---

## Author

**Chibuzo Ogbogu** (Ikenga)
Full-stack Laravel developer
- GitHub: [@TheTechGuy77777](https://github.com/TheTechGuy77777)
- LinkedIn: [chibuzo-ogbogu](https://linkedin.com/in/chibuzo-ogbogu)

---

## License

This project is proprietary software. All rights reserved.
