---
name: thin-controller-refactoring
description: Refactor fat Laravel controllers into thin controllers by extracting business logic into Actions, Services, or Form Requests.
---

# Thin Controller Refactoring Skill

## Purpose

Transform fat Laravel controllers into thin controllers that delegate business logic to dedicated classes.

A thin controller:
1. Receives the request.
2. Authorizes.
3. Validates through Form Requests.
4. Invokes application logic.
5. Returns a response.

---

## Identify Fat Controllers

A controller is fat when it:
- Contains business logic
- Performs database operations directly
- Validates inline instead of using Form Requests
- Authorizes inline instead of using Policies
- Has complex conditional logic
- Manages transactions directly
- Calls multiple services/repositories without clear orchestration
- Has methods exceeding 30-40 lines

---

## Extraction Targets

### Form Requests

Move validation logic into Form Requests when:
- Validation rules are complex
- Multiple fields need conditional validation
- Authorization checks belong with validation
- Validation logic is reused

Example:
```text
StoreStudentRequest
UpdateStudentRequest
```

### Policies

Move authorization logic into Policies when:
- Authorization is based on model ownership
- Multiple actions share the same authorization logic
- Gate checks are repeated across controllers

### Actions

Create Action classes when:
- The operation has a clear single responsibility
- The operation is reusable
- The operation has significant business logic
- The operation needs isolated testing
- The controller merely orchestrates the operation

Good Action names:
```text
RegisterStudent
GenerateInvoice
ProcessPayment
CalculateFeeBalance
ApproveApplication
```

Avoid generic Action names:
```text
StudentAction
CommonAction
HelperAction
```

### Services

Create Service classes when:
- The operation coordinates multiple domain objects
- The operation is meaningful to the domain
- The operation is reused across controllers
- The operation encapsulates a complete workflow

Good Service names:
```text
StudentRegistrationService
InvoiceGenerationService
FeePaymentService
```

Avoid generic Service names:
```text
StudentService
CommonService
HelperService
UtilityService
```

These become dumping grounds for unrelated functionality.

---

## Refactoring Workflow

```text
Identify fat controller
↓
Understand current behavior
↓
Check existing tests
↓
Identify extraction targets
↓
Create Form Requests for validation
↓
Create Policies for authorization
↓
Create Actions/Services for business logic
↓
Inject dependencies explicitly
↓
Simplify controller method
↓
Run tests
↓
Review diff
↓
Commit
```

---

## Controller Method Shape

After refactoring, a controller method should look like:

```php
public function store(StoreStudentRequest $request, RegisterStudent $action)
{
    $student = $action->execute($request->validated());

    return to_route('students.show', $student)
        ->with('success', 'Student registered successfully.');
}
```

Or with Inertia:

```php
public function index(StudentIndexRequest $request)
{
    $students = app(StudentIndexQuery::class)
        ->forAuthUser()
        ->paginate();

    return Inertia::render('Students/Index', [
        'students' => $students,
    ]);
}
```

---

## Dependency Injection

Prefer explicit dependency injection:

```php
public function __construct(private readonly RegisterStudent $action)
{
}
```

Or method injection for one-off operations:

```php
public function store(StoreStudentRequest $request, RegisterStudent $action)
```

Avoid resolving dependencies directly inside controller methods:

```php
// Avoid
$action = app(RegisterStudent::class);
```

---

## Avoid Arbitrary Extraction

Do not create classes merely to shorten a controller.

Extract when the logic:
- Has a meaningful name
- Has a clear responsibility
- Can be tested independently
- May be reused

Do not extract trivial one-line operations.

---

## Testing

After refactoring:
- Existing tests should still pass
- Business logic tests should move with the extracted logic
- Controller tests become thinner (testing orchestration)
- Extracted classes should have their own tests

---

## Definition of Done

A controller refactoring is complete when:
- The controller method is under 30 lines
- Business logic is extracted to Actions/Services
- Validation uses Form Requests
- Authorization uses Policies
- Dependencies are explicitly injected
- Tests pass
- Behavior is preserved
- The diff is focused
