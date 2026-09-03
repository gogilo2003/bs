# Implementation Plan Workflow Rules

These rules govern the lifecycle of implementation plans, from creation to
completion.

## Lifecycle

1. **Create** -- architect writes plan to `docs/plans/pending/NNN-<slug>.md`.
2. **Implement** -- implementer agent executes the plan steps.
3. **Verify** -- implementer meets acceptance criteria and verification steps.
4. **Complete** -- implementer moves the plan file to
   `docs/plans/implemented/NNN-<slug>.md` and sets status to `Completed`.

## Rules

- A plan must reference its parent decision record in `docs/decisions/`.
- A plan file must use the format in `docs/templates/plan-template.md`:
  Objective, Scope (In/Out), Steps, Acceptance Criteria, Verification, Notes.
- Plans live in `pending/` until fully implemented AND verified. Never move a
  plan to `implemented/` on intent alone.
- A plan must not expand its own scope. If an implementer finds the plan
  inconsistent with the codebase or parent decision, they stop and report back
  rather than improvise.
- Include concrete acceptance criteria that are objectively checkable
  (tests, type checks, builds, manual steps).
- On completion, keep the plan file intact but flip status to `Completed` and
  note verification results.

## Verification Commands (this project)

- Backend tests: `php artisan test`
- Frontend build + types: `npm run build` (runs `vue-tsc && vite build`)
- Type check only: `npx vue-tsc --noEmit`
