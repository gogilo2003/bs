---
name: legacy-code-refactoring
description: Disciplined approach for improving legacy Laravel, PHP, Vue, and TypeScript code without changing established application behavior.
---

# Legacy Code Refactoring Skill

## Purpose

Provide a disciplined approach for improving legacy Laravel, PHP, Vue, and TypeScript code without changing established application behavior unnecessarily.

This skill is particularly useful after Laravel 10 → Laravel 13 functional migration.

---

# Core Principle

Legacy code is not automatically bad code.

Refactor when there is a meaningful improvement in:

* Maintainability
* Correctness
* Testability
* Reusability
* Readability
* Type safety
* Performance
* Security

Do not refactor solely because the code looks old.

---

# Identify Code Smells

Look for:

## Backend

* Fat controllers
* Large methods
* God classes
* Duplicated queries
* Duplicated business logic
* Inline validation
* Inline authorization
* Repeated transaction logic
* Magic strings
* Magic numbers
* Missing types
* Weak abstractions
* Hidden dependencies
* Static service lookups
* Excessive model responsibilities
* Unclear service boundaries

## Frontend

* Large Vue pages
* Options API in actively maintained code
* Untyped props
* `any`
* Duplicated interfaces
* Duplicated forms
* Duplicated API logic
* Repeated modal logic
* Repeated pagination logic
* Excessive watchers
* Large computed expressions
* Business logic inside templates
* Repeated UI patterns

---

# Refactoring Priority

Prioritize:

```text
Critical correctness/security issue
↓
High-impact maintainability issue
↓
Repeated business logic
↓
High-complexity code
↓
Type safety
↓
Duplication
↓
Minor readability improvements
```

Do not spend significant time polishing trivial code while important architecture remains problematic.

---

# Refactoring Safety

Before refactoring:

1. Find existing tests.
2. Understand the current behavior.
3. Identify dependencies.
4. Identify callers.
5. Identify routes.
6. Identify frontend consumers.
7. Identify side effects.

If tests are missing and behavior is important, consider adding characterization tests before refactoring.

---

# Characterization Tests

When legacy behavior is unclear, tests may be written to capture current behavior before refactoring.

The purpose is to document what the application currently does.

Do not assume that unexpected behavior is a bug during a refactor.

Record suspected bugs separately.

---

# Refactoring vs Bug Fix

Separate:

```text
Refactoring
```

from:

```text
Bug fix
```

If a refactor exposes a bug:

1. Preserve the intended migration scope.
2. Record the bug.
3. Fix it separately where practical.

Do not silently change business behavior.

---

# Refactoring Controllers

Use:

```text
@.ai/skills/thin-controller-refactoring.md
```

Do not move business logic into arbitrary classes merely to shorten the controller.

---

# Refactoring Vue

Use:

```text
@.ai/skills/vue-typescript-modernization.md
```

Prefer Vue 3 Composition API and TypeScript for actively modernized components.

---

# Refactoring Services

A service should have a coherent responsibility.

Bad:

```text
ApplicationService
CommonService
GeneralService
UtilityService
```

Better:

```text
RegisterStudent
GenerateInvoice
ProcessPayment
CalculateFeeBalance
```

Do not create services solely because a method is long.

---

# Refactoring Repositories

Evaluate whether the repository provides meaningful abstraction.

Keep if it provides:

* Complex query logic
* Data source abstraction
* Reusable persistence operations
* Domain-specific queries

Consider simplifying if it only contains:

```php
return Student::find($id);
```

Do not remove an established repository architecture across the application as part of an unrelated feature migration.

---

# Extracting Shared Logic

Before extracting shared logic:

1. Identify duplicated behavior.
2. Confirm the behavior is actually equivalent.
3. Identify differences.
4. Determine appropriate abstraction.
5. Add tests.
6. Extract.
7. Verify all callers.

Do not abstract code merely because two pieces look similar.

---

# Naming

Names should describe intent.

Prefer:

```text
calculateOutstandingBalance()
generateInvoice()
registerStudent()
approveApplication()
```

over:

```text
process()
handle()
doSomething()
execute()
```

unless the generic name is meaningful in context.

---

# Type Safety

During PHP modernization:

Prefer explicit:

```php
public function calculateTotal(int $amount): int
```

over untyped methods.

During Vue modernization:

Prefer:

```ts
interface Student {
    id: number
    name: string
}
```

over:

```ts
student: any
```

Do not introduce inaccurate types merely to remove warnings.

---

# Database Refactoring

Be extremely conservative with database changes.

Do not modify:

* Column meaning
* Constraints
* Indexes
* Relationships
* Data types

without understanding the production data implications.

Schema refactoring is separate from code refactoring.

---

# Performance Refactoring

Do not optimize based on assumptions.

Look for obvious issues such as:

* N+1 queries
* Unnecessary repeated queries
* Loading huge datasets
* Unnecessary frontend reactivity
* Excessive API calls

When practical, verify the performance improvement.

Do not introduce complex caching or asynchronous architecture without a demonstrated requirement.

---

# Refactoring Workflow

```text
Identify smell
↓
Understand behavior
↓
Check tests
↓
Plan smallest safe change
↓
Refactor
↓
Run tests
↓
Run static checks
↓
Review diff
↓
Commit
```

---

# Commit Discipline

Prefer focused commits:

```text
refactor(students): extract student registration action

refactor(fees): simplify fee balance calculation

refactor(students): type student Vue components

refactor(attendance): extract attendance query
```

Avoid mixing:

```text
refactoring
+
feature development
+
dependency upgrades
+
UI redesign
```

in one commit.

---

# Do Not Overengineer

Avoid introducing patterns merely because they are considered "clean architecture."

Do not automatically introduce:

* Repository interfaces everywhere
* DTOs everywhere
* Actions everywhere
* Value objects everywhere
* Event-driven architecture everywhere
* Generic base services
* Generic base repositories
* Generic CRUD abstractions

Use architecture to solve actual problems.

---

# Definition of Done

A legacy refactoring is complete when:

* Existing behavior is preserved.
* The code is objectively easier to maintain.
* The responsibility boundaries are clearer.
* Tests pass.
* Types are improved where appropriate.
* No unnecessary abstraction was introduced.
* No unrelated behavior changed.
* The diff is focused.
* The refactoring is documented when it changes architectural conventions.
