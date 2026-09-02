# Shared Development Rules

These are the baseline coding and collaboration rules that every agent
(backend, frontend, discussion-architect, implementer) must follow when working
in this repository.

## Convention Source of Truth

- `.agents/project/architecture.md` -- stack, architecture, file layout,
  conventions. Read it before substantial work.
- `.agents/skills/` -- specialized skills for Laravel/Vue modernization and
  refactoring. Use when a task matches.

## General Rules

- Do not rewrite working code merely to modernize its syntax.
- Preserve business behavior. Do not introduce regressions.
- Prefer small, focused files over large god-classes/pages.
- Prefer explicit dependency injection.
- Add comments only when the intent is not self-evident; do not over-comment.

## Backend Rules

- Thin controllers; extract logic to Actions/Services.
- Substantial validation belongs in Form Requests.
- Authorization belongs in Policies.
- PHP 8.3+ features are available.
- Verify with `php artisan test`.

## Frontend Rules

- `<script setup lang="ts">` and Composition API for all new/modified
  components. No Options API in new code.
- Type everything; avoid `any`.
- Reuse existing components from `resources/js/Components/`.
- Tailwind v4 is CSS-first (no `tailwind.config.js`).
- Verify with `npm run build` / `npx vue-tsc --noEmit`.

## Discussion / Decision / Plan Rules

- Discussions, decisions, and plans live in `docs/` with the paths defined in
  `discussion-workflow.md`, `decision-workflow.md`, and `plan-workflow.md`.
- The discussion-architect agent initiates, iterates, and approves.
- The implementer agent implements and completes plans.
- Never delete resolved discussions or decision records.

## Verification

- Run the project's tests, type checks, and builds before considering work done.
- See `plan-workflow.md` for the concrete commands.
