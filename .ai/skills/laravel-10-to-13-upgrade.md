---
name: laravel-10-to-13-upgrade
description: Migrate a Laravel 10 application to Laravel 13 using a fresh foundation approach, preserving git history and migrating features incrementally.
---

# Laravel 10 → Laravel 13 Fresh Foundation Migration

## Purpose

This skill defines the complete workflow for migrating an existing Laravel 10 application to Laravel 13 by creating a fresh Laravel 13 application and gradually migrating the existing application's features and functionality into it.

This is a **fresh-foundation migration**, not an in-place Laravel upgrade.

The migration uses two sibling directories:

```text
/home/ogilo/Projects/
├── bs/
└── bs-upgrade/
```

Where:

* `bs` is the existing Laravel 10 application.
* `bs-upgrade` is the Laravel 13 migration workspace.

The existing application's Git history must be preserved in `bs-upgrade`.

---

# 1. Migration Strategy

The migration follows this model:

```text
                    LEGACY APPLICATION
                    /home/ogilo/Projects/bs
                              │
                              │
                              │  reference
                              ▼
                    ┌───────────────────┐
                    │  Laravel 13      │
                    │  Fresh Foundation │
                    └─────────┬─────────┘
                              │
                              ▼
                    bs-upgrade / upgrade
                              │
                    migrate functionality
                              │
                              ▼
                    verified Laravel 13
                    application
```

The legacy application is the source of:

* Business functionality
* Existing workflows
* Domain rules
* Existing UI behavior
* Existing routes
* Existing database behavior
* Existing integrations
* Existing tests
* Existing application architecture

The fresh Laravel 13 application is the source of:

* Laravel framework foundation
* Laravel 13 configuration
* Laravel 13 bootstrap structure
* Laravel 13 package ecosystem
* Laravel 13 frontend foundation
* Current Vite configuration
* Current Tailwind configuration
* Current authentication scaffolding
* Current Laravel conventions

---

# 2. Important Terminology

Throughout this skill:

### Legacy project

```text
/home/ogilo/Projects/bs
```

This contains the Laravel 10 application.

### Upgrade project

```text
/home/ogilo/Projects/bs-upgrade
```

This contains the Laravel 13 migration.

### Legacy branch

```text
main
```

The `main` branch in the upgrade repository represents the historical/application baseline that must be preserved.

### Upgrade branch

```text
upgrade
```

The `upgrade` branch is the Laravel 13 migration branch.

---

# 3. Critical Safety Rules

## NEVER modify the legacy application during migration

The directory:

```text
/home/ogilo/Projects/bs
```

is read-only from the perspective of this migration.

Do not:

* Delete files
* Rename files
* Run destructive migrations
* Change dependencies
* Modify configuration
* Reset Git
* Rewrite history
* Change branches

unless the user explicitly requests such an operation on `bs`.

The migration should normally be performed entirely in:

```text
/home/ogilo/Projects/bs-upgrade
```

---

# 4. Verify the Environment Before Starting

Before doing anything destructive, verify:

```bash
pwd
ls -la /home/ogilo/Projects
```

Confirm both projects exist:

```bash
ls -la /home/ogilo/Projects/bs
ls -la /home/ogilo/Projects/bs-upgrade
```

Verify the legacy project:

```bash
cd /home/ogilo/Projects/bs
git status
git branch --show-current
git log --oneline -5
```

Verify the upgrade repository:

```bash
cd /home/ogilo/Projects/bs-upgrade
git status
git branch --show-current
git log --oneline -5
```

Do not proceed if:

* `bs` does not exist.
* `bs-upgrade` does not exist.
* Either directory is not the expected repository.
* The Git state is ambiguous.
* There are unexpected uncommitted changes that could be lost.
* The repositories do not correspond to the expected application.

When in doubt, stop and ask the user.

---

# 5. Preserve Git History

The purpose of keeping the existing `.git` directory in `bs-upgrade` is to preserve the project's existing commit history.

The migration must NOT initialize a new Git repository inside `bs-upgrade`.

Do not run:

```bash
rm -rf .git
git init
```

The existing `.git` directory must remain intact.

---

# 6. Create the Laravel 13 Foundation

The fresh Laravel 13 project must be created outside `bs-upgrade` first.

Use a temporary sibling directory.

For example:

```text
/home/ogilo/Projects/
├── bs/
├── bs-upgrade/
└── bs-laravel-13/
```

The exact temporary directory name may be chosen by the agent, but it must not overwrite either existing project.

Before creation, verify the temporary directory does not already contain an unrelated project.

Create the Laravel 13 application using the current Laravel installer or Composer method appropriate for the environment.

The resulting project must be a genuine fresh Laravel 13 application.

Verify:

```bash
php artisan --version
```

Expected result should indicate Laravel 13.

---

# 7. Do Not Build Laravel 13 Directly Inside bs-upgrade

Do NOT run:

```bash
laravel new bs-upgrade
```

Do NOT overwrite `bs-upgrade` directly.

The required sequence is:

```text
bs-upgrade
    ↓
preserve .git
    ↓
fresh Laravel 13 project created elsewhere
    ↓
copy Laravel 13 project files into bs-upgrade
```

This makes the Git preservation requirement explicit and prevents accidental repository destruction.

---

# 8. Create the Upgrade Branch

Before replacing the contents of `bs-upgrade`, create the migration branch.

From:

```text
/home/ogilo/Projects/bs-upgrade
```

verify the existing branch structure.

Create:

```bash
git switch -c upgrade
```

or use the appropriate equivalent if the branch already exists.

If `upgrade` already exists:

1. Inspect it.
2. Do not overwrite it.
3. Determine whether it is the intended migration branch.
4. Ask the user if there is ambiguity.

The goal is:

```text
main
└── historical/application Git baseline

upgrade
└── Laravel 13 migration
```

---

# 9. Clean bs-upgrade Without Removing .git

Once the following have been verified:

* Correct repository
* Correct path
* Correct branch
* Clean/understood Git state
* Fresh Laravel 13 project exists separately

remove all files from:

```text
/home/ogilo/Projects/bs-upgrade
```

EXCEPT:

```text
.git
```

The cleanup must preserve:

```text
/home/ogilo/Projects/bs-upgrade/.git
```

Do not delete `.git`.

A safe conceptual operation is:

```text
delete everything inside bs-upgrade
except .git
```

After cleanup:

```bash
ls -la /home/ogilo/Projects/bs-upgrade
```

The `.git` directory must still exist.

Then verify:

```bash
git status
git log --oneline -5
```

The historical commit history must still be available.

---

# 10. Copy the Fresh Laravel 13 Foundation

Copy the contents of the temporary Laravel 13 project into:

```text
/home/ogilo/Projects/bs-upgrade
```

Do NOT copy its `.git` directory.

The Laravel 13 project's Git metadata must not replace the existing `.git`.

The result should be:

```text
bs-upgrade/
├── .git/                 ← ORIGINAL Git repository
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── artisan
├── composer.json
├── package.json
└── ...
```

---

# 11. Verify Git History After Foundation Copy

Immediately verify:

```bash
cd /home/ogilo/Projects/bs-upgrade

git status
git log --oneline -10
git branch
```

The history must still be the history of the original project.

Do not accept the result if the repository now contains the fresh Laravel application's Git history.

---

# 12. Establish the Laravel 13 Baseline

Before migrating application functionality, establish a clean Laravel 13 baseline.

Verify:

```bash
php artisan --version
composer validate
npm --version
node --version
```

Install dependencies:

```bash
composer install
npm install
```

Verify:

```bash
php artisan about
npm run build
```

Run available tests:

```bash
php artisan test
```

If the fresh Laravel 13 application has failures before any legacy functionality is migrated, investigate them before proceeding.

The baseline should be known to work before application code is introduced.

---

# 13. Install gogilo/breeze

The Laravel 13 project uses:

```text
gogilo/breeze
```

which is a fork of Laravel Breeze compatible with Laravel 13+.

Do not install the old Laravel Breeze package if `gogilo/breeze` is the intended authentication package.

Install:

```bash
composer require gogilo/breeze
```

If the package is being tested locally, the project may use a Composer path repository such as:

```json
{
    "repositories": {
        "gogilo/breeze": {
            "type": "path",
            "url": "/home/ogilo/Projects/breeze",
            "options": {
                "symlink": true
            }
        }
    }
}
```

The agent must inspect the actual package configuration before assuming a released version is being used.

Verify:

```bash
composer show gogilo/breeze
```

---

# 14. Authentication Migration

The fresh Laravel 13 application and `gogilo/breeze` implementation form the authentication foundation.

Do NOT copy Laravel 10 Breeze files wholesale.

Instead:

1. Inspect the Laravel 10 authentication implementation.
2. Inspect the Laravel 13 `gogilo/breeze` implementation.
3. Identify custom application functionality.
4. Preserve the Laravel 13 authentication foundation.
5. Migrate only application-specific authentication behavior.

Verify:

* Login
* Logout
* Registration
* Password reset
* Password confirmation
* Email verification
* Profile
* Authentication middleware
* Authorization
* Session behavior
* Authentication tests

---

# 15. Begin Gradual Feature Migration

After the Laravel 13 foundation is stable, migrate application functionality gradually.

Do NOT attempt to migrate the entire `bs` application in one operation.

Each feature must be treated as a migration unit.

Examples:

```text
Authentication
Users
Roles
Permissions
Dashboard
Students
Admissions
Staff
Fees
Invoices
Payments
Attendance
Examinations
Reports
Documents
Notifications
Settings
```

The actual order must be determined from the application's dependency graph.

---

# 16. Feature Migration Method

For every feature:

## Step 1 — Discover

Inspect the legacy application:

```text
/home/ogilo/Projects/bs
```

Identify all relevant:

* Models
* Migrations
* Factories
* Seeders
* Controllers
* Requests
* Services
* Repositories
* Actions
* Policies
* Middleware
* Jobs
* Events
* Listeners
* Notifications
* Routes
* Vue pages
* Vue components
* Composables
* Types
* Tests
* Configuration
* Assets

Do not assume that the obvious controller contains the entire feature.

---

## Step 2 — Map Dependencies

Determine:

```text
Feature
├── Database
├── Models
├── Backend services
├── Authorization
├── Routes
├── Controllers
├── Inertia pages
├── Vue components
├── Frontend state
├── External services
└── Tests
```

Identify prerequisites before migrating the feature.

---

## Step 3 — Inspect Laravel 13 Foundation

Before copying code, inspect the corresponding Laravel 13 implementation.

Determine:

* What already exists.
* What has changed.
* What should be preserved.
* What should be adapted.
* What should not be copied.

---

## Step 4 — Migrate Backend

Migrate:

```text
Database
↓
Models
↓
Factories
↓
Seeders
↓
Enums / DTOs
↓
Requests
↓
Policies
↓
Services
↓
Repositories
↓
Actions
↓
Controllers
↓
Routes
```

Adapt Laravel 10 implementation details to Laravel 13.

---

## Step 5 — Migrate Frontend

Then migrate:

```text
Types
↓
Composables
↓
Shared components
↓
Layouts
↓
Pages
↓
Forms
↓
Feature-specific components
```

Use the Laravel 13 project's frontend foundation.

Do not replace the new Vite/Tailwind configuration with the Laravel 10 configuration unless there is a demonstrated compatibility requirement.

---

## Step 6 — Migrate Tests

Migrate relevant tests with the feature.

Classify old tests as:

```text
KEEP
ADAPT
REWRITE
ADD
OBSOLETE
```

Never delete a failing test simply to make the migration pass.

---

## Step 7 — Verify

For each feature:

```text
Backend tests
Frontend build
TypeScript checks
Linting
Routes
Database
Authorization
UI behavior
```

Run the smallest relevant checks first, followed by broader checks.

---

# 17. Migrate Behavior, Not Files

Never blindly copy:

```text
app/
resources/
routes/
config/
bootstrap/
```

from `bs` to `bs-upgrade`.

Instead:

```text
Legacy implementation
        ↓
Understand behavior
        ↓
Identify Laravel 10 assumptions
        ↓
Adapt to Laravel 13
        ↓
Implement in upgrade branch
```

The goal is not to reproduce the old source tree exactly.

The goal is to reproduce the application's functionality on Laravel 13.

---

# 18. Framework Infrastructure Rule

The Laravel 13 project is the authoritative source for:

```text
bootstrap/*
config/*
Vite configuration
Tailwind configuration
package.json
Laravel dependencies
authentication foundation
application bootstrap
framework providers
middleware infrastructure
```

The Laravel 10 project is the authoritative source for:

```text
Business behavior
Domain rules
Existing features
Application workflows
Existing data requirements
```

When these conflict, investigate rather than blindly choosing one.

---

# 19. Dependency Migration

For each package in the Laravel 10 project:

```text
KEEP
UPGRADE
REPLACE
REMOVE
INVESTIGATE
```

Never assume a Laravel 10 package should be installed in Laravel 13.

Check:

* Laravel compatibility
* PHP compatibility
* Vue compatibility
* Vite compatibility
* TypeScript compatibility
* Package maintenance
* Security advisories
* Available alternatives

Avoid upgrading unrelated packages solely because newer versions exist.

---

# 20. Frontend Dependency Migration

Treat frontend dependencies independently.

Inspect:

```text
package.json
package-lock.json
```

from both applications.

Pay particular attention to:

```text
Vue
Inertia
Vite
TypeScript
Tailwind
PrimeVue
ESLint
Prettier
Axios
Ziggy
Wayfinder
```

Preserve the Laravel 13 foundation and migrate application functionality into it.

Do not blindly copy the Laravel 10 `package.json`.

---

# 21. Git Commit Strategy

Commit completed migration units independently.

Examples:

```text
feat(upgrade): establish Laravel 13 foundation

chore(upgrade): install gogilo breeze

feat(upgrade): migrate authentication customizations

feat(upgrade): migrate user management

feat(upgrade): migrate student management

feat(upgrade): migrate fees module

feat(upgrade): migrate dashboard frontend

test(upgrade): migrate student feature tests
```

Avoid one giant migration commit.

Small commits make it easier to:

* Review
* Debug
* Revert
* Bisect
* Compare old and new implementations

---

# 22. Migration Tracking

Maintain:

```text
.ai/project/upgrade-map.md
```

Track every feature:

```text
Feature | Backend | Frontend | Tests | Verified
```

Example:

```text
Users        | done | done | done | yes
Students     | done | partial | pending | no
Fees         | partial | pending | pending | no
Reports      | pending | pending | pending | no
```

Never mark a feature complete merely because its files have been copied.

A feature is complete only after verification.

---

# 23. AI Agent Operating Rules

The agent must:

1. Inspect before modifying.
2. Plan before copying.
3. Preserve the Laravel 13 foundation.
4. Preserve business behavior.
5. Work feature-by-feature.
6. Keep changes small.
7. Test continuously.
8. Review diffs.
9. Document uncertainty.
10. Never claim completion without verification.

The agent should prefer:

```text
small verified migration
```

over:

```text
large speculative migration
```

---

# 24. Destructive Operation Policy

The initial cleanup of `bs-upgrade` is destructive.

Before performing it, the agent MUST verify:

```text
[ ] Correct directory
[ ] Correct Git repository
[ ] Correct branch
[ ] Existing .git directory
[ ] Git history visible
[ ] Fresh Laravel 13 project exists elsewhere
[ ] Temporary Laravel project is valid
[ ] User has not left important uncommitted work in bs-upgrade
```

If any item is uncertain, STOP.

Never execute destructive cleanup based only on an assumed path.

---

# 25. Completion Criteria

The migration is complete only when:

```text
[ ] Fresh Laravel 13 foundation established
[ ] Original Git history preserved
[ ] upgrade branch contains Laravel 13 implementation
[ ] gogilo/breeze installed and functional
[ ] Required Composer dependencies migrated
[ ] Required NPM dependencies migrated
[ ] Database functionality migrated
[ ] Backend functionality migrated
[ ] Frontend functionality migrated
[ ] Authentication migrated
[ ] Authorization migrated
[ ] All important application features migrated
[ ] Relevant tests migrated
[ ] Tests passing
[ ] Frontend builds
[ ] TypeScript checks passing
[ ] ESLint checks passing where configured
[ ] Routes verified
[ ] Database migrations verified
[ ] No critical Laravel 10 compatibility issues remain
[ ] Final migration audit completed
```

---

# 26. Final Verification

Run the appropriate project checks:

```bash
php artisan --version
php artisan about
composer validate
composer audit
php artisan test
npm run build
```

Also run:

```bash
php artisan route:list
php artisan migrate:status
```

and any project-specific:

```text
TypeScript checks
ESLint
Prettier
Browser tests
Integration tests
```

Review:

```bash
git status
git diff
git log --oneline
```

Confirm that the upgrade branch contains the Laravel 13 application while the original Git history remains intact.

---

# 27. Final Principle

This migration should be understood as:

```text
                    Laravel 10
                  /home/ogilo/Projects/bs
                           │
                           │
                    source of behavior
                           │
                           ▼
              ┌─────────────────────────┐
              │ Fresh Laravel 13        │
              │ Foundation              │
              └────────────┬────────────┘
                           │
                           ▼
                 /home/ogilo/Projects/
                           │
                 ┌─────────┴─────────┐
                 │                   │
                bs             bs-upgrade
             Laravel 10        Laravel 13
             unchanged          upgrade
                                   │
                                   ▼
                         Gradual feature migration
                                   │
                                   ▼
                         Verified Laravel 13
                           application
```

The legacy application is preserved.

The Git history is preserved.

The Laravel 13 foundation remains clean.

Existing functionality is migrated deliberately.

No feature is considered complete until it has been implemented, tested, and verified.
