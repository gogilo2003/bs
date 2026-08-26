---
name: modernization-audit
description: Analyze a Laravel 13 application and produce a prioritized modernization plan without modifying application code.
---

# Application Modernization Audit Skill

## Purpose

Analyze the Laravel 13 application and produce a prioritized modernization plan without modifying application code.

This skill is an analysis-only skill.

Do not make changes unless explicitly instructed after the audit.

---

# Objectives

Identify opportunities to improve:

* Laravel architecture
* PHP code quality
* Controller design
* Service/action boundaries
* Type safety
* Vue architecture
* TypeScript adoption
* Inertia contracts
* Component reuse
* Testing
* Dependency health
* Frontend tooling

---

# Audit Categories

## Backend

Inspect:

```text
app/
routes/
database/
tests/
```

Identify:

* Fat controllers
* Missing Form Requests
* Complex business logic in controllers
* Weak service boundaries
* Unnecessary repositories
* Duplicated business logic
* Missing authorization
* Missing tests
* Untyped PHP
* Large classes
* Large methods

---

## Frontend

Inspect:

```text
resources/js/
resources/css/
```

Identify:

* JavaScript Vue components
* Options API components
* Untyped props
* `any`
* Duplicated interfaces
* Large pages
* Duplicated components
* Missing composables
* Repeated form logic
* Repeated pagination/filter logic
* Poor component boundaries

---

# Classification

Every finding should be classified:

```text
CRITICAL
HIGH
MEDIUM
LOW
OPTIONAL
```

Do not classify subjective style preferences as high priority.

---

# Modernization Matrix

Produce:

```text
Feature | Area | Finding | Priority | Recommended Skill | Effort
```

Example:

```text
Students | Backend | StudentController contains registration logic | High | thin-controller-refactoring | Medium

Students | Frontend | Student form is untyped JavaScript | Medium | vue-typescript-modernization | Low
```

---

# Recommended Order

Prioritize:

```text
Security
↓
Correctness
↓
Test coverage
↓
High-complexity backend
↓
High-complexity frontend
↓
Type safety
↓
Duplication
↓
Developer experience
↓
Cosmetic cleanup
```

---

# Do Not Modify

This audit must not:

* Rewrite code
* Rename files
* Change dependencies
* Change database schema
* Change routes
* Change business behavior

The output is a plan.

---

# Output

Produce:

## 1. Executive Summary

Brief overview of the application's modernization state.

## 2. Backend Findings

Prioritized list.

## 3. Frontend Findings

Prioritized list.

## 4. Architecture Findings

Prioritized list.

## 5. Testing Findings

Prioritized list.

## 6. Dependency Findings

Prioritized list.

## 7. Recommended Roadmap

Break the modernization into small logical units.

## 8. Quick Wins

Identify low-risk improvements.

## 9. High-Value Refactors

Identify changes with significant maintainability benefits.

## 10. Risks

Identify areas where refactoring could change behavior.

---

# Final Rule

The audit should answer:

> "What should we modernize, why, in what order, and what should we leave alone?"

It should NOT attempt to modernize everything.
