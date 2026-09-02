# Discussion Workflow Rules

These rules govern how the discussion-architect agent initiates and iterates on
discussions. All agents should respect these paths and formats.

## Canonical Paths

| Item | Path |
|---|---|
| Discussions | `docs/discussions/DISC_NNN-<slug>.md` |
| Decision records | `docs/decisions/NNN-<slug>.md` |
| Pending plans | `docs/plans/pending/NNN-<slug>.md` |
| Implemented plans | `docs/plans/implemented/NNN-<slug>.md` |
| Templates | `docs/templates/*-template.md` |

## Discussion Lifecycle

1. **Initiate** -- architect creates the discussion file from the template.
2. **Iterate** -- architect logs rounds in the Iteration Log; options, trade-offs,
   and recommendation are refined.
3. **Decide** -- on approval, architect creates the decision record and the
   pending plan, then marks the discussion `Decided`.
4. **Archive** -- resolved discussions remain in `docs/discussions/` (do not
   delete them). Their status field records the outcome.

## Rules

- One discussion per topic per file.
- The discussion filename uses `DISC_NNN-<slug>.md` where NNN is the next
  available zero-padded sequence number (001, 002, ...). The `DISC_` prefix
  distinguishes discussions from decisions and plans.
- Always record iteration history — never silently rewrite an earlier round.
- A decision (ADR) is only created after the discussion converges and is
  approved. Do not create ADRs for unresolved discussions.
- Decision number `NNN` must be unique and shared between the ADR and its plan.
- The architect never implements code; implementation belongs to the
  implementer agent.

## Formatting

Follow the templates in `docs/templates/` exactly. If a template is missing,
recreate it from the existing `docs/templates/*` files before writing content.
