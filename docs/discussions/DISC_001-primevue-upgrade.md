# Discussion: PrimeVue Upgrade Strategy

- **Date**: 2026-09-03
- **Status**: Open
- **Author**: discussion-architect agent
- **Related Decision**: (populated once decided)

## Context

The project currently uses PrimeVue **4.5.5** with `@primevue/themes` 4.5.4. PrimeVue **5.0.1** was released on July 15, 2026, as the first release under the PrimeUI umbrella. The `@primevue/themes` package is now deprecated in favor of `@primeuix/themes`.

The project uses only **6 PrimeVue components**: Button, InputNumber, DatePicker, Select, Toast, and Chart. PrimeVue is configured via `resources/js/app.ts` with the Lara theme preset and ToastService. No auto-import resolver is configured — all components are imported manually.

The project is simultaneously undergoing a Laravel 10 → Laravel 13 fresh-foundation migration, with the upgrade workspace at `/home/ogilo/Projects/bs-upgrade`.

## Problem Statement

We need to determine the optimal timing and approach for upgrading PrimeVue, balancing:
- Staying current with a actively maintained package (`@primevue/themes` is deprecated)
- Minimizing risk during the concurrent Laravel migration
- Planning for deprecated components (Chart, Button icon prop) that will be removed in v6

## Current Usage

| Component | Files | Notes |
|---|---|---|
| Button | Readings.vue, Report.vue | Uses `icon` prop with primeicons classes |
| InputNumber | Readings.vue | — |
| DatePicker | Readings.vue | — |
| Select | Readings.vue | — |
| Toast + useToast | Readings.vue | ToastService registered globally |
| Chart | ReportChart.vue, LineChart.vue | Uses PrimeVue CSS custom properties (`--p-*`) |

**Configuration:**
- Theme: Lara preset from `@primevue/themes/lara`
- primeicons: loaded from GitHub (not npm), imported in `Icon.vue`
- No auto-import resolver

## Options

### Option A: Upgrade to PrimeVue 5.x Now

Upgrade directly from 4.5.5 to 5.0.1 in the current codebase.

**Steps:**
1. Replace `primevue` ^4.0.3 with ^5.0.1
2. Replace `@primevue/themes` with `@primeuix/themes`
3. Use the 14px compat preset to avoid layout shifts
4. Update `app.ts` theme configuration
5. Address deprecated APIs proactively (Button icon prop → slots)
6. Plan for Chart deprecation (evaluate alternatives)

**Pros:**
- No breaking changes to public component APIs — should upgrade cleanly
- Latest bug fixes, security patches, and improvements
- Deprecation warnings guide future migration path
- Removes dependency on deprecated `@primevue/themes` package

**Cons:**
- 16px base font size may cause subtle layout shifts (mitigated by compat preset)
- Chart component is deprecated — requires long-term plan
- Adds scope during Laravel migration period
- Button icon prop deprecated — needs slot-based migration

### Option B: Upgrade to Latest PrimeVue 4.x

Stay on the v4 line, upgrading only within 4.x for bug fixes.

**Pros:**
- Minimal risk — no API changes or layout shifts
- No deprecated component concerns
- Lowest effort

**Cons:**
- `@primevue/themes` remains deprecated
- Missing v5 improvements and bug fixes
- Will eventually need v5 upgrade anyway
- Technical debt accumulates

### Option C: Defer Until Laravel Migration Completes

Focus on the Laravel 10 → 13 migration first, then upgrade PrimeVue in the fresh `bs-upgrade` workspace.

**Pros:**
- Clean slate — can adopt v5 patterns from the start in new workspace
- No risk to legacy codebase
- One fewer variable during Laravel migration

**Cons:**
- Deprecated packages remain in legacy codebase for extended period
- Chart deprecation still needs a plan
- May be harder to upgrade later if PrimeVue 6 removes deprecated APIs before we migrate

## Trade-offs

| Factor | Option A (Upgrade now) | Option B (Stay on v4) | Option C (Defer) |
|---|---|---|---|
| Risk | Low (no breaking changes) | Very Low | Low |
| Technical debt | Low | Medium (deprecated pkg) | Medium-High |
| Effort | Medium | Low | Low now, Medium later |
| Layout stability | Needs compat preset | Stable | Stable |
| Chart future | Requires planning | Requires planning | Can plan in fresh workspace |
| Laravel migration impact | Minimal | None | None |

## Recommendation

(Leave blank until iterated.) Initial leaning and rationale.

## Iteration Log

### 2026-09-03 -- Initial proposal

- Created discussion with three options for PrimeVue upgrade strategy.
- Key finding: PrimeVue v5 has **no breaking changes** to public component APIs, making Option A lower-risk than expected.
- Chart component deprecation is a concern across all options — needs a separate decision regardless of upgrade timing.

## Approval

- **Approved By**: *(name, once approved)*
- **Decision Record**: *(path to ADR once created)*
- **Implementation Plan**: *(path to plan once created)*
