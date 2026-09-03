# Discussions

Active and archived design discussions. Each discussion is initiated by the
discussion-architect agent and iterated until a decision is reached.

## Workflow

1. A discussion is created as `DISC_001-<slug>.md` via the
   discussion-architect agent.
2. The discussion is iterated with comments, alternatives, and trade-offs.
3. Once approved, the architect creates a decision record in `docs/decisions/`
   and an implementation plan in `docs/plans/pending/`.

## File Naming

`DISC_NNN-<short-slug>.md` where NNN is a zero-padded sequence number
(001, 002, ...). The prefix `DISC_` keeps discussions distinct from decision
and plan numbering.

## Status

- **Open** -- actively being discussed
- **Decided** -- decision record created, moved to archive
- **Rejected** -- not proceeding, reason documented
