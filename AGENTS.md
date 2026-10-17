# Senior Laravel Developer Guidelines

You are a Senior Laravel Developer. Build maintainable, scalable, and robust code adhering strictly to best practices and the established architecture of this codebase.

---

## 1. Core Principles (DRY, KISS, SOLID)

- **Single Responsibility Principle (SRP)**: Keep classes and methods small and focused on a single responsibility. Keep controllers thin; offload business operations to Actions or Domain Services.
- **Open/Closed Principle (OCP)**: Code should be open for extension, closed for modification. Use interfaces, events, and strategy patterns where variation is expected.
- **Liskov Substitution Principle (LSP)**: Implementations must honor interface contracts without unexpected side-effects.
- **Interface Segregation Principle (ISP)**: Create narrow, client-specific contracts in `app/Interfaces/`.
- **Dependency Inversion Principle (DIP)**: Depend on abstractions (`app/Interfaces/`), never concrete implementations. Bind interfaces to implementations in service providers.
- **DRY (Don't Repeat Yourself)**: Avoid duplicating logic, queries, validation rules, or markup. Extract shared logic into traits, actions, scopes, or components only when duplication is real, not accidental.
- **KISS (Keep It Simple, Stupid)**: Favor clean, readable code over clever or speculative abstractions. If a simple native Laravel feature solves the problem, use it.

---

## 2. Project Architecture & Directory Structure

Follow the project's layered Clean Architecture / DDD conventions:

- **`app/Domain/`**: Core business entities, Eloquent models, domain logic, and value objects.
- **`app/Application/`**: Application use-cases, Actions, and DTOs (using `spatie/laravel-data`).
- **`app/Infrastructure/`**: Repositories, database implementations, external API clients, and file system adapters.
- **`app/Interfaces/`**: Contracts and interfaces that define application and repository boundaries.
- **`app/Http/`**: Thin HTTP layer.
  - **Controllers**: Handle HTTP routing, authorize requests, invoke an Action/Service, and return response/view.
  - **Requests / Rules**: Validate requests using dedicated Form Requests or `app/Rules/`.
  - **Livewire / Volt**: Reactive UI components in `resources/views/livewire/` or `app/Http/Livewire/`.
- **`app/Support/`, `app/Utils/`, `app/Traits/`**: General-purpose cross-cutting helpers and traits.

---

## 3. UI Component Reuse & Frontend Standards

- **Check Existing Components First**: Always inspect `resources/views/components/` before creating new Blade markup or custom HTML elements.
- **Reuse Built-in UI Components**:
  - Base UI: `<x-ui.button>`, `<x-ui.input>`, `<x-ui.select>`, `<x-ui.card>`, `<x-ui.table>`, `<x-ui.badge>`, `<x-ui.alert>`, `<x-ui.empty-state>`, `<x-ui.back-button>`, `<x-ui.delete-button>`.
  - Form Layouts: `<x-form.form-group>`, `<x-form.form-actions>`.
  - Layouts: `<x-layout.app>`, `<x-layout.topbar>`, `<x-layout.sidebar>`, `<x-layout.breadcrumb>`.
  - Domain Components: `<x-product.product-card>`, `<x-product.product-list>`, `<x-stock.stock-list>`.
- **Component Consistency**: Use Tailwind CSS v4 classes matching the existing styling. When a reusable pattern occurs across 2+ views, extract it to a Blade component under `resources/views/components/`.
- **Edge States**: Ensure UI handles loading, empty states (`<x-ui.empty-state>`), error messages, and disabled actions.

---

## 4. Concurrency & Race Condition Prevention

- **Pessimistic Locking**: For critical read-modify-write workflows (e.g., stock levels, balances, reservations, checkout, payment processing), wrap queries in `DB::transaction()` and apply pessimistic locks via `lockForUpdate()` or `sharedLock()`.
- **Atomic Database Operations**:
  - Use database-level atomic increments/decrements (`increment()`, `decrement()`) instead of reading to memory, mutating, and writing back.
  - Use atomic conditional updates (`Model::where('id', $id)->where('status', 'pending')->update(['status' => 'processing'])`) and check affected row counts.
- **Database-Level Unique Constraints**: Never rely solely on application or Form Request validation for uniqueness. Define `unique()` indexes in migrations and handle concurrent inserts with `upsert()`, `firstOrCreate()`, or catch database `QueryException`.
- **Distributed Atomic Locks**: For asynchronous queue workers, scheduled tasks, or external webhook ingestions, acquire atomic distributed locks using `Cache::lock($key, $seconds)->block($wait, function () { ... })`.
- **Optimistic Locking**: When long-running workflows or forms can experience concurrent modifications, maintain an update version/timestamp column and verify it matches before persisting.

---

## 5. N+1 Query Prevention & Database Performance

- **Mandatory Eager Loading**: Always eager load relationships (`with()`, `load()`, `loadMissing()`) whenever accessing related model attributes in loops, Blade templates, API resources, or Livewire components.
- **Fail-Fast Strict Mode**: Keep `Model::preventLazyLoading(! app()->isProduction())` enabled (configured in `AppServiceProvider`) to break immediately on unintended lazy loading during development and testing.
- **Subquery Aggregates Over Hydration**: Use `withCount()`, `withExists()`, `withSum()`, `withAvg()`, or `selectSub()` instead of loading entire relationship collections just to check counts or summary totals.
- **Chunking & Lazy Cursors**: For large batch processes, exports, or data migrations, process records using `chunkById()` or `lazy()`/`cursor()` to maintain constant memory usage and avoid querying all rows into memory.
- **Encapsulate Common Queries in Scopes**: Keep reusable eager-loading and query constraints inside model local scopes (e.g. `scopeWithDetails()`) to enforce consistent loading patterns across controllers and actions.

---

## 6. Mandatory Automated Testing & Verification Gate (Strict Rule)

- **Mandatory Pre-Commit & Pre-Push Verification**: Before executing ANY `git commit` or `git push` (and before reporting completion), you **MUST ALWAYS** run and verify 100% green status with zero failures on:
  1. **Unit & Feature Test Suite**: `php artisan test` (must achieve 100% green with 0 failures).
  2. **UI & E2E Browser Test Suite**: `npm run test:e2e` / `npx playwright test` (all browser UI tests for CRUD, wizards, live search, responsive layouts, theme toggle, and localization).
- **Never Deliver or Commit Untested Code**: No commit or push is permitted until actively proven green by running both test suites (`php artisan test` and `npm run test:e2e`).
- **Comprehensive Test Coverage**: Every newly created or modified workflow (POS transaction, stock movement, pricing calculation, API endpoint, Livewire component, or validation rule) must have corresponding test coverage in `tests/Feature/`, `tests/Unit/`, or `tests/e2e/`.
- **Isolation & Mocking for Heavy Algorithms**: Test complex business calculations in isolation by mocking repository and external service dependencies (`$this->createMock(...)`) under `tests/Unit/`.
- **Comprehensive Edge-Case Scenarios**:
  - Empty / null inputs and collections.
  - Boundary limits (zero, max quantities, negative balances).
  - Out-of-order execution, partial updates, and duplicate payloads.
  - Exception triggering on unauthorized or invalid domain state.
- **Pre-Test Formatting**: Always run `vendor/bin/pint` on modified files before running the final tests.

---

## 7. Laravel Best Practices & Code Standards

- **PHP 8.2+ Standards**: Use strict typing (`declare(strict_types=1);`), typed properties, and explicit return types everywhere.
- **Transactions for Multi-Step Writes**: Always encapsulate multi-table writes in `DB::transaction()` to ensure atomicity.
- **Code Style**: Format and fix code using Laravel Pint (`vendor/bin/pint`).
