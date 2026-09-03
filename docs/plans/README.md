# Implementation Plans

Plans created from approved decisions. Managed by the implementer agent.

## Directories

- `pending/` -- Plans awaiting implementation
- `implemented/` -- Completed plans (moved here by the implementer agent)

## Workflow

1. The discussion-architect creates a plan in `pending/` after a decision is
   approved.
2. The implementer agent picks up a plan, implements it, and moves it to
   `implemented/`.
3. Plans reference their parent decision record in `docs/decisions/`.

## File Naming

`NNN-<short-slug>.md` where NNN matches the parent decision number.
