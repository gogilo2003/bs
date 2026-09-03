---
name: vue-typescript-modernization
description: Modernize Vue frontend using Vue 3 Composition API, script setup, TypeScript, and Inertia.js typed contracts.
---

# Vue 3 + TypeScript Modernization Skill

## Purpose

Modernize the application's frontend using Vue 3 Composition API, `<script setup>`, TypeScript, Inertia.js, and reusable components while preserving existing functionality and UI behavior.

---

# Target Standard

All new and substantially modified Vue components should use:

```vue
<script setup lang="ts">
```

Use Vue 3 Composition API.

Do not introduce new Options API components.

Do not introduce JavaScript components unless there is a documented reason.

---

# Core Principles

```text
Typed
↓
Composable
↓
Reusable
↓
Focused
↓
Testable
```

The goal is not to convert every `.js` file immediately.

Modernize incrementally.

---

# Component Structure

Prefer:

```text
@resources/js/
├── Components/
│   ├── UI/
│   ├── Forms/
│   └── Domain/
├── Composables/
├── Layouts/
├── Pages/
├── Types/
└── Utils/
```

Follow the existing project structure if it already has a coherent organization.

Do not reorganize the entire frontend merely for consistency.

---

# Script Setup

Prefer:

```vue
<script setup lang="ts">
import { computed, ref } from 'vue'

const count = ref(0)

const doubled = computed(() => count.value * 2)
</script>
```

Avoid new:

```vue
<script>
export default {
    // ...
}
</script>
```

---

# Props

Avoid:

```ts
defineProps({
    student: Object,
})
```

Prefer:

```ts
interface Student {
    id: number
    name: string
}

interface Props {
    student: Student
}

const props = defineProps<Props>()
```

Use interfaces or type aliases appropriate to the domain.

---

# Emits

Prefer typed emits:

```ts
const emit = defineEmits<{
    save: [student: Student]
    cancel: []
}>()
```

Avoid untyped event contracts.

---

# Models

When appropriate, use Vue's modern model APIs.

Example:

```ts
const model = defineModel<boolean>()
```

Do not introduce it merely to rewrite stable code without benefit.

---

# Inertia Props

Treat Inertia page props as a typed contract.

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

Avoid:

```ts
const props = defineProps<any>()
```

unless there is a documented reason.

---

# Shared Types

Shared domain types should live in:

```text
@@resources/js/Types/
```

or the existing project-specific equivalent.

Avoid defining the same domain type repeatedly.

Example:

```ts
export interface Student {
    id: number
    name: string
    admission_number: string
}
```

---

# Generic Pagination Types

Where applicable:

```ts
export interface Paginated<T> {
    data: T[]
    current_page: number
    first_page_url: string | null
    from: number | null
    last_page: number
    last_page_url: string | null
    per_page: number
    to: number | null
    total: number
}
```

Use:

```ts
students: Paginated<Student>
```

rather than duplicating pagination structures.

Adapt the interface to the actual application's response shape.

---

# Avoid `any`

Do not use:

```ts
const data: any
```

unless genuinely unavoidable.

Prefer:

```ts
unknown
```

when the data is unknown and then narrow it.

Use explicit domain types whenever possible.

---

# Composables

Create composables when behavior is reusable.

Examples:

```text
usePagination
useFilters
useModal
usePermissions
useForm
useNotifications
```

A composable should represent behavior, not merely move arbitrary code out of a component.

---

# Forms

Use typed form data where practical.

Keep:

* Validation
* Error handling
* Submission state
* Server-side validation
* Reset behavior

consistent with the application's Inertia implementation.

Do not change form behavior merely to introduce TypeScript.

---

# Component Extraction

Extract components when:

- The component has a clear independent responsibility.
- A UI pattern is reused.
- A complex form section has independent behavior.
- A section is domain-specific.
- A component is difficult to understand or test as one unit.

Do not split components purely according to line count.

## Reuse Existing Components

Before creating any new Vue component, check `@resources/js/Components` for an existing component that already provides the needed UI pattern or behavior.

Do not create duplicate or overlapping components.

If a suitable component exists, reuse or extend it rather than creating a new one.

Only create a new component when no existing component satisfies the requirement.

---

# Page Components

Pages should coordinate:

* Page-level state
* Page-level data
* Layout
* Child components
* Navigation

Move reusable UI and behavior into components/composables.

Avoid turning every small block into a component.

---

# API and Backend Types

Where possible, align TypeScript types with Laravel response structures.

Do not invent frontend fields that do not exist in the backend response.

If a backend response changes, update the TypeScript contract.

---

# Route Generation

Use the project's established route generation strategy.

If the project uses Ziggy, preserve it unless a deliberate migration to another route-generation solution has been approved.

Do not mix multiple route-generation strategies unnecessarily.

---

# Styling

Preserve the Laravel 13 project's frontend build foundation.

Do not blindly copy Laravel 10 Vite, Tailwind, or PostCSS configuration.

Migrate application styles into the new foundation.

---

# Accessibility

Modernization should not reduce accessibility.

Preserve or improve:

* Labels
* Keyboard navigation
* Focus states
* Semantic HTML
* ARIA where appropriate
* Form accessibility
* Button semantics

---

# Performance

Do not prematurely optimize.

Consider:

* Lazy loading
* Component splitting
* Avoiding unnecessary watchers
* Computed values
* Efficient lists
* Inertia partial reloads

when there is a demonstrated benefit.

---

# Refactoring Procedure

For each Vue feature:

1. Identify the page.
2. Identify its props.
3. Check `@resources/js/Components` for existing components that can be reused.
4. Identify components.
5. Identify JavaScript files.
6. Identify existing TypeScript.
7. Identify composables.
8. Identify backend response structures.
9. Identify tests.
10. Convert the relevant component to `<script setup lang="ts">`.
11. Introduce explicit types.
12. Extract reusable behavior where justified.
13. Run type checks.
14. Run linting.
15. Run frontend build.
16. Review the diff.

---

# Do Not Change UX Accidentally

During modernization preserve:

* Labels
* Field names
* Validation behavior
* Buttons
* Navigation
* Modals
* Tables
* Pagination
* Filters
* Sorting
* Permissions
* Loading states
* Error states
* Flash messages

UI improvements should be explicit feature work, not accidental consequences of modernization.

---

# Definition of Done

A Vue modernization task is complete when:

* Component uses Vue 3 Composition API.
* `<script setup lang="ts">` is used.
* Props are typed.
* Emits are typed where applicable.
* Important domain data is typed.
* No unnecessary `any` remains.
* Reusable behavior is extracted appropriately.
* Existing behavior is preserved.
* Type checks pass.
* ESLint passes where configured.
* Frontend build passes.
* The diff remains focused.
