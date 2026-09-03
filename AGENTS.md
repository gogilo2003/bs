# Project Agents

This project uses opencode with specialized agents and a documented
discussion → decision → plan → implement → PR workflow.

## Agents

| Agent | Purpose |
|---|---|
| **backend** | Laravel/PHP: controllers, models, migrations, services, form requests, policies, API, tests. |
| **frontend** | Vue 3 / TypeScript / Tailwind: components, pages, composables, state, styling. |
| **discussion-architect** | Initiates and iterates design discussions; on approval produces a decision record (ADR) and an implementation plan. |
| **implementer** | Picks up pending plans, implements them, verifies, then moves them to `implemented`. |

Agent definitions live in `.agents/agents/<name>.md`. (Symlinked to
`.opencode/agents/` so opencode discovers them at its expected location.)

## Discussion → Decision → Plan → Implement → PR Workflow

```
discussion          decision (ADR)        plan                  implement              pull request
docs/discussions/   docs/decisions/       docs/plans/           docs/plans/            docs/pull-requests-description/
DISC_NNN-<slug>.md  NNN-<slug>.md         pending/NNN-<slug>.md implemented/NNN-<slug>.md  PR_NNN-<slug>.md
```

1. **Discuss** -- the discussion-architect creates
   `docs/discussions/DISC_NNN-<slug>.md` (from the template) and iterates on
   options and trade-offs.
2. **Decide** -- once approved, the architect creates
   `docs/decisions/NNN-<slug>.md` (an ADR) and
   `docs/plans/pending/NNN-<slug>.md`.
3. **Implement** -- the implementer agent executes the plan, verifies it, then
   moves the plan to `docs/plans/implemented/`.
4. **Pull Request** -- before a branch is merged, a PR description is authored
   from `docs/templates/pull-request-template.md` into
   `docs/pull-requests-description/PR_NNN-<slug>.md` documenting the
   source → target diff, breaking changes, and verification.

### Paths & Status

| Item | Path | Status |
|---|---|---|
| Discussion | `docs/discussions/DISC_NNN-<slug>.md` | Open / Decided / Rejected |
| Decision (ADR) | `docs/decisions/NNN-<slug>.md` | Accepted / Proposed / Deprecated / Superseded |
| Plan (pending) | `docs/plans/pending/NNN-<slug>.md` | Pending / In Progress |
| Plan (implemented) | `docs/plans/implemented/NNN-<slug>.md` | Completed |
| PR description | `docs/pull-requests-description/PR_NNN-<slug>.md` | Draft / Ready for Review / Merged |
| Templates | `docs/templates/*-template.md` | — |

`NNN` is a zero-padded sequence number. Discussions use the `DISC_` prefix
(`DISC_NNN-`, numbered independently); decisions, plans, and PR descriptions
share a single `NNN` sequence.

## Rule Files

The workflow and coding rules live in `.agents/rules/` and are loaded as
global instructions (also reachable via the `.opencode/rules/` symlink):

- `shared-dev.md` -- baseline coding & collaboration rules for all agents.
- `discussion-workflow.md` -- how discussions are initiated and iterated.
- `decision-workflow.md` -- how ADRs are created and sequenced.
- `plan-workflow.md` -- how plans are implemented and completed.
- `pull-request-workflow.md` -- how PR descriptions are authored and retained.

## Convention Source of Truth

- `.agents/project/architecture.md` -- stack, architecture, file layout,
  conventions.
- `.agents/skills/` -- specialized Laravel/Vue modernization and refactoring
  skills.

## Verification

| Area | Command |
|---|---|
| Backend tests | `php artisan test` |
| Frontend build + types | `npm run build` (runs `vue-tsc && vite build`) |
| Frontend type check | `npx vue-tsc --noEmit` |

**Note:** after changing opencode config or agent files, quit and restart
opencode for the changes to take effect.
