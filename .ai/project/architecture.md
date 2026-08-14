---
name: project-context
description: Core project context, conventions, and architectural constraints for AI-assisted coding on this repository.
---

# Project Context

## Stack

- Laravel 10 (target: Laravel 13 via fresh-foundation migration)
- PHP 8.3+
- Vue 3 with Composition API
- TypeScript (`<script setup lang="ts">`)
- Inertia.js
- MySQL/MariaDB
- gogilo/breeze authentication
- PrimeVue UI components
- Tailwind CSS
- Vite

## Architecture

### Backend

- Controllers are thin
- Complex operations belong in Actions/Services
- Form Requests handle substantial validation
- Policies handle authorization
- Explicit dependency injection preferred

### Frontend

- Vue 3 Composition API
- `<script setup lang="ts">` for all new components
- Typed Inertia page props
- Composables for reusable client-side behavior
- Shared types in `@resources/js/Types/`

### Routing

- Use the project's established route generation strategy

## File Layout

```text
app/
├── Actions/          # Single-responsibility application operations
├── Http/
│   ├── Controllers/  # Thin controllers
│   ├── Requests/     # Form Requests
│   └── Middleware/
├── Models/
├── Services/         # Cohesive service classes
├── Policies/
database/
resources/
├── js/
│   ├── Components/
│   ├── Composables/
│   ├── Layouts/
│   ├── Pages/
│   ├── Types/
│   └── Utils/
routes/
tests/
```

## Conventions

- Prefer explicit dependencies
- Avoid unnecessary abstractions
- Preserve business behavior
- Prefer small focused classes
- Prefer typed frontend contracts
- Do not rewrite working code merely to modernize syntax
- Do not introduce Options API into new code

## Migration Context

This project is undergoing a Laravel 10 → Laravel 13 fresh-foundation migration.

- `/home/ogilo/Projects/bs` — legacy Laravel 10 application (read-only during migration)
- `/home/ogilo/Projects/bs-upgrade` — Laravel 13 migration workspace

See `@.ai/skills/laravel-10-to-13-upgrade.md` for migration workflow.

## Testing

- Preserve existing tests during refactoring
- Add characterization tests for unclear legacy behavior
- Run relevant test suites before and after changes
- Do not remove tests simply because implementation changed
