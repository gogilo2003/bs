# Pull Request Workflow Rules

These rules govern how pull request descriptions are authored, sequenced, and
retained. All agents should respect these paths and formats when preparing or
reviewing PRs.

## Canonical Paths

| Item | Path |
|---|---|
| PR descriptions | `docs/pull-requests-description/PR_NNN-<slug>.md` |
| PR template | `docs/templates/pull-request-template.md` |

## Lifecycle

1. **Author** -- a PR description is created for a branch before it is merged,
   using the template in `docs/templates/pull-request-template.md`.
2. **Review** -- the description documents the `source` → `target` branches, the
   scope of the diff, breaking changes, and how it will be verified.
3. **Merge** -- once the PR merges, the description's **Status** is updated to
   `Merged`.

## Rules

- The description must be grounded in the actual diff
  (`git diff <target>...<source>` and `git log <target>..<source>`); never
  invent changes that are not in the branch.
- One PR description per PR, one file per description.
- The filename uses `PR_NNN-<slug>.md` where `NNN` shares the same zero-padded
  sequence as decisions (`docs/decisions/`) and plans (`docs/plans/`), so
  designed changes stay linked across all artifacts.
- If the change was produced from a plan (see `plan-workflow.md`), the PR
  description should reference the parent decision and plan.
- Completed PR descriptions are permanent records — never delete them.
- A PR description is only moved to `Merged` status after the PR actually
  merges; never on intent alone.

## Quick Reference (gathering the diff)

- Commits on the branch:
  `git log --oneline <target>..<source>`
- Files/scope summary:
  `git diff --stat <target>...<source>`
- Detailed diff:
  `git diff <target>...<source>`
