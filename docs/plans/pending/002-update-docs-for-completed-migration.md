# Plan: Update Docs for Completed Migration & Refine PrimeVue Discussion

- **Date**: 2026-09-03
- **Status**: Pending
- **Author**: implementer agent
- **Related Decision**: N/A (documentation updates)

## Objective

Update two stale documents now that the Laravel 10 → 13 migration is complete:
1. `architecture.md` — remove legacy migration references
2. `DISC_001-primevue-upgrade.md` — remove Option C (defer until migration), refine remaining options

## Scope

**In scope:**
- Edit `.agents/project/architecture.md`
- Edit `docs/discussions/DISC_001-primevue-upgrade.md`

**Out of scope:**
- No code changes
- No new discussions or decisions

---

## Step 1: Update `architecture.md`

**File:** `.agents/project/architecture.md`

### Changes

**Line 10** — Stack section:
```
- Laravel 10 (target: Laravel 13 via fresh-foundation migration)
+ Laravel 13
```

**Lines 78-85** — Replace entire Migration Context section:
```markdown
## Migration Context

This project is undergoing a Laravel 10 → Laravel 13 fresh-foundation migration.

- `/home/ogilo/Projects/bs` — legacy Laravel 10 application (read-only during migration)
- `/home/ogilo/Projects/bs-upgrade` — Laravel 13 migration workspace

See `@.ai/skills/laravel-10-to-13-upgrade.md` for migration workflow.
```

Replace with:
```markdown
## Migration History

The Laravel 10 → Laravel 13 fresh-foundation migration was completed via PR #1
and merged into `main`. See `docs/pull-requests-description/PR_001-laravel13-upgrade.md`
for details. The migration skill is preserved at
`.agents/skills/laravel-10-to-13-upgrade.md` for reference.
```

---

## Step 2: Update `DISC_001-primevue-upgrade.md`

**File:** `docs/discussions/DISC_001-primevue-upgrade.md`

### Changes

**Line 14** — Remove stale migration reference:
```
- The project is simultaneously undergoing a Laravel 10 → Laravel 13 fresh-foundation migration, with the upgrade workspace at `/home/ogilo/Projects/bs-upgrade`.
+ The Laravel 10 → 13 migration is complete (PR #1), so the PrimeVue upgrade can proceed without competing with migration work.
```

**Lines 18-21** — Simplify Problem Statement (remove migration concern):
```
We need to determine the optimal timing and approach for upgrading PrimeVue, balancing:
- Staying current with a actively maintained package (`@primevue/themes` is deprecated)
- Minimizing risk during the concurrent Laravel migration
- Planning for deprecated components (Chart, Button icon prop) that will be removed in v6
```

Replace with:
```
We need to determine the approach for upgrading PrimeVue:
- `@primevue/themes` is deprecated in favor of `@primeuix/themes`
- Chart component is deprecated in v5 (removed in v6, replaced by commercial PrimeUI PRO Charts)
- Button `icon` prop is deprecated in v5 (removed in v6, use slots instead)
```

**Lines 62-63** — Remove migration-related Cons from Option A:
```
- Chart component is deprecated — requires long-term plan
- Adds scope during Laravel migration period
- Button icon prop deprecated — needs slot-based migration
```

Replace with:
```
- Chart component is deprecated — requires long-term plan
- Button icon prop deprecated — needs slot-based migration
```

**Lines 80-92** — Remove entire Option C section.

**Lines 94-103** — Update Trade-offs table (remove Option C column):
```markdown
| Factor | Option A (Upgrade now) | Option B (Stay on v4) |
|---|---|---|
| Risk | Low (no breaking changes) | Very Low |
| Technical debt | Low | Medium (deprecated pkg) |
| Effort | Medium | Low |
| Layout stability | Needs compat preset | Stable |
| Chart future | Requires planning | Requires planning |
```

**Lines 111-115** — Update Iteration Log with new entry:
```markdown
### 2026-09-03 -- Iteration 2

- Removed Option C (defer until migration) — the Laravel 13 migration is now complete.
- Simplified problem statement to focus on PrimeVue-specific concerns only.
- Updated trade-offs table to reflect two-option comparison.
```

---

## Acceptance Criteria

- [ ] `architecture.md` no longer references Laravel 10 or the upgrade workspace as active
- [ ] `architecture.md` stack section says "Laravel 13"
- [ ] `DISC_001` has no Option C
- [ ] `DISC_001` trade-offs table has two columns (Option A, Option B)
- [ ] `DISC_001` iteration log documents the changes
- [ ] No other files modified

## Verification

- Review both files for stale references to "Laravel 10", "legacy", "bs-upgrade"
- Confirm no broken internal links
