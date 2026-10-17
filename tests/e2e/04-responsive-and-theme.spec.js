import { test, expect } from '@playwright/test';

test.describe('Responsive Viewports, Dark Mode & RTL Locale Edge Cases', () => {

  test('mobile viewport renders responsive sidebar toggle', async ({ page }) => {
    // Set viewport to iPhone / small mobile screen
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');

    // Hamburger button should be visible in topbar on mobile
    const hamburgerBtn = page.locator('button[\\@click="sidebarOpen = !sidebarOpen"]');
    await expect(hamburgerBtn).toBeVisible();

    // Click hamburger to open sidebar
    await hamburgerBtn.click();
    await expect(page.locator('aside')).toHaveClass(/translate-x-0/);
  });

  test('user can toggle dark and light theme modes without flashing on reload or new tabs', async ({ page }) => {
    await page.goto('/');

    // Find the dark mode toggle button in the topbar
    const themeBtn = page.locator('button[aria-label="Toggle theme"]');
    await expect(themeBtn).toBeVisible();

    // In initial light mode, moon icon is visible, sun icon is hidden
    await expect(page.locator('.theme-moon-icon')).toBeVisible();
    await expect(page.locator('.theme-sun-icon')).not.toBeVisible();

    // 1. Click to turn dark mode ON
    await themeBtn.click();
    await expect(page.locator('html')).toHaveClass(/dark/);
    const storedDark = await page.evaluate(() => localStorage.getItem('dark_mode'));
    expect(storedDark).toBe('true');

    // Sun icon visible, moon icon hidden
    await expect(page.locator('.theme-sun-icon')).toBeVisible();
    await expect(page.locator('.theme-moon-icon')).not.toBeVisible();

    // 2. Navigate via sidebar to another tab/screen (e.g. Products)
    const productsLink = page.locator('aside a[href="/products"]');
    await productsLink.click();
    await page.waitForLoadState('networkidle');

    // Verify dark mode persisted without flashing back to light
    await expect(page.locator('html')).toHaveClass(/dark/);
    await expect(page.locator('.theme-sun-icon')).toBeVisible();
    await expect(page.locator('.theme-moon-icon')).not.toBeVisible();

    // 3. Reload page - verify html already has 'dark' class immediately without white flash
    await page.reload();
    await expect(page.locator('html')).toHaveClass(/dark/);
    await expect(page.locator('.theme-sun-icon')).toBeVisible();

    // 4. Navigate back to dashboard via sidebar
    const dashboardLink = page.locator('aside a[href="/"]');
    await dashboardLink.click();
    await page.waitForLoadState('networkidle');
    await expect(page.locator('html')).toHaveClass(/dark/);

    // 5. Simulate another tab switching theme to light via storage event
    await page.evaluate(() => {
      window.dispatchEvent(new StorageEvent('storage', { key: 'dark_mode', newValue: 'false' }));
    });
    await expect(page.locator('html')).not.toHaveClass(/dark/);

    // 6. Toggle back to dark via button
    await themeBtn.click();
    await expect(page.locator('html')).toHaveClass(/dark/);

    // Clean up to light mode
    await themeBtn.click();
    await expect(page.locator('html')).not.toHaveClass(/dark/);
  });

  test('switching to Arabic switches document direction to RTL and translates UI elements', async ({ page }) => {
    await page.goto('/');

    // 1. Switch to Arabic
    const arabicBtn = page.locator('button:has-text("ع")');
    if (await arabicBtn.isVisible()) {
      await arabicBtn.click();
      await page.waitForLoadState('networkidle');

      // Check document direction and html lang
      await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
      await expect(page.locator('html')).toHaveAttribute('lang', 'ar');

      // Assert UI sidebar text is translated to Arabic
      const sidebar = page.locator('aside');
      await expect(sidebar).toContainText('لوحة التحكم');
      await expect(sidebar).toContainText('المنتجات');
      await expect(sidebar).toContainText('الفئات');
      await expect(sidebar).toContainText('المبيعات');
      await expect(sidebar).toContainText('المشتريات');
      await expect(sidebar).toContainText('الموردون');
      await expect(sidebar).toContainText('حركات المخزون');
      await expect(sidebar).toContainText('المخزون');

      // Assert Dashboard page content is translated to Arabic
      const mainContent = page.locator('main');
      await expect(mainContent).toContainText('مبيعات اليوم');
      await expect(mainContent).toContainText('مشتريات اليوم');
      await expect(mainContent).toContainText('إجمالي المنتجات');
      await expect(mainContent).toContainText('مرتجعات مبيعات اليوم');
      await expect(mainContent).toContainText('مرتجعات مشتريات اليوم');
      await expect(mainContent).toContainText('أحدث المبيعات');
      await expect(mainContent).toContainText('أحدث المشتريات');
      await expect(page.locator('nav[aria-label="Breadcrumb"]')).toContainText('الرئيسية');

      // Assert products page localized in Arabic including bulk actions 'المزيد'
      await page.goto('/products');
      await page.waitForLoadState('networkidle');
      await expect(page.locator('button:has-text("المزيد")')).toBeVisible();
    }

    // 2. Switch back to English
    const englishBtn = page.locator('button:has-text("EN")');
    if (await englishBtn.isVisible()) {
      await englishBtn.click();
      await page.waitForLoadState('networkidle');
      await page.goto('/');
      await page.waitForLoadState('networkidle');

      // Check document direction and html lang
      await expect(page.locator('html')).toHaveAttribute('dir', 'ltr');
      await expect(page.locator('html')).toHaveAttribute('lang', 'en');

      // Assert UI text is translated to English
      const sidebar = page.locator('aside');
      await expect(sidebar).toContainText('Dashboard');
      await expect(sidebar).toContainText('Products');
      await expect(sidebar).toContainText('Categories');
      await expect(sidebar).toContainText('Sales');
      await expect(sidebar).toContainText('Purchases');
      await expect(sidebar).toContainText('Suppliers');
      await expect(sidebar).toContainText('Movements');
      await expect(sidebar).toContainText('Inventory');

      // Assert Dashboard page content is translated to English
      const mainContent = page.locator('main');
      await expect(mainContent).toContainText('Sales Today');
      await expect(mainContent).toContainText('Purchases Today');
      await expect(mainContent).toContainText('Total Products');
      await expect(mainContent).toContainText('Sales Returns Today');
      await expect(mainContent).toContainText('Purchase Returns Today');
      await expect(mainContent).toContainText('Recent Sales');
      await expect(mainContent).toContainText('Recent Purchases');
      await expect(page.locator('nav[aria-label="Breadcrumb"]')).toContainText('Home');
    }
  });

  test('sidebar location moves between left and right screen edges when language switches', async ({ page }) => {
    // Desktop viewport where sidebar is visible
    await page.setViewportSize({ width: 1280, height: 800 });
    await page.goto('/');

    // 1. Ensure English (LTR) first
    const englishBtn = page.locator('button:has-text("EN")');
    if (await englishBtn.isVisible()) {
      await englishBtn.click();
      await page.waitForLoadState('networkidle');
    }

    // Measure sidebar bounding box in LTR
    const aside = page.locator('aside');
    await expect(aside).toBeVisible();
    let ltrBox = await aside.boundingBox();
    expect(ltrBox).not.toBeNull();
    // In LTR, sidebar is on the left edge (x = 0)
    expect(ltrBox.x).toBe(0);

    // 2. Switch to Arabic (RTL)
    const arabicBtn = page.locator('button:has-text("ع")');
    await expect(arabicBtn).toBeVisible();
    await arabicBtn.click();
    await page.waitForLoadState('networkidle');

    // Document must be RTL
    await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');

    // Measure sidebar bounding box in RTL
    let rtlBox = await aside.boundingBox();
    expect(rtlBox).not.toBeNull();
    // In RTL (1280px wide), sidebar is on the right edge: x + width = 1280
    const rightEdgeDistance = 1280 - (rtlBox.x + rtlBox.width);
    expect(Math.abs(rightEdgeDistance)).toBeLessThanOrEqual(2);

    // 3. Switch back to English (vice versa: right to left)
    await page.locator('button:has-text("EN")').click();
    await page.waitForLoadState('networkidle');

    await expect(page.locator('html')).toHaveAttribute('dir', 'ltr');
    ltrBox = await aside.boundingBox();
    expect(ltrBox).not.toBeNull();
    expect(ltrBox.x).toBe(0);
  });

  test('sidebar collapse button arrow points inward toward screen in Arabic RTL and English LTR', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });
    await page.goto('/');

    const collapseButton = page.locator('aside div.border-t button');

    // In English (LTR), sidebar is on left; expanded arrow path points left
    await page.locator('button:has-text("EN")').click();
    await page.waitForLoadState('networkidle');
    const ltrPath = collapseButton.locator('path[x-show="!sidebarCollapsed"]');
    await expect(ltrPath).toHaveAttribute('d', 'M11 19l-7-7 7-7m8 14l-7-7 7-7');

    // In Arabic (RTL), sidebar is on right; expanded arrow path points right (into the right edge to collapse)
    await page.locator('button:has-text("ع")').click();
    await page.waitForLoadState('networkidle');
    await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');

    const rtlPath = collapseButton.locator('path[x-show="!sidebarCollapsed"]');
    await expect(rtlPath).toHaveAttribute('d', 'M13 5l7 7-7 7M5 5l7 7-7 7');

    // Click collapse button in Arabic and verify state toggle
    await collapseButton.click();
    await expect(page.locator('aside')).toHaveCSS('width', '64px'); // 4rem collapsed width

    // Collapsed arrow in Arabic points left (outward to expand)
    const rtlCollapsedPath = collapseButton.locator('path[x-show="sidebarCollapsed"]');
    await expect(rtlCollapsedPath).toHaveAttribute('d', 'M11 19l-7-7 7-7m8 14l-7-7 7-7');

    // Click again to expand
    await collapseButton.click();
    await expect(page.locator('aside')).toHaveCSS('width', '256px'); // 16rem expanded width
  });

  test('mobile Arabic RTL viewport completely hides sidebar off-screen when closed and opens full drawer without overlapping', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');

    // Switch to Arabic
    const arabicBtn = page.locator('button:has-text("ع")');
    if (await arabicBtn.isVisible()) {
      await arabicBtn.click();
      await page.waitForLoadState('networkidle');
    }

    await page.goto('/sale-units');
    await page.waitForLoadState('networkidle');

    const aside = page.locator('aside');
    // Verify sidebar is completely off-screen to the right in Arabic RTL when closed
    const boxClosed = await aside.boundingBox();
    if (boxClosed) {
      // Either x is off-screen to the right (>= 375) or it has translate-x-full
      expect(boxClosed.x).toBeGreaterThanOrEqual(370);
    }

    // Main content must be clearly visible and not obscured
    const mainHeading = page.locator('main h2');
    await expect(mainHeading).toBeVisible();
    await expect(mainHeading).toContainText('وحدات البيع');

    // Click hamburger to open drawer
    const hamburgerBtn = page.locator('button[\\@click="sidebarOpen = !sidebarOpen"]');
    await hamburgerBtn.click();

    // Drawer opens: aside should now be on screen at the right edge
    await expect(aside).toHaveClass(/translate-x-0/);
    const boxOpened = await aside.boundingBox();
    expect(boxOpened).not.toBeNull();
    // In mobile, drawer width should be full width (256px), not collapsed 64px!
    expect(boxOpened.width).toBeGreaterThanOrEqual(240);

    // Backdrop should be visible
    const backdrop = page.locator('div[x-show="sidebarOpen"].fixed');
    await expect(backdrop).toBeVisible();

    // Click backdrop to close
    await backdrop.click({ position: { x: 10, y: 10 } });
    await expect(aside).not.toHaveClass(/!translate-x-0/);
  });

  test('mobile viewport with prior sidebar_collapsed in localStorage never renders 4rem icon strip or overlaps content', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });

    // Pre-seed localStorage with sidebar_collapsed = true (from desktop session)
    await page.addInitScript(() => {
      localStorage.setItem('sidebar_collapsed', 'true');
    });

    await page.goto('/sale-units');
    await page.waitForLoadState('networkidle');

    const aside = page.locator('aside');
    // When closed, sidebar must NOT be visible on screen
    const box = await aside.boundingBox();
    if (box) {
      // Must be off-screen
      const isOffScreen = box.x >= 370 || (box.x + box.width) <= 5;
      expect(isOffScreen).toBe(true);
    }

    // Open drawer
    const hamburgerBtn = page.locator('button[\\@click="sidebarOpen = !sidebarOpen"]');
    await hamburgerBtn.click();

    // On mobile, even with sidebar_collapsed=true in storage, width must be full drawer (256px), NOT 64px (4rem)!
    const boxOpen = await aside.boundingBox();
    expect(boxOpen).not.toBeNull();
    expect(boxOpen.width).toBeGreaterThanOrEqual(240);
  });

  test('every primary screen renders without horizontal overflow on mobile viewport (375x667)', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });

    const routes = [
      '/',
      '/products',
      '/products/create',
      '/categories',
      '/categories/create',
      '/sale-units',
      '/sale-units/create',
      '/locations',
      '/locations/create',
      '/suppliers',
      '/suppliers/create',
      '/sales',
      '/sales/create',
      '/purchases',
      '/purchases/create',
      '/sales-returns',
      '/purchase-returns',
      '/stock',
      '/stock/transfer',
    ];

    for (const route of routes) {
      await page.goto(route);
      await page.waitForLoadState('networkidle');

      // Topbar hamburger must be visible on mobile
      const hamburger = page.locator('button[\\@click="sidebarOpen = !sidebarOpen"]');
      await expect(hamburger).toBeVisible();

      // Check document does not suffer from horizontal page-level overflow
      const scrollWidth = await page.evaluate(() => document.documentElement.scrollWidth);
      const clientWidth = await page.evaluate(() => document.documentElement.clientWidth);
      expect(scrollWidth).toBeLessThanOrEqual(clientWidth + 2);
    }
  });

  test('mobile slide bar drawer opens and closes via hamburger, backdrop, close button, and escape key', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');

    const hamburger = page.locator('button[\\@click="sidebarOpen = !sidebarOpen"]');
    const aside = page.locator('aside');
    const backdrop = page.locator('div[x-show="sidebarOpen"]');
    const closeBtn = page.locator('aside button[\\@click="sidebarOpen = false"]');

    // 1. Initial state: drawer hidden
    let isHidden = await page.evaluate(() => {
      const asideEl = document.querySelector('aside');
      const box = asideEl.getBoundingClientRect();
      return box.right <= 0 || box.left >= window.innerWidth;
    });
    expect(isHidden).toBe(true);

    // 2. Open via hamburger
    await hamburger.click();
    await expect(aside).toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    let isOpen = await page.evaluate(() => {
      const asideEl = document.querySelector('aside');
      const box = asideEl.getBoundingClientRect();
      return box.left >= 0 && box.right <= window.innerWidth;
    });
    expect(isOpen).toBe(true);
    await expect(backdrop).toBeVisible();

    // 3. Close via backdrop click (click empty area outside drawer)
    const isRtl = await page.evaluate(() => document.documentElement.dir === 'rtl');
    await backdrop.click({ position: isRtl ? { x: 20, y: 300 } : { x: 350, y: 300 } });
    await expect(aside).not.toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    isHidden = await page.evaluate(() => {
      const asideEl = document.querySelector('aside');
      const box = asideEl.getBoundingClientRect();
      return box.right <= 0 || box.left >= window.innerWidth;
    });
    expect(isHidden).toBe(true);

    // 4. Reopen and close via close button
    await hamburger.click();
    await expect(aside).toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    await closeBtn.click();
    await expect(aside).not.toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    isHidden = await page.evaluate(() => {
      const asideEl = document.querySelector('aside');
      const box = asideEl.getBoundingClientRect();
      return box.right <= 0 || box.left >= window.innerWidth;
    });
    expect(isHidden).toBe(true);

    // 5. Reopen and close via Escape key
    await hamburger.click();
    await expect(aside).toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    await page.keyboard.press('Escape');
    await expect(aside).not.toHaveClass(/!translate-x-0/);
    await page.waitForTimeout(350);
    isHidden = await page.evaluate(() => {
      const asideEl = document.querySelector('aside');
      const box = asideEl.getBoundingClientRect();
      return box.right <= 0 || box.left >= window.innerWidth;
    });
    expect(isHidden).toBe(true);
  });

  test('mobile POS sales and purchase wizards operate cleanly without horizontal scroll on small screen', async ({ page }) => {
    await page.setViewportSize({ width: 360, height: 640 });

    // Test sales creation wizard
    await page.goto('/sales/create');
    await page.waitForLoadState('networkidle');

    let scrollWidth = await page.evaluate(() => document.documentElement.scrollWidth);
    let clientWidth = await page.evaluate(() => document.documentElement.clientWidth);
    expect(scrollWidth).toBeLessThanOrEqual(clientWidth + 2);

    // Test purchases creation wizard
    await page.goto('/purchases/create');
    await page.waitForLoadState('networkidle');

    scrollWidth = await page.evaluate(() => document.documentElement.scrollWidth);
    clientWidth = await page.evaluate(() => document.documentElement.clientWidth);
    expect(scrollWidth).toBeLessThanOrEqual(clientWidth + 2);

    // Test stock transfer wizard
    await page.goto('/stock/transfer');
    await page.waitForLoadState('networkidle');

    scrollWidth = await page.evaluate(() => document.documentElement.scrollWidth);
    clientWidth = await page.evaluate(() => document.documentElement.clientWidth);
    expect(scrollWidth).toBeLessThanOrEqual(clientWidth + 2);
  });

});


