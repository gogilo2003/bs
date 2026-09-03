# PR: Laravel 13 Upgrade with TypeScript, Tailwind v4, and Enhanced Reporting

> **Branch:** `upgrade` → `main`
> **Scope:** 19 commits · 182 files · +18,756 / −11,321 lines
> **Files:** `docs/pull-requests-description/PR_001-laravel13-upgrade.md`

---

## Title

`feat: Laravel 13 upgrade with TypeScript, Tailwind v4, and enhanced blood sugar reporting`

---

## Summary

This pull request delivers a major modernization of the application, upgrading the
entire stack from a legacy Laravel 10 / JavaScript / Bootstrap foundation to a
modern Laravel 13 / TypeScript / Vue 3 / Tailwind v4 stack, while introducing a
significantly enhanced blood sugar reporting feature.

---

## What's Changing

### 1. Laravel Framework Upgrade (10 → 13)
- **Framework:** Upgraded to Laravel 13.17 with PHP 8.3+ requirement.
- **Application Bootstrap:** Modernized `bootstrap/app.php` to the new
  `Application::configure()` structure.
- **Middleware:** Replaced legacy `app/Http/Kernel.php` and `TrustHosts.php` with
  Laravel 13's fluently-configured middleware (web/api/alias/replace) in
  `bootstrap/app.php`.
- **Migrations:** Added Laravel 13 default migrations —
  `password_reset_tokens`, `cache`, `cache_locks`, `sessions`, `jobs`,
  `job_batches`.
- **Safe Migrations:** Guarded existing/new migrations with `hasTable` checks so
  they run safely against existing databases without data loss.
- **Auth Stack:** Installed `gogilo/breeze` with the Vue + TypeScript stack.
- **Dependencies:** Updated Core packages — `laravel/framework`, `inertia-laravel`
  v3, `tightenco/ziggy` v2, `laravel/sanctum`, `doctrine/dbal`.

### 2. Frontend Modernization
- **TypeScript Migration:**
  - `resources/js/app.js` → `app.ts`, `bootstrap.js` → `bootstrap.ts`.
  - Added `tsconfig.json` with `strict: true`.
  - Added type declarations (`types/global.d.ts`, `types/index.d.ts`,
    `types/vite-env.d.ts`, `types/ziggy.d.ts`) and `vue-tsc` type checking.
- **Tailwind CSS v4 (CSS-first):**
  - Removed `tailwind.config.js` and `postcss.config.js`.
  - Configured `resources/css/app.css` with `@import "tailwindcss"`,
    `@plugin "@tailwindcss/forms"`, and `@source` directives.
  - Added the `@tailwindcss/vite` plugin to `vite.config.js`.
- **Build Tooling:** Upgraded to Vue 3.5, Vite 8, TypeScript 5.6, and added
  `chart.js` and `date-fns`.
- **UI Components (new):** `BaseButton`, `SuccessButton`, `WarningButton`,
  `InfoButton`, `Sidebar`, and icon set — plus `ReportChart` and `SummaryTable`
  used by the report feature.
- **App Shell:** Replaced the legacy Bootstrap/CSS navbar and Vue Router setup
  with an Inertia-driven layout and a responsive sidebar.

### 3. Enhanced Blood Sugar Reporting
- **A4 Report View:** New interactive report page (`resources/js/Pages/Report.vue`)
  rendering an A4 document sheet with:
  - A 7-day FBS/RBS chart (`ReportChart.vue`, using Chart.js).
  - A readings table (date, time, type, value).
  - Weekly / monthly / quarterly / all-time summary tables (`SummaryTable.vue`).
- **Server-Side PDF:** `ReadingsController@download` generates a branded A4 PDF
  server-side with `barryvdh/laravel-snappy`, rendering an inline SVG line chart
  for FBS/RBS trends plus summary statistics. Supports both inline viewing and
  file download across all report periods (today / week / month / quarterly / all).
- **Statistics Service:** New `ReadingStatsService` centralizes:
  - Mean / min / max for week, month, quarter, and all-time periods.
  - Last-7-days series with interpolation to fill gaps between recorded dates.
  - User-scoped queries.

### 4. Reproducible Setup & Data
- **Seeder:** `DatabaseSeeder` seeds a default user and `ReadingSeeder` provides
  initial reading data for development.
- **Config/Layout:** Refreshed `.env.example`, config files, and `README`.

### 5. Developer Workflow & Documentation
- **Opencode agents** for backend, frontend, discussion-architect, and implementer
  roles, plus the discussion → decision → plan → implement workflow under `docs/`.
- **Skill libraries** for Laravel 10→13 upgrades, Vue/TypeScript modernization,
  legacy code refactoring, thin-controller refactoring, and modernization audits.
- Templates for discussions, ADRs, and implementation plans.

### 6. Bug Fixes
- Fixed reading date shifting.
- Fixed modal backdrop / transparency issues.
- Preserved historical migrations and added dedicated migrations for new tables.

---

## Files Changed (Highlights)

| Area | Files |
|---|---|
| Backend | `composer.json`, `composer.lock`, `bootstrap/app.php`, `bootstrap/providers.php`, `app/Http/*`, `app/Services/ReadingStatsService.php` |
| Frontend | `package.json`, `package-lock.json`, `tsconfig.json`, `vite.config.js`, `resources/js/**` |
| Migration/Data | `database/migrations/*`, `database/seeders/*` |
| Assets | `resources/css/app.css`, removal of legacy `resources/sass/**`, `tailwind.config.js`, `postcss.config.js`, `public/css/*`, `public/js/app.js` |
| Docs/Workflow | `.agents/**`, `AGENTS.md`, `opencode.json`, `docs/**` |

---

## Breaking Changes

- **PHP 8.3+** is now required.
- Run `composer install` / `npm install` to update dependencies.
- Run `php artisan migrate` to create the new (cache / sessions / jobs /
  token) tables.
- TypeScript strict mode may surface type errors in any remaining legacy JS.

---

## Testing

- [ ] `php artisan test` — backend tests pass
- [ ] `npm run build` (`vue-tsc && vite build`) — type check + build pass
- [ ] `npx vue-tsc --noEmit` — clean type check
- [ ] PDF generation works for all report periods (today / week / month / quarterly / all)
- [ ] A4 report renders chart, readings table, and summary statistics correctly
- [ ] New migrations run safely against an existing database (no data loss)

---

## Source

- **Branches**: `upgrade` → `main`
- **Commits**: `git log --oneline main..upgrade` (19 commits, highlights below)
  1. `8522081` — establish Laravel 13 foundation
  2. `61dc5b3` — migrate readings feature, legacy controllers, middleware, frontend
  3. `a340139` — migrate to Tailwind v4 CSS-first
  4. `8400c0a` / `4554ab6` / `fa8d867` — A4 blood sugar report + server-side Snappy PDF
  5. `e39853b` / `9b56be5` — seeders (default user, readings)
  6. `dca95c8` / `22ec81c` — safe/historical migrations
  7. `59196ed` — date shifting + modal fixes
  8. `8e233fb` — backend/frontend agents + discussion→decision→plan workflow
  9. `97d43d5` — remove `.kilocode`
- **Related Decision/Plan**: N/A (direct upgrade; no ADR/plan produced)

## Status

**Ready for Review**

