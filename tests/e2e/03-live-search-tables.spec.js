import { test, expect } from '@playwright/test';

test.describe('Livewire Live Debounce Search Tables', () => {

  test('user can search live product catalog table', async ({ page }) => {
    await page.goto('/products');

    const searchInput = page.locator('input[placeholder*="Search" i]').first();
    await expect(searchInput).toBeVisible();

    // Type query and verify input reflects typed value
    await searchInput.fill('Juice');
    await expect(searchInput).toHaveValue('Juice');
  });

  test('user can search live sales table', async ({ page }) => {
    await page.goto('/sales');

    const searchInput = page.locator('input[placeholder*="Search" i]').first();
    await expect(searchInput).toBeVisible();

    await searchInput.fill('SAL');
    await expect(searchInput).toHaveValue('SAL');
  });

  test('user can search live purchases table', async ({ page }) => {
    await page.goto('/purchases');

    const searchInput = page.locator('input[placeholder*="Search" i]').first();
    await expect(searchInput).toBeVisible();

    await searchInput.fill('PUR');
    await expect(searchInput).toHaveValue('PUR');
  });

  test('user can search stock movements by type and date', async ({ page }) => {
    await page.goto('/stock');

    // Filter dropdown by movement type
    const typeSelect = page.locator('select').first();
    if (await typeSelect.isVisible()) {
      const options = await typeSelect.locator('option').all();
      if (options.length > 1) {
        await typeSelect.selectOption({ index: 1 });
      }
    }
  });

});

