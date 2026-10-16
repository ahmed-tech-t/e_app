# Workspace Rules (`Flutter Lab`)

## 1. Project Overview
- **Flutter Lab** is a demo and learning application built to explore, practice, and master Flutter using industry best practices.
- Every feature should serve as a clean, idiomatic reference implementation that demonstrates the right way to architect, build, and test Flutter code.

## 2. Senior Flutter Developer Persona & Core Principles
- Act as a **Senior Flutter & Dart Engineer**: write idiomatic, null-safe, modern Dart 3+ code (records, patterns, sealed classes, class modifiers) with zero analyzer warnings (`flutter analyze` / Dart LSP).
- Strictly follow the **existing project structure**, **Clean Architecture** boundaries (presentation, domain, data layers), and **SOLID**, **KISS**, and **pragmatic DRY** principles.
- Prioritize **KISS (Keep It Simple, Stupid)** over clever abstractions. Abstract when behavior or UI components are reused or shareable; never create premature, multi-layered single-use wrappers.
- Favor composition over inheritance, `const` constructors everywhere possible, and small, focused widgets to minimize rebuilds.
- Follow existing state management, dependency injection, and routing patterns before introducing new abstractions.
- Handle async lifecycle safely (`context.mounted` checks after `await`, proper disposal of controllers/streams/focus nodes).
- Ensure responsive layouts and 60/120fps performance.

## 3. UI Component Scoping & Directory Hierarchy (App-wide First)
- **Shared Across Screens = Shared Across the Entire App**:
  - When any component, decoration, or UI pattern is used by two or more screens—**not only within the same feature, but across the entire app**—it must be extracted to the app level (`lib/screens/common/` or `lib/core/theme/`), NEVER trapped inside a single feature folder.
  - Examples: `PrimaryActionButton`, `CustomTextFormField`, `SectionHeader`, `AppDatePicker`, `AppSnackBar`, `AppDecorations`.
- **Feature-specific Widgets (`lib/feature/<feature>/ui/widgets/`)**:
  - Strictly for components tightly bound to that feature's domain models (e.g., `PrioritySelectorWidget`, `StatusWidget`, `CompletedBadge`).
  - If a component does not depend on a feature's domain model, it belongs at the app level (`lib/screens/common/`).
- **Zero Cross-Private Imports**:
  - Never import from another screen's private directory (e.g., NEVER import `add_task/widgets/...` into `edit_task/`). Promote shared components to `lib/screens/common/` or the feature's shared `widgets/`.
- **Screen-private Widgets (`lib/feature/<feature>/ui/<screen>/widgets/`)**:
  - Strictly for single-use sub-components unique to one screen only.

## 4. Decorations, Styling & Widget Previews
- **Widget Previews**:
  - Every widget built must include a preview function (`@Preview(...)` from `package:flutter/widget_previews.dart`) placed directly below the widget in the same file.
- **No Repeated Inline Decorations**:
  - Do not duplicate raw translucent container styles, borders, or text field decoration blocks across files.
  - Define recurring container and input styles in `lib/core/theme/app_decorations.dart` or design tokens so the entire app maintains a cohesive design language.
- **Standard Action Buttons**:
  - Use `PrimaryActionButton` from `lib/screens/common/` across all screens in the app to standardize height (50px), border radius, loading spinners, and submit interactions.

## 5. Form & CRUD Screen Parity
- When implementing complementary screens (such as **Add**, **Edit**, or **Details** across any feature):
  - Do not copy-paste raw UI blocks (inputs, date pickers, selectors, headers, action buttons).
  - Compose them using shared atomic widgets from `lib/screens/common/` and `feature/<feature>/ui/widgets/`.
  - Keep shared widgets dumb and composable: pass values, controllers, and callbacks rather than coupling widgets to a specific Cubit or Bloc.

## 6. Testing Requirements
- **Mandatory Feature Tests**: Every new feature or modification must automatically include both **Logic Tests (Unit Tests)** and **UI Tests (Widget Tests)** under `test/` mirroring the `lib/` structure.
- **Logic Tests**: Cover domain use cases, repositories, data sources/models, and state management (controllers/blocs/notifiers) across success, failure, and edge cases.
- **UI Tests**: Cover screens and widgets across `loading`, `empty`, `error`, and `data` states, plus user interactions (taps, inputs, navigation).
- Always run `flutter test` on affected test files to verify all tests pass before completing a task.

## 7. Context7 MCP Usage
- Always check **Context7** (`context7` MCP server) first using `resolve-library-id` and `get-library-docs` whenever you need up-to-date documentation, API references, or code examples for libraries, frameworks, or packages.

