# Pull Request Descriptions

Ready-for-review and completed pull request descriptions. Each PR description
is authored before the branch is merged and kept here as a permanent reference.

## Workflow

1. A PR description is created as `PR_NNN-<slug>.md` using the template in
   `docs/templates/pull-request-template.md`.
2. The `NNN` sequence is shared with decisions and plans
   (`docs/decisions/`, `docs/plans/`) so a designed change stays linked across
   all artifacts.
3. The description documents the source→target branches and the diff it
   introduces.
4. Once merged, the description's **Status** is updated to `Merged`. Completed
   descriptions are never deleted.

## File Naming

`PR_NNN-<short-slug>.md` where NNN is a zero-padded sequence number
(001, 002, ...).

## Status

- **Draft** -- in progress, not yet ready for review
- **Ready for Review** -- description complete, open PR awaiting merge
- **Merged** -- PR merged; record retained for reference
