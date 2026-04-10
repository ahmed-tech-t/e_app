# POS System Web Frontend — Technical Specification

## 1. Overview

Build a web frontend for the existing POS system using **Blade components**, **Livewire Volt (search + stock pages)**, and **Tailwind CSS v4**. CRUD operations (create/edit/delete) use traditional Blade forms with standard controller routes. Livewire Volt is used for **search/filtering on index pages**, **stock movements listing with filters**, and **stock transfer with multi-step preview**. All Volt components are single `.blade.php` files using Volt's functional API — no separate PHP class files. The web layer integrates directly with existing Application Services. No authentication for now (will be added later). The UI must be responsive (mobile + small screens), support dark mode (persisted via `localStorage`), and feature a collapsible sidebar.

---

## 2. Architecture Decisions

| Decision            | Choice                                     | Rationale                                                          |
| ------------------- | ------------------------------------------ | ------------------------------------------------------------------ |
| Frontend framework  | Blade + Livewire Volt (functional API)     | Volt for reactive search + stock pages; traditional forms for CRUD |
| CSS framework       | Tailwind CSS v4                            | Already installed in `package.json`                                |
| Component model     | Blade components (anonymous + class)       | Reusable, DRY, follows Laravel conventions                         |
| CRUD pattern        | Traditional Blade forms + controllers      | POST/PUT/DELETE routes, server-side validation, redirects          |
| Interactive pages   | Livewire Volt (single-file functional)     | Search, stock movements, stock transfer — all in .blade.php files  |
| Auth                | None (deferred)                            | Will be added later                                                |
| Data source         | Application Services injected directly     | No HTTP overhead; follows existing clean arch pattern              |
| Dark mode           | Tailwind `class` strategy + `localStorage` | Client-side toggle, no server dependency                           |
| Delete confirmation | JavaScript `confirm()`                     | Lightweight, no extra dependencies                                 |
| Testing             | PHPUnit feature tests                      | Consistent with existing `phpunit.xml` setup                       |

---

## 3. New Packages to Install

```
composer require livewire/livewire
```

---

## 4. Directory Structure (New Files Only)

```
app/
  Interfaces/
    Http/
      Controllers/
        Web/
          DashboardController.php
          SupplierController.php
          ProductController.php
          SaleUnitController.php
          LocationController.php
          CategoryController.php
          PurchaseController.php
          SaleController.php
          StockController.php

resources/
  views/
    components/
      layout/
        app.blade.php               (main layout shell: sidebar + topbar + content area)
        sidebar.blade.php           (collapsible sidebar navigation)
        topbar.blade.php            (top bar: dark mode toggle)
        breadcrumb.blade.php        (breadcrumb navigation)
      ui/
        button.blade.php            (reusable button: primary, secondary, danger, sizes)
        table.blade.php             (reusable data table with sortable headers, overflow-x for mobile)
        card.blade.php              (stat card / info card)
        badge.blade.php             (status badge: success, warning, danger, info)
        alert.blade.php             (flash notification: success, error, warning, info)
        input.blade.php             (form input wrapper: label, input, error)
        select.blade.php            (form select wrapper: label, select, error)
        empty-state.blade.php       (empty data placeholder with icon + message)
        pagination.blade.php        (pagination links)
        delete-button.blade.php     (delete form button with JS confirm())
        back-button.blade.php       (back navigation button)
      form/
        form-group.blade.php        (label + input/error wrapper)
        form-actions.blade.php      (submit + cancel button group)
    layouts/
      app.blade.php                 (main layout — uses layout/ components)
    suppliers/
      index.blade.php
      create.blade.php
      edit.blade.php
    products/
      index.blade.php
      create.blade.php
      edit.blade.php
      show.blade.php
    sale-units/
      index.blade.php
      create.blade.php
      edit.blade.php
    locations/
      index.blade.php
      create.blade.php
      edit.blade.php
    categories/
      index.blade.php
      create.blade.php
      edit.blade.php
    purchases/
      index.blade.php
      create.blade.php
      show.blade.php
    sales/
      index.blade.php
      create.blade.php
      show.blade.php
    stock/
      index.blade.php               (embeds stock-movements Volt component)
      transfer.blade.php            (embeds stock-transfer Volt component)
    livewire/
      search/
        supplier-search.blade.php   (Volt functional — search + table + pagination)
        product-search.blade.php
        sale-unit-search.blade.php
        location-search.blade.php
        category-search.blade.php
        purchase-search.blade.php
        sale-search.blade.php
      stock/
        stock-movements.blade.php   (Volt functional — filter + table for stock movements)
        stock-transfer.blade.php    (Volt functional — multi-step transfer form with preview)
    dashboard.blade.php

routes/
  web.php                           (new: all web routes)
  api.php                           (existing: unchanged)

tests/
  Feature/
    DashboardTest.php
    Suppliers/
      SupplierCrudTest.php
      SupplierSearchTest.php
    Products/
      ProductCrudTest.php
      ProductSearchTest.php
    SaleUnits/
      SaleUnitCrudTest.php
      SaleUnitSearchTest.php
    Locations/
      LocationCrudTest.php
      LocationSearchTest.php
    Categories/
      CategoryCrudTest.php
      CategorySearchTest.php
    Purchases/
      PurchaseCrudTest.php
      PurchasePreviewTest.php
    Sales/
      SaleCrudTest.php
      SalePreviewTest.php
    Stock/
      StockMovementsTest.php
      StockTransferTest.php
  Unit/
    Livewire/
      Search/
        SupplierSearchTest.php
        ProductSearchTest.php
        SaleUnitSearchTest.php
        LocationSearchTest.php
        CategorySearchTest.php
        PurchaseSearchTest.php
        SaleSearchTest.php
      Stock/
        StockMovementsTest.php
        StockTransferTest.php
```

---

## 5. Routing Plan (`routes/web.php`)

No auth middleware. Standard RESTful routes for CRUD.

```
# Dashboard
GET    /                           -> DashboardController@index

# Suppliers
GET    /suppliers                  -> SupplierController@index
GET    /suppliers/create           -> SupplierController@create
POST   /suppliers                  -> SupplierController@store
GET    /suppliers/{id}/edit        -> SupplierController@edit
PUT    /suppliers/{id}             -> SupplierController@update
DELETE /suppliers/{id}             -> SupplierController@destroy

# Products
GET    /products                   -> ProductController@index
GET    /products/create            -> ProductController@create
POST   /products                   -> ProductController@store
GET    /products/{id}              -> ProductController@show
GET    /products/{id}/edit         -> ProductController@edit
PUT    /products/{id}              -> ProductController@update
DELETE /products/{id}              -> ProductController@destroy

# Sale Units
GET    /sale-units                 -> SaleUnitController@index
GET    /sale-units/create          -> SaleUnitController@create
POST   /sale-units                 -> SaleUnitController@store
GET    /sale-units/{id}/edit       -> SaleUnitController@edit
PUT    /sale-units/{id}            -> SaleUnitController@update
DELETE /sale-units/{id}            -> SaleUnitController@destroy

# Locations
GET    /locations                  -> LocationController@index
GET    /locations/create           -> LocationController@create
POST   /locations                  -> LocationController@store
GET    /locations/{id}/edit        -> LocationController@edit
PUT    /locations/{id}             -> LocationController@update
DELETE /locations/{id}             -> LocationController@destroy

# Categories
GET    /categories                 -> CategoryController@index
GET    /categories/create          -> CategoryController@create
POST   /categories                 -> CategoryController@store
GET    /categories/{id}/edit       -> CategoryController@edit
PUT    /categories/{id}            -> CategoryController@update
DELETE /categories/{id}            -> CategoryController@destroy

# Purchases
GET    /purchases                  -> PurchaseController@index
GET    /purchases/create           -> PurchaseController@create
POST   /purchases                  -> PurchaseController@store
GET    /purchases/{id}             -> PurchaseController@show

# Sales
GET    /sales                      -> SaleController@index
GET    /sales/create               -> SaleController@create
POST   /sales                      -> SaleController@store
GET    /sales/{id}                 -> SaleController@show

# Stock
GET    /stock                      -> StockController@index
GET    /stock/transfer             -> StockController@transfer
POST   /stock/transfer             -> StockController@storeTransfer
```

> Each index view embeds a Volt search component. Create/Edit/Show are traditional Blade views with standard HTML forms and controller actions. Stock movements and stock transfer are full-page Volt components.

---

## 6. Web Controllers Specification

### 6.1 Controller Pattern

All web controllers extend `App\Interfaces\Http\Controllers\Controller` and inject Application Services via constructor. They follow the same pattern as the API `BaseController` but return Blade views instead of JSON.

#### Shared Controller Methods

```
index()    → renders index view with Livewire search component embedded
create()   → renders create form view with dropdown data loaded
store()    → validates via FormRequest, builds DTO, calls service->create(), redirects to index
edit($id)  → fetches entity via service->findById(), renders edit form view
update($id)→ validates via FormRequest, builds DTO, calls service->update(), redirects to index
destroy($id)→ calls service->destroy(), redirects to index with flash
show($id)  → fetches entity via service->findById(), renders show view (products, purchases, sales only)
```

### 6.2 `DashboardController`

```
__construct(
    ProductService $productService,
    SalesService $salesService,
    PurchaseService $purchaseService
)
index() → renders dashboard view with stats data
```

### 6.3 `SupplierController`

```
__construct(SupplierService $supplierService)
index()   → view('suppliers.index')
create()  → view('suppliers.create')
store(CreateSupplierRequest $request)  → dto = $request->toDto(), service->create(), redirect('/suppliers')
edit(int $id)    → entity from service->findById(), view('suppliers.edit')
update(int $id, UpdateSupplierRequest $request) → dto = $request->toDto(), service->update(), redirect('/suppliers')
destroy(int $id) → service->destroy(), redirect('/suppliers')
```

### 6.4 `ProductController`

```
__construct(
    ProductService $productService,
    CategoryService $categoryService,
    SaleUnitService $saleUnitService,
    ProductPriceService $productPriceService
)
index()   → view('products.index')
create()  → loads categories + sale units for dropdowns, view('products.create')
store(CreateProductRequest $request)  → dto = $request->toDto(), service->create(), redirect('/products')
show(int $id)  → entity + price history, view('products.show')
edit(int $id)  → entity + categories + sale units, view('products.edit')
update(int $id, UpdateProductRequest $request) → dto = $request->toDto(), service->update(), redirect('/products')
destroy(int $id) → service->destroy(), redirect('/products')
```

### 6.5 `SaleUnitController`

```
__construct(SaleUnitService $saleUnitService)
index()   → view('sale-units.index')
create()  → view('sale-units.create')
store(CreateSaleUnitRequest $request) → dto = $request->toDto(), service->create(), redirect('/sale-units')
edit(int $id)    → entity, view('sale-units.edit')
update(int $id, UpdateSaleUnitRequest $request) → dto = $request->toDto(), service->update(), redirect('/sale-units')
destroy(int $id) → service->destroy(), redirect('/sale-units')
```

### 6.6 `LocationController`

```
__construct(LocationService $locationService)
index()   → view('locations.index')
create()  → view('locations.create')
store(CreateLocationRequest $request) → dto = $request->toDto(), service->create(), redirect('/locations')
edit(int $id)    → entity, view('locations.edit')
update(int $id, UpdateLocationRequest $request) → dto = $request->toDto(), service->update(), redirect('/locations')
destroy(int $id) → service->destroy(), redirect('/locations')
```

### 6.7 `CategoryController`

```
__construct(CategoryService $categoryService)
index()   → view('categories.index')
create()  → view('categories.create')
store(CreateCategoryRequest $request) → dto = $request->toDto(), service->create(), redirect('/categories')
edit(int $id)    → entity, view('categories.edit')
update(int $id, UpdateCategoryRequest $request) → dto = $request->toDto(), service->update(), redirect('/categories')
destroy(int $id) → service->destroy(), redirect('/categories')
```

### 6.8 `PurchaseController`

```
__construct(
    PurchaseService $purchaseService,
    SupplierService $supplierService,
    LocationService $locationService,
    ProductService $productService
)
index()   → view('purchases.index')
create()  → loads suppliers + locations + products for dropdowns, view('purchases.create')
store(CreatePurchaseRequest $request) → dto = $request->toDto(), service->create(), redirect('/purchases')
show(int $id) → entity from service->findById(), view('purchases.show')
```

### 6.9 `SaleController`

```
__construct(
    SalesService $salesService,
    LocationService $locationService,
    ProductService $productService
)
index()   → view('sales.index')
create()  → loads locations + products for dropdowns, view('sales.create')
store(CreateSalesRequest $request) → dto = $request->toDto(), service->create(), redirect('/sales')
show(int $id) → entity from service->findById(), view('sales.show')
```

### 6.10 `StockController`

```
__construct(
    StockService $stockService,
    ProductService $productService,
    LocationService $locationService
)
index()              → view('stock.index') (embeds Volt stock-movements component)
transfer()           → loads products + locations for dropdowns, view('stock.transfer') (embeds Volt stock-transfer component)
storeTransfer(TransferProductRequest $request) → dto = $request->toDto(), service->transferProduct(), redirect('/stock')
```

---

## 7. Livewire Volt Components

All Livewire components use **Volt** (functional API, single `.blade.php` file, no separate PHP class). Volt is built into Livewire 3 — no extra package needed.

Each Volt file contains PHP logic at the top using `use function Livewire\Volt\{...}` and HTML below. Example pattern:

```php
<?php
use function Livewire\Volt\{state, computed, mount};

state(['search' => '', 'perPage' => 10]);

$items = computed(function () {
    return app(Service::class)->search($this->search, $this->perPage);
});

mount(fn () => '');

?>

<div>
    <!-- HTML here -->
</div>
```

### 7.1 Search Components (Index Pages)

Each search component handles search/filtering/pagination for an entity's index page:

```
State:
  $search = ''           (search query)
  $perPage = 10          (items per page)
  $sortField = 'id'     (sort column)
  $sortDirection = 'desc'

Computed:
  $items → calls service->search() or getPaginatedItems()

Actions:
  updatingSearch()       → resets page to 1
  sortBy($field)         → toggles sort direction
```

| Component                 | Service           | Search Fields                                                           | Table Columns                                                                          |
| ------------------------- | ----------------- | ----------------------------------------------------------------------- | -------------------------------------------------------------------------------------- |
| `search.supplier-search`  | `SupplierService` | name, email, phone                                                      | Code, Name, Email, Phone, Address, Actions                                             |
| `search.product-search`   | `ProductService`  | name_ar, name_en, brand, code, original_code, category_id, sale_unit_id | Code, Name AR, Brand, Category, Sale Unit, Retail Price, Wholesale Price, Qty, Actions |
| `search.sale-unit-search` | `SaleUnitService` | name_ar, name_en, code                                                  | Code, Name AR, Name EN, Actions                                                        |
| `search.location-search`  | `LocationService` | name, code, type                                                        | Code, Name, Address, Phone, Type, Actions                                              |
| `search.category-search`  | `CategoryService` | name_ar, name_en, code                                                  | Code, Name AR, Name EN, Actions                                                        |
| `search.purchase-search`  | `PurchaseService` | code, supplier_name                                                     | Code, Supplier, Store, Total, Discount, Tax, Grand Total, Date, Actions                |
| `search.sale-search`      | `SalesService`    | code, customer_name                                                     | Code, Customer, Store, Total, Discount, Tax, Grand Total, Date, Actions                |

Each search Volt component renders:

- Search input with `wire:model.live.debounce.300ms="search"`
- Sortable table headers
- Table rows with action links (Edit/View/Delete using `<x-ui-delete-button>`)
- Pagination links
- Empty state when no results

Service injection via `app(Service::class)` inside computed/action closures.

### 7.2 Stock Movements Component (`stock.stock-movements`)

Full-page Volt component for viewing all stock movements with filtering.

```
State:
  $search = ''
  $productId = null       (filter by product)
  $locationId = null      (filter by location)
  $type = null            (filter by StockMovementType enum: entry, sale, transfer_in, transfer_out, adjust_initial)
  $dateFrom = null        (filter by date range start)
  $dateTo = null          (filter by date range end)
  $perPage = 10
  $sortField = 'created_at'
  $sortDirection = 'desc'

Computed:
  $movements → builds StockMovementSearchDto from state, calls StockService->search()

Actions:
  resetFilters() → clears all filter state
  sortBy($field) → toggles sort direction
  updatingSearch() → resets page to 1
```

Renders:

- Filter bar: product select, location select, type select, date range inputs, reset button
- Table columns: Date, Product (via batch), Batch Code, Location, Quantity, Type (badge), Bill Number
- Pagination links
- Empty state when no results
- Type badges use color coding: ENTRY=success, SALE=danger, TRANSFER_IN=info, TRANSFER_OUT=warning, ADJUST_INITIAL=default

### 7.3 Stock Transfer Component (`stock.stock-transfer`)

Multi-step Volt component for transferring products between locations with preview.

```
State:
  $step = 1               (current step: 1=form, 2=preview)
  $productId = null
  $fromLocationId = null
  $toLocationId = null
  $quantity = 0
  $availableQuantity = 0  (computed from selected product + from location)

Computed:
  $products → ProductService::findAll() (for dropdown)
  $locations → LocationService::findAll() (for dropdown)
  $productBatches → batches available in fromLocation (after product + from selected)

Actions:
  updatedProductId()      → resets fromLocationId, toLocationId, availableQuantity
  updatedFromLocationId() → fetches available quantity for product at location
  goToPreview()           → validates all fields set, sets step = 2
  goBack()                → sets step = 1
  transfer()              → builds TransferProductDto, calls StockService->transferProduct(), redirects to /stock with flash
```

**Step 1 — Form:**

- Product select dropdown
- From Location select (filtered to locations where product has stock)
- To Location select (all locations except from location)
- Quantity input (with available quantity shown as hint, validated <= available)
- Available stock display: shows remaining_quantity for product at from location
- "Preview Transfer" button → validates, moves to step 2

**Step 2 — Preview:**

- Product name + code
- From Location → To Location (with arrow icon)
- Quantity being transferred
- Available stock at from location (before transfer)
- "Confirm Transfer" button → calls `transfer()` action
- "Back" button → returns to step 1

---

## 8. Reusable Blade Components Specification

### 8.1 Layout Components

#### `components/layout/app.blade.php`

- Wraps all pages
- Slots: `header`, `content`
- Includes sidebar + topbar
- Manages sidebar collapsed state via Alpine.js + `localStorage`
- Uses Tailwind `@media` breakpoints: `lg:` for desktop, `md:` for tablet, default for mobile

#### `components/layout/sidebar.blade.php`

- Navigation items with icons (Heroicons)
- Collapsible with smooth transition (Alpine.js)
- On mobile: overlay with backdrop, close on outside click
- On desktop: slide between full width (w-64) and icon-only (w-16)
- Active state highlight for current route
- Navigation groups:
    - **Dashboard** — `/`
    - **Inventory** — Products, Categories, Sale Units, Locations
    - **People** — Suppliers
    - **Transactions** — Purchases, Sales
    - **Stock** — Movements (`/stock`), Transfer (`/stock/transfer`)
- Sidebar state persisted in `localStorage` key `sidebar_collapsed`

#### `components/layout/topbar.blade.php`

- Mobile menu toggle (hamburger)
- Dark mode toggle (sun/moon icon button)
- Page title slot
- Dark mode toggle sets `localStorage` key `dark_mode`

#### `components/layout/breadcrumb.blade.php`

- Props: `$items` (array of `['label' => string, 'url' => ?string]`)
- Renders: Home > Section > Page
- Last item is non-clickable (current page)

### 8.2 UI Components

#### `components/ui/button.blade.php`

- Props: `$variant` (primary|secondary|danger|ghost|outline), `$size` (sm|md|lg), `$type` (button|submit)
- Default slot: button text

#### `components/ui/table.blade.php`

- Props: `$headers` (array of `['key' => string, 'label' => string, 'sortable' => bool]`)
- Default slot: table rows (`<tbody>`)
- Wrapped in `overflow-x-auto` for mobile horizontal scroll

#### `components/ui/card.blade.php`

- Props: `$title` (string), `$icon` (optional string)
- Slots: `content`, `footer`
- Used for dashboard stat cards

#### `components/ui/badge.blade.php`

- Props: `$variant` (success|warning|danger|info|default), `$text` (string)

#### `components/ui/alert.blade.php`

- Props: `$type` (success|error|warning|info), `$message` (string)
- Auto-dismiss after 5 seconds via Alpine `setTimeout`
- Reads from session flash messages (`session('success')`, `session('error')`, etc.)

#### `components/ui/input.blade.php`

- Props: `$name`, `$label`, `$type` (text|email|password|number|date|file), `$value`, `$placeholder`, `$required`, `$error`, `$disabled`, `$step`, `$min`, `$max`
- Renders: label + input + error message (conditional)

#### `components/ui/select.blade.php`

- Props: `$name`, `$label`, `$options` (array or Collection), `$valueKey`, `$labelKey`, `$selected`, `$error`, `$required`, `$placeholder`
- Renders: label + select + error message

#### `components/ui/empty-state.blade.php`

- Props: `$icon`, `$title`, `$description`
- Centered placeholder when no data

#### `components/ui/pagination.blade.php`

- Props: `$paginator` (LengthAwarePaginator)
- Previous/Next + page number links
- Responsive: shows fewer pages on mobile

#### `components/ui/delete-button.blade.php`

- Props: `$action` (form action URL), `$id` (entity ID)
- Renders: `<form method="POST" action="{{ $action }}">` with `@method('DELETE')` + `@csrf`
- Submit button with `onclick="return confirm('Are you sure?')"` JavaScript confirmation
- Red/danger styled button

#### `components/ui/back-button.blade.php`

- Props: `$url` (string)
- Simple back link button styled as secondary

### 8.3 Form Components

#### `components/form/form-group.blade.php`

- Wrapper: `<div class="mb-4">` + label + slot (input/select) + error
- Props: `$name`, `$label`, `$error`

#### `components/form/form-actions.blade.php`

- Submit + Cancel button row
- Props: `$cancelUrl`, `$submitLabel`
- Slots: `extra` (for additional buttons)

---

## 9. Views Specification

### 9.1 Suppliers

#### `suppliers/index.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home > Suppliers
- Page header: "Suppliers" + Create button (links to `/suppliers/create`)
- Embeds `<livewire:search.supplier-search />`

#### `suppliers/create.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home > Suppliers > Create
- `<form>` POST to `/suppliers` with `@csrf`
- Fields: name, email, phone, address (using `x-ui-input` components)
- Form actions: Save + Cancel

#### `suppliers/edit.blade.php`

- Same as create but PUT method, pre-filled values from entity
- Breadcrumb: Home > Suppliers > Edit

### 9.2 Products

#### `products/index.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home > Products
- Header: "Products" + Create button
- Embeds `<livewire:search.product-search />`

#### `products/create.blade.php`

- Fields: name_ar, name_en, brand, original_code, category_id (select), sale_unit_id (select), units_per_carton, origin, description, image (file), retail_price, wholesale_price
- Dropdowns loaded from CategoryService, SaleUnitService

#### `products/edit.blade.php`

- Same fields as create, pre-filled
- Additional section: price history table

#### `products/show.blade.php`

- Read-only display: all product details, current prices, stock by location, price history
- Actions: Edit button, Back button

### 9.3 Sale Units

#### `sale-units/index.blade.php`

- Embeds `<livewire:search.sale-unit-search />`

#### `sale-units/create.blade.php`

- Fields: name_ar, name_en

#### `sale-units/edit.blade.php`

- Same as create, pre-filled

### 9.4 Locations

#### `locations/index.blade.php`

- Embeds `<livewire:search.location-search />`

#### `locations/create.blade.php`

- Fields: name, address, phone, type (select: store|warehouse)

#### `locations/edit.blade.php`

- Same as create, pre-filled

### 9.5 Categories

#### `categories/index.blade.php`

- Embeds `<livewire:search.category-search />`

#### `categories/create.blade.php`

- Fields: name_ar, name_en

#### `categories/edit.blade.php`

- Same as create, pre-filled

### 9.6 Purchases

#### `purchases/index.blade.php`

- Embeds `<livewire:search.purchase-search />`
- No Create/Edit/Delete — only View links

#### `purchases/create.blade.php`

- Multi-step form with JavaScript-managed steps:

**Step 1 — Header:**

- Supplier select (from SupplierService)
- Store/Location select (from LocationService)
- Discount input
- Tax input

**Step 2 — Add Items:**

- Dynamic item rows added via JavaScript (Alpine.js)
- Each row: product select, quantity, price (auto-filled from wholesale), total (computed)
- Add row / remove row buttons
- Running subtotal computed via Alpine.js

**Step 3 — Preview:**

- Shows full purchase summary (header + items + totals)
- Back button returns to step 2
- Confirm button submits the form (POST to `/purchases`)

- Hidden `<form>` contains all final values submitted on confirm
- JavaScript `x-data` Alpine component manages step state, item rows, and calculations

#### `purchases/show.blade.php`

- Read-only: purchase header, supplier, store, all items, totals

### 9.7 Sales

#### `sales/index.blade.php`

- Embeds `<livewire:search.sale-search />`

#### `sales/create.blade.php`

- Multi-step form (same pattern as purchases):

**Step 1 — Header:**

- Customer name, customer phone
- Store/Location select
- Discount, Tax
- Type radio: Retail | Wholesale

**Step 2 — Add Items:**

- Dynamic rows: product select, quantity, price (auto-filled based on type), total
- Stock availability warning if quantity exceeds available
- Running totals computed via Alpine.js

**Step 3 — Preview:**

- Full sale summary
- Back to step 2
- Confirm submits POST to `/sales`

#### `sales/show.blade.php`

- Read-only: sale header, customer, store, all items, totals

### 9.8 Stock

#### `stock/index.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home > Stock Movements
- Page header: "Stock Movements" + "Transfer Product" button (links to `/stock/transfer`)
- Embeds `<livewire:stock.stock-movements />`

#### `stock/transfer.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home > Stock > Transfer
- Embeds `<livewire:stock.stock-transfer />` (full multi-step Volt component)

### 9.9 Dashboard

#### `dashboard.blade.php`

- Extends `layouts.app`
- Breadcrumb: Home
- 4 stat cards in a responsive grid (`grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4`):
    1. Total Sales Today (count + grand_total sum)
    2. Total Purchases Today (count + grand_total sum)
    3. Total Products (count)
    4. Low Stock Alerts (count of products below threshold)
- Recent Sales table (last 5) with link to view
- Recent Purchases table (last 5) with link to view
- All data passed from `DashboardController`

---

## 10. Dark Mode Implementation

- Tailwind config: `darkMode: 'class'` (apply `dark` class on `<html>`)
- Alpine.js in layout:
    ```
    x-data="{ dark: localStorage.getItem('dark_mode') === 'true' }"
    x-init="document.documentElement.classList.toggle('dark', dark)"
    x-effect="document.documentElement.classList.toggle('dark', dark); localStorage.setItem('dark_mode', dark)"
    ```
- Toggle button in topbar: sun icon (light) / moon icon (dark)
- All components use Tailwind `dark:` variants
- Color tokens:
    - Light: `bg-white`, `text-gray-900`, `bg-gray-50`
    - Dark: `dark:bg-gray-900`, `dark:text-gray-100`, `dark:bg-gray-800`
    - Sidebar: `bg-gray-800` / `dark:bg-gray-950`
    - Cards: `bg-white` / `dark:bg-gray-800`

---

## 11. Responsive Design Strategy

| Breakpoint            | Layout                                                                             |
| --------------------- | ---------------------------------------------------------------------------------- |
| `< 640px` (mobile)    | Sidebar hidden (overlay), single column, tables scroll horizontally, stacked forms |
| `640-1023px` (tablet) | Sidebar collapsed (icons only or overlay), 2-column forms                          |
| `>= 1024px` (desktop) | Sidebar full or collapsed (user preference), multi-column layouts                  |

- **Tables**: wrapped in `overflow-x-auto` container; secondary columns hidden on mobile via `hidden md:table-cell`
- **Forms**: `grid grid-cols-1 md:grid-cols-2 gap-4` for side-by-side fields
- **Dashboard cards**: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`
- **Navigation**: hamburger menu on mobile, standard sidebar on desktop

---

## 12. Sidebar Specification

- **Desktop** (>=1024px):
    - Full mode: 256px wide, icon + text for each nav item
    - Collapsed mode: 64px wide, icon only with tooltip on hover
    - Toggle button at bottom of sidebar
    - State saved in `localStorage` key `sidebar_collapsed`
- **Mobile** (<1024px):
    - Hidden by default
    - Hamburger button in topbar opens sidebar as overlay
    - Semi-transparent backdrop behind sidebar
    - Close on backdrop click or Escape key
    - Full width (256px) when open
- Active state: route matching highlights current nav item
- Icon library: Heroicons (SVG inline in component)

---

## 13. Flash Messages & Notifications

- Success/Error/Warning/Info flashed via `session()->flash('success', 'message')`
- Rendered by `components/ui/alert.blade.php` at top of content area
- Auto-dismiss after 5 seconds via Alpine `setTimeout`
- Sessions flashed in controller actions after store/update/destroy

---

## 14. Dashboard Additional Service Methods

New methods added to existing services (additions only, no modifications):

### `SalesService`

```
getTodaySalesCount(): int
getTodaySalesTotal(): float
getRecentSales(int $limit = 5): array
```

### `PurchaseService`

```
getTodayPurchasesCount(): int
getTodayPurchasesTotal(): float
getRecentPurchases(int $limit = 5): array
```

### `ProductService`

```
getLowStockProducts(int $threshold = 5): array
getTotalProductCount(): int
```

---

## 15. Form Validation Strategy

- **Reuse existing API FormRequests** — Web controllers inject the same request classes from `app/Interfaces/Http/Requests/` that the API controllers use (e.g., `CreateSupplierRequest`, `UpdateSupplierRequest`, `CreateProductRequest`, etc.)
- Each existing FormRequest already has `rules()` (using `App\Utils\ValidationRules`) and `toDto()` returning the appropriate DTO
- No new request classes needed — both API and Web layers share the same validation + DTO building logic
- Errors displayed inline via `components/ui/input.blade.php` and `components/ui/select.blade.php` using `$errors->has('field')`
- Old input preserved via `old('field')` in form components
- On validation failure: Laravel auto-redirects back to the form with errors + old input

---

## 16. Test Plan

### 16.1 Dashboard Tests

| Test                                          | What it verifies                 |
| --------------------------------------------- | -------------------------------- |
| `DashboardTest::test_dashboard_is_accessible` | GET `/` returns 200              |
| `DashboardTest::test_dashboard_shows_stats`   | Response contains stat card data |

### 16.2 CRUD Tests (per entity: Supplier, Product, SaleUnit, Location, Category)

Using Suppliers as example — same pattern applies to all:

| Test                                              | What it verifies                                                                 |
| ------------------------------------------------- | -------------------------------------------------------------------------------- |
| `SupplierCrudTest::test_index_page_rendered`      | GET `/suppliers` returns 200                                                     |
| `SupplierCrudTest::test_create_page_rendered`     | GET `/suppliers/create` returns 200                                              |
| `SupplierCrudTest::test_can_create_supplier`      | POST `/suppliers` with valid data, assert DB has record, redirected              |
| `SupplierCrudTest::test_create_validation_errors` | POST `/suppliers` with empty data, assert session has errors for required fields |
| `SupplierCrudTest::test_edit_page_rendered`       | GET `/suppliers/{id}/edit` returns 200 with entity data                          |
| `SupplierCrudTest::test_can_update_supplier`      | PUT `/suppliers/{id}` with valid data, assert DB updated, redirected             |
| `SupplierCrudTest::test_can_delete_supplier`      | DELETE `/suppliers/{id}`, assert DB record soft-deleted, redirected              |

#### Search Tests (per entity — Livewire component tests)

| Test                                                         | What it verifies                                               |
| ------------------------------------------------------------ | -------------------------------------------------------------- |
| `SupplierSearchTest::test_search_filters_results`            | Set `$search`, assert rendered HTML contains matching supplier |
| `SupplierSearchTest::test_search_returns_empty_for_no_match` | Set `$search` to gibberish, assert empty state shown           |
| `SupplierSearchTest::test_pagination_works`                  | Navigate to page 2, assert correct items shown                 |

### 16.3 Purchase Tests

| Test                                                          | What it verifies                                                             |
| ------------------------------------------------------------- | ---------------------------------------------------------------------------- |
| `PurchaseCrudTest::test_index_page_rendered`                  | GET `/purchases` returns 200                                                 |
| `PurchaseCrudTest::test_create_page_rendered`                 | GET `/purchases/create` returns 200 with supplier/location/product dropdowns |
| `PurchaseCrudTest::test_can_create_purchase_with_items`       | POST `/purchases` with header + items data, assert purchase + items in DB    |
| `PurchaseCrudTest::test_create_purchase_validation_errors`    | POST without items, assert validation error                                  |
| `PurchaseCrudTest::test_show_page_rendered`                   | GET `/purchases/{id}` returns 200 with purchase details                      |
| `PurchasePreviewTest::test_preview_validates_required_fields` | POST without supplier_id or store_id, assert errors                          |
| `PurchasePreviewTest::test_preview_calculates_totals`         | POST with items, assert grand_total = total - discount% + tax%               |

### 16.4 Sales Tests

| Test                                                      | What it verifies                                                |
| --------------------------------------------------------- | --------------------------------------------------------------- |
| `SaleCrudTest::test_index_page_rendered`                  | GET `/sales` returns 200                                        |
| `SaleCrudTest::test_create_page_rendered`                 | GET `/sales/create` returns 200 with location/product dropdowns |
| `SaleCrudTest::test_can_create_sale_with_items`           | POST `/sales` with header + items, assert sale + items in DB    |
| `SaleCrudTest::test_create_sale_validation_errors`        | POST without customer_name, assert error                        |
| `SaleCrudTest::test_show_page_rendered`                   | GET `/sales/{id}` returns 200                                   |
| `SalePreviewTest::test_preview_validates_required_fields` | POST without store_id or items, assert errors                   |
| `SalePreviewTest::test_preview_calculates_grand_total`    | POST with items, assert grand_total calculation                 |

### 16.5 Stock Tests

| Test                                                            | What it verifies                                                                  |
| --------------------------------------------------------------- | --------------------------------------------------------------------------------- |
| `StockMovementsTest::test_stock_index_page_rendered`            | GET `/stock` returns 200                                                          |
| `StockMovementsTest::test_stock_movements_shows_data`           | Page contains stock movement entries                                              |
| `StockTransferTest::test_transfer_page_rendered`                | GET `/stock/transfer` returns 200 with product/location dropdowns                 |
| `StockTransferTest::test_can_transfer_product`                  | POST `/stock/transfer` with valid data, assert transfer created in DB, redirected |
| `StockTransferTest::test_transfer_validation_errors`            | POST without required fields, assert validation errors                            |
| `StockTransferTest::test_transfer_requires_different_locations` | POST with same from/to location, assert validation error                          |
| `StockTransferTest::test_transfer_quantity_exceeds_stock`       | POST with quantity > available stock, assert validation error                     |

### 16.6 Livewire Volt Unit Tests

| Test                                                     | What it verifies                                              |
| -------------------------------------------------------- | ------------------------------------------------------------- |
| `SupplierSearchTest::test_sorting_toggles_direction`     | Call `sortBy('name')`, assert sort direction toggled          |
| `SupplierSearchTest::test_updating_search_resets_page`   | Set search, assert page reset to 1                            |
| `ProductSearchTest::test_filters_by_category`            | Set category filter, assert results filtered                  |
| `ProductSearchTest::test_filters_by_sale_unit`           | Set sale unit filter, assert results filtered                 |
| `PurchaseSearchTest::test_sorting_by_date`               | Call `sortBy('created_at')`, assert correct order             |
| `StockMovementsTest::test_filters_by_type`               | Set type filter, assert only matching movements shown         |
| `StockMovementsTest::test_filters_by_location`           | Set location filter, assert results filtered                  |
| `StockMovementsTest::test_filters_by_date_range`         | Set dateFrom + dateTo, assert results within range            |
| `StockMovementsTest::test_reset_clears_filters`          | Call resetFilters(), assert all filters cleared               |
| `StockTransferTest::test_step_advances_to_preview`       | Fill valid form, call goToPreview(), assert step = 2          |
| `StockTransferTest::test_step_returns_to_form`           | Call goBack(), assert step = 1                                |
| `StockTransferTest::test_product_change_resets_location` | Change productId, assert fromLocationId reset                 |
| `StockTransferTest::test_fetches_available_quantity`     | Set product + from location, assert availableQuantity fetched |

---

## 17. Execution Order (Implementation Phases)

### Phase 1: Foundation

1. Install Livewire
2. Set up Tailwind dark mode (`darkMode: 'class'`)
3. Create `routes/web.php` with all routes
4. Create `components/layout/` components (app shell, sidebar, topbar, breadcrumb)
5. Create `components/ui/` components (button, input, select, table, card, badge, alert, empty-state, pagination, delete-button, back-button)
6. Create `components/form/` components (form-group, form-actions)
7. Add dark mode Alpine.js logic to layout
8. Add sidebar collapse Alpine.js logic to layout
9. Create `layouts/app.blade.php`

### Phase 2: Dashboard

10. Add dashboard stat methods to services
11. Create `DashboardController`
12. Create `dashboard.blade.php` view

### Phase 3: Simple CRUD Entities

13. Categories: Controller + Views (index/create/edit) + Livewire Search component
14. Sale Units: Controller + Views (index/create/edit) + Livewire Search component
15. Locations: Controller + Views (index/create/edit) + Livewire Search component
16. Suppliers: Controller + Views (index/create/edit) + Livewire Search component

### Phase 4: Products CRUD

17. Products: Controller + Views (index/create/edit/show) + Livewire Search component
18. Product price history in show view

### Phase 5: Transactions

19. Purchases: Controller + Views (index/create/show) + Volt Search component
20. Sales: Controller + Views (index/create/show) + Volt Search component

### Phase 6: Stock

21. StockController + stock/index.blade.php + Volt stock-movements component
22. Stock transfer: stock/transfer.blade.php + Volt stock-transfer component (multi-step with preview)

### Phase 7: Testing

23. Dashboard tests
24. Category CRUD + search tests
25. SaleUnit CRUD + search tests
26. Location CRUD + search tests
27. Supplier CRUD + search tests
28. Product CRUD + search tests
29. Purchase CRUD + preview tests
30. Sale CRUD + preview tests
31. Stock movements + transfer tests
32. Volt unit tests (search + stock)

---

## 18. Key Constraints

- **No code duplication**: All shared UI patterns must be Blade components. All shared logic in traits or base classes.
- **Follow existing patterns**: Use same DTOs, entities, mappers, services that the API layer uses.
- **Validation**: Reuse `App\Utils\ValidationRules` for all form validation if it's valid.
- **Livewire Volt (functional API)**: All Livewire components are single `.blade.php` files using Volt's functional API. No separate PHP class files. Search components in `resources/views/livewire/search/`, stock components in `resources/views/livewire/stock/`.
- **Traditional CRUD**: Create/Edit/Delete use standard Blade forms, controllers, and POST/PUT/DELETE routes — not Livewire.
- **No auth**: No middleware, no login/register. Will be added later.
- **Volt functional syntax**: Use `use function Livewire\Volt\{state, computed, action, mount}`, `wire:model.live.debounce.300ms` for search input.
- **Alpine.js only** for non-Livewire client-side interactivity (bundled with Livewire). No React/Vue.
- **All new code in existing architecture layers**: Web controllers in `app/Interfaces/Http/Controllers/Web/`, Volt components in `resources/views/livewire/`.
- **Reuse existing API FormRequests**: Web controllers use the same request classes (`CreateSupplierRequest`, `UpdateSupplierRequest`, etc.) from `app/Interfaces/Http/Requests/` — no duplicate validation logic.
- **Existing API untouched**: `routes/api.php` and all API controllers remain unchanged.
