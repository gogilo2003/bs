---
name: application-modernization
description: Modernize a Laravel 13 application while preserving business behavior, improving architecture, and adopting modern Laravel, PHP, Vue, and TypeScript conventions.
---

# Application Modernization Skill

## Purpose

Modernize an existing Laravel 13 application after or alongside the Laravel 10 → Laravel 13 migration while preserving existing business behavior.

This skill is separate from framework compatibility migration.

The primary goals are:

* Modern Laravel conventions
* Clean PHP architecture
* Thin controllers
* Strong typing
* Vue 3 Composition API
* TypeScript
* Typed Inertia boundaries
* Reusable Vue components
* Maintainable services/actions
* Improved testability
* Reduced duplication
* Clear separation of concerns

---

## Core Principle

Modernize incrementally.

Do not rewrite the application simply because older code exists.

The priority is:

```text
Correctness
    ↓
Functional parity
    ↓
Test coverage
    ↓
Maintainability
    ↓
Modernization
    ↓
Optimization
```

Never sacrifice working business behavior merely to make code look newer.

---

## Separate Migration From Modernization

Every change must be classified as one of:

### Migration-required

A change required for Laravel 13 compatibility.

### Modernization

A change that improves architecture, maintainability, typing, or developer experience but is not required for Laravel 13.

### Feature change

A change that alters business behavior or user-visible functionality.

Do not silently mix these categories.

Prefer separate commits for substantial modernization work.

---

## Modernization Rules

### Preserve business behavior

Unless explicitly instructed otherwise, modernization must preserve:

* Business rules
* Validation behavior
* Authorization behavior
* Financial calculations
* Database meaning
* Existing workflows
* Existing routes
* Existing permissions
* Existing notifications
* Existing integrations

If behavior must change, document the reason.

---

## Prefer Incremental Refactoring

Modernize one logical feature at a time.

Preferred workflow:

```text
Inspect
↓
Understand
↓
Test existing behavior
↓
Identify improvement
↓
Refactor
↓
Run tests
↓
Review diff
↓
Commit
```

Do not perform repository-wide rewrites unless explicitly requested.

---

## PHP Modernization

Prefer modern PHP features supported by the project's PHP version.

Use:

* Strict typing
* Typed properties
* Parameter types
* Return types
* Nullable types
* Union/intersection types where appropriate
* Enums
* Readonly properties where appropriate
* Constructor property promotion where appropriate
* Value objects where justified
* Explicit dependency injection

Avoid introducing abstractions solely for the sake of abstraction.

---

## Laravel Modernization

Prefer current Laravel conventions.

Use:

* Form Requests for complex validation
* Policies for authorization
* Actions/services for substantial application operations
* Eloquent relationships
* Query scopes where reusable
* Events/jobs for appropriate asynchronous workflows
* Notifications for notifications
* Resources where API transformation is required
* Enums for finite domain values
* Route model binding where appropriate

Do not introduce an abstraction if a simple Laravel implementation is clearer.

---

## Repository Pattern

If the existing project uses repositories, do not remove them automatically.

Evaluate each repository.

Keep repositories when they provide meaningful value such as:

* Complex query abstraction
* Multiple data sources
* Reusable persistence operations
* Significant domain-specific querying

Avoid repositories that merely wrap simple Eloquent calls.

Do not replace established architecture across the entire application during a feature migration.

---

## Service Classes

Services should represent meaningful application operations.

Good:

```text
RegisterStudent
GenerateStudentInvoice
ProcessFeePayment
GenerateAcademicReport
```

Avoid generic classes such as:

```text
StudentService
CommonService
HelperService
UtilityService
```

when they become dumping grounds for unrelated functionality.

---

## Action Classes

Actions may be used when an operation has:

* A clear single responsibility
* A meaningful name
* Reusable behavior
* Significant business logic
* A need for isolated testing

Do not create an Action class for trivial one-line operations.

---

## Controllers

Controllers should remain thin.

They should primarily:

1. Receive the request.
2. Authorize.
3. Validate through Form Requests where appropriate.
4. Invoke application logic.
5. Return a response.

See:

```text
@.ai/skills/thin-controller-refactoring.md
```

for detailed controller rules.

---

## Vue Modernization

All new or modernized Vue components should use:

```vue
<script setup lang="ts">
```

Prefer:

* Composition API
* TypeScript
* `defineProps`
* `defineEmits`
* `defineModel` where appropriate
* Composables
* Typed interfaces
* Typed API/Inertia data
* Small focused components

Avoid introducing Options API into modernized code.

Do not rewrite every existing component merely to change syntax.

---

## TypeScript

New frontend code should be TypeScript.

Modernize JavaScript components when they are being actively modified or when there is a clear benefit.

Prefer:

```ts
interface Student {
    id: number
    name: string
}
```

over:

```ts
const student: any = ...
```

Avoid `any` unless there is a documented reason.

Prefer precise types over excessive type assertions.

---

## Inertia

Treat the Laravel → Inertia → Vue boundary as a typed contract.

Prefer typed page props.

Example:

```vue
<script setup lang="ts">
interface Student {
    id: number
    name: string
    admission_number: string
}

interface Props {
    student: Student
}

const props = defineProps<Props>()
</script>
```

Do not rely on `Object`, `Array`, or `any` when meaningful types can be defined.

---

## Vue Components

Extract components when:

* A component is becoming too large.
* A section has independent behavior.
* A UI pattern is reused.
* A complex form section has its own state.
* A component has a clear domain responsibility.

Do not split components merely to reduce line count.

A component should have a meaningful responsibility.

---

## Composables

Use composables for reusable client-side behavior.

Examples:

```text
usePagination
useFilters
useModal
useForm
usePermissions
useStudents
useNotifications
```

Do not create composables that simply move a few lines of code without improving reuse or clarity.

---

## Types

Prefer organizing shared frontend types under:

```text
resources/js/Types/
```

or the existing project-specific type structure.

Domain types should have meaningful names.

Avoid duplicating the same interface across multiple pages.

---

## Testing

Refactoring should preserve or improve test coverage.

Before substantial refactoring:

1. Identify existing tests.
2. Add missing tests when practical.
3. Refactor.
4. Run tests.

Never remove tests simply because the implementation changed.

---

## Avoid Overengineering

Do not introduce:

* Unnecessary design patterns
* Excessive interfaces
* Unnecessary repositories
* Generic services
* Generic utility classes
* Excessive DTOs
* Unnecessary factories
* Unnecessary abstractions

Prefer the simplest architecture that clearly expresses the business operation.

---

## Definition of Done

A modernization task is complete when:

* Existing behavior is preserved.
* The new architecture is clearer.
* Tests pass.
* Types are correct.
* No unnecessary abstractions were introduced.
* The diff is focused.
* The code follows project conventions.
* The change is documented when architectural behavior changed.
