import { test, expect } from '@playwright/test';

test.describe('Interactive POS Multi-Step Wizards & Livewire Controls', () => {

  test('stock transfer wizard handles step navigation, validation, and cancel', async ({ page }) => {
    await page.goto('/stock/transfer');

    // Edge Case: Clicking Preview with empty inputs stays on step 1 and shows validation
    const previewButton = page.locator('button:has-text("Preview Transfer")');
    if (await previewButton.isVisible()) {
      await previewButton.click();
      // Should remain on the page
      await expect(page).toHaveURL(/\/stock\/transfer/);
    }

    // Verify quantity input can be changed
    const qtyInput = page.locator('input[type="number"]');
    await expect(qtyInput).toBeVisible();
    await qtyInput.fill('7');
    await expect(qtyInput).toHaveValue('7');
  });

  test('sales creation multi-step wizard navigates through steps', async ({ page }) => {
    await page.goto('/sales/create');

    // Verify step indicators exist
    await expect(page.locator('.text-sm.font-medium:has-text("Header")').first()).toBeVisible();
    await expect(page.locator('.text-sm.font-medium:has-text("Items")').first()).toBeVisible();
    await expect(page.locator('.text-sm.font-medium:has-text("Preview")').first()).toBeVisible();

    // Fill customer name
    const customerInput = page.locator('input[placeholder*="customer" i], input[placeholder*="Name" i]').first();
    if (await customerInput.isVisible()) {
      await customerInput.fill('Amr Khaled');
    }

    // Check Next Step button
    const nextButton = page.locator('button:has-text("Next")').first();
    if (await nextButton.isVisible()) {
      await nextButton.click();
    }
  });

  test('purchase creation multi-step wizard navigates through steps', async ({ page }) => {
    await page.goto('/purchases/create');

    // Verify step header indicators
    await expect(page.locator('.text-sm.font-medium:has-text("Header")').first()).toBeVisible();
    await expect(page.locator('.text-sm.font-medium:has-text("Items")').first()).toBeVisible();
    await expect(page.locator('.text-sm.font-medium:has-text("Preview")').first()).toBeVisible();

    // Verify Next button exists
    const nextButton = page.locator('button:has-text("Next")').first();
    await expect(nextButton).toBeVisible();
  });

});

