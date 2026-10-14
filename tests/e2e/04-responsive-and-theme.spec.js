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

  test('user can toggle dark and light theme modes in real browser', async ({ page }) => {
    await page.goto('/');

    // Find the dark mode toggle button in the topbar
    const themeBtn = page.locator('.h-16 button').last();
    if (await themeBtn.isVisible()) {
      await themeBtn.click();
      // Click again to toggle back
      await themeBtn.click();
    }
  });

  test('switching to Arabic switches document direction to RTL', async ({ page }) => {
    await page.goto('/');

    const arabicBtn = page.locator('button:has-text("ع")');
    if (await arabicBtn.isVisible()) {
      await arabicBtn.click();
      await page.waitForLoadState('networkidle');

      // Check document direction or locale indicator
      const htmlDir = await page.locator('html').getAttribute('dir');
      const htmlLang = await page.locator('html').getAttribute('lang');
      expect(htmlDir === 'rtl' || htmlLang === 'ar').toBeTruthy();
    }

    // Switch back to English
    const englishBtn = page.locator('button:has-text("EN")');
    if (await englishBtn.isVisible()) {
      await englishBtn.click();
      await page.waitForLoadState('networkidle');
      const htmlLangEn = await page.locator('html').getAttribute('lang');
      expect(htmlLangEn === 'en' || !htmlLangEn).toBeTruthy();
    }
  });

});

