# Project AI Instructions

## Project

This is a Laravel 13 migration project.

The legacy Laravel 10 application is located at:

/home/ogilo/Projects/bs

The Laravel 13 migration application is:

/home/ogilo/Projects/bs-upgrade

The legacy application must be treated as read-only unless explicitly instructed otherwise.

## Migration Strategy

This project uses a fresh Laravel 13 foundation.

The existing application functionality is being migrated gradually from the legacy application into this project.

Do not blindly copy the Laravel 10 application into this project.

Understand functionality first, then implement it using Laravel 13 conventions.

## AI Skills

Detailed project skills are stored in:

.ai/skills/

Before performing a substantial task, inspect the relevant skill.

Available skills include:

- .ai/skills/laravel-10-to13-upgrade.md
- .ai/skills/application-modernization.md
- .ai/skills/thin-controller-refactoring.md
- .ai/skills/vue-typescript-modernization.md
- .ai/skills/legacy-code-refactoring.md
- .ai/skills/modernization-audit.md

## Important

Do not modernize code merely because it is old.

During migration prioritize:

1. Functional parity
2. Correctness
3. Tests
4. Laravel 13 compatibility
5. Modernization

When modernizing:

- Use thin controllers.
- Use Form Requests for substantial validation.
- Use Actions/Services for meaningful application operations.
- Avoid unnecessary abstractions.
- Use Vue 3 Composition API.
- Use `<script setup lang="ts">`.
- Prefer TypeScript for new frontend code.
- Type Inertia page props.
- Avoid unnecessary `any`.

## Legacy Application

The legacy application is the source of truth for existing business behavior.

The Laravel 13 project is the source of truth for framework infrastructure.

When the two differ, investigate the difference rather than blindly copying either implementation.

## Safety

Before destructive operations:

- Verify the current directory.
- Verify the Git repository.
- Verify the current branch.
- Verify Git status.
- Never delete `.git`.
- Never modify the legacy application unless explicitly instructed.