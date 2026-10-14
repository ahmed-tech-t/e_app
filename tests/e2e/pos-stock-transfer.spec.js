import { test, expect } from '@playwright/test';

test.describe('POS Stock Transfer Real Browser E2E Flow', () => {

  test('user visits stock transfer wizard and sees interactive controls', async ({ page }) => {
    await page.goto('/stock/transfer');

    // Verify wizard page loaded
    await expect(page).toHaveURL(/\/stock\/transfer/);

    // Verify dropdowns and inputs rendered in real DOM
    const selects = page.locator('select');
    await expect(selects.first()).toBeVisible();

    const quantityInput = page.locator('input[type="number"]');
    await expect(quantityInput).toBeVisible();

    // Verify initial quantity is set (default 1)
    await expect(quantityInput).toHaveValue('1');
  });

  test('user can adjust quantity input in real browser', async ({ page }) => {
    await page.goto('/stock/transfer');

    const quantityInput = page.locator('input[type="number"]');
    await quantityInput.fill('5');
    await expect(quantityInput).toHaveValue('5');
  });

});
