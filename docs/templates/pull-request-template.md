# Pull Request Description Template

> Copy this template into `docs/pull-requests-description/PR_NNN-<slug>.md` when
> preparing a pull request description. Fill in the sections below.
>
> The PR description is authored for the change under review and lives in the
> repo for permanent reference — never delete a completed PR description.

## Naming

- **File**: `docs/pull-requests-description/PR_NNN-<short-slug>.md`
- **Sequence**: NNN is the next available zero-padded number (001, 002, ...),
  sharing the sequence with decisions (`docs/decisions/`) and plans
  (`docs/plans/`).
- **Slug**: short, hyphenated, lowercase identifier, e.g. `laravel13-upgrade`.

## Format

```markdown
# PR: <Title>

> **Branch:** `<source>` → `<target>`
> **Scope:** <commits> commits · <files> files · +<added> / −<deleted> lines
> **Files:** `docs/pull-requests-description/PR_NNN-<slug>.md`

---

## Title

`<conventional-commit-prefix>: <concise summary of the change>`

## Summary

A short paragraph describing the overall purpose of the pull request and the
value it delivers.

## What's Changing

### 1. <Area 1>

Describe the change, files/technologies involved, and the benefit.

### 2. <Area 2>

### 3. <Area 3>

(Group logically: framework upgrades, features, bug fixes, tooling, etc.)

## Files Changed (Highlights)

| Area | Files |
|---|---|
| Backend | ... |
| Frontend | ... |
| Migration/Data | ... |
| Docs/Workflow | ... |

## Breaking Changes

List anything that requires manual action or could affect existing behavior
(e.g. new runtime requirements, migrations to run, config changes).

## Testing

- [ ] `php artisan test` -- backend tests pass
- [ ] `npm run build` (`vue-tsc && vite build`) -- type check + build pass
- [ ] `npx vue-tsc --noEmit` -- clean type check
- [ ] <manual verification steps specific to the change>

---

## Source

- **Branches**: `<source>` → `<target>`
- **Commits**: `git log --oneline <target>..<source>` (list the highlights)
- **Related Decision/Plan**: (path to ADR/plan if the change was designed, else N/A)

## Status

- **Draft** | **Ready for Review** | **Merged**
```
