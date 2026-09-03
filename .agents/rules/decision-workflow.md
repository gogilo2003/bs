# Decision Workflow Rules

These rules govern creation of decision records (ADRs) from approved
discussions.

## Lifecycle

1. **Approve** -- discussion converges and is approved.
2. **Create ADR** -- architect writes `docs/decisions/NNN-<slug>.md`.
3. **Create Plan** -- architect writes `docs/plans/pending/NNN-<slug>.md`.
4. **Implement** -- implementer agent executes the plan, then moves it to
   `docs/plans/implemented/`.

## Rules

- Every ADR must reference its source discussion in
  `docs/discussions/DISC_NNN-<slug>.md`.
- Every ADR must reference its implementation plan in
  `docs/plans/pending/NNN-<slug>.md`.
- Every ADR must have a unique zero-padded sequence number (`NNN`).
- The `NNN` sequence is shared between decisions, plans, and PR descriptions
  (`docs/pull-requests-description/PR_NNN-<slug>.md`) so designed changes stay
  linked across all artifacts.
- Every ADR must use the format in `docs/templates/decision-template.md`:
  Title, Date, Status, Source Discussion, Implementation Plan, Context,
  Decision, Alternatives Considered, Consequences, Verification.
- Status values: `Accepted`, `Proposed`, `Deprecated`, `Superseded`.
- Once a decision is implemented, plan moves to `implemented/` but the ADR
  **stays** in `docs/decisions/` as the permanent record.

## Sequence Numbering

To find the next number, list `docs/decisions/`, take the highest `NNN-`
prefix, and add 1. Keep padding consistent (001, 002, ... 100, ...).
