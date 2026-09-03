# Decisions

Architecture and design decision records (ADRs). Created from approved
discussions by the discussion-architect agent.

## Workflow

1. Decision is created from an approved discussion in `docs/discussions/`.
2. An implementation plan is created in `docs/plans/pending/`.
3. The implementer agent picks up the plan and moves it to
   `docs/plans/implemented/` upon completion.

## File Naming

`NNN-<short-slug>.md` where NNN is a zero-padded sequence number.

Each decision follows a standard ADR format: title, status, context, decision,
consequences. See the template in `docs/templates/decision-template.md`.
