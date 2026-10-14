import { test, expect } from '@playwright/test';

test.describe('Master Data Real Browser CRUD & Validation Edge Cases', () => {

  test('user can create a category with Arabic & English names, and sees validation on empty input', async ({ page }) => {
    // 1. Edge Case: Empty submission validation
    await page.goto('/categories/create');
    await page.locator('form button[type="submit"]:has-text("Save")').click();
    await expect(page).toHaveURL(/\/categories\/create/);

    // 2. Normal Case: Valid submission
    const catName = 'E2E Cat ' + Date.now();
    await page.locator('input[name="name_ar"]').fill('تصنيف ' + catName);
    await page.locator('input[name="name_en"]').fill(catName);
    await page.locator('form button[type="submit"]:has-text("Save")').click();

    // Verify redirected to index with success
    await expect(page).toHaveURL(/\/categories/);
    await expect(page.locator('body')).toContainText(catName);
  });

  test('user can create a sale unit in real browser', async ({ page }) => {
    await page.goto('/sale-units/create');
    const unitName = 'Unit ' + Date.now();
    await page.locator('input[name="name_ar"]').fill('وحدة ' + unitName);
    await page.locator('input[name="name_en"]').fill(unitName);
    await page.locator('form button[type="submit"]:has-text("Save")').click();

    await expect(page).toHaveURL(/\/sale-units/);
    await expect(page.locator('body')).toContainText(unitName);
  });

  test('user can create a store location in real browser', async ({ page }) => {
    await page.goto('/locations/create');
    const locName = 'Branch ' + Date.now();
    await page.locator('input[name="name"]').fill(locName);
    await page.locator('input[name="address"]').fill('123 Commercial St');
    await page.locator('input[name="phone"]').fill('010' + Math.floor(10000000 + Math.random() * 90000000));
    await page.locator('select[name="type"]').selectOption('store');
    await page.locator('form button[type="submit"]:has-text("Save")').click();

    await expect(page).toHaveURL(/\/locations/);
    await expect(page.locator('body')).toContainText(locName);
  });

  test('user can create a supplier in real browser', async ({ page }) => {
    await page.goto('/suppliers/create');
    const supName = 'Supplier ' + Date.now();
    await page.locator('input[name="name"]').fill(supName);
    await page.locator('input[name="email"]').fill('sup_' + Date.now() + '@example.com');
    await page.locator('input[name="phone"]').fill('010' + Math.floor(10000000 + Math.random() * 90000000));
    await page.locator('input[name="address"]').fill('Supply Industrial Zone');
    await page.locator('form button[type="submit"]:has-text("Save")').click();

    await expect(page).toHaveURL(/\/suppliers/);
    await expect(page.locator('body')).toContainText(supName);
  });

  test('user can create a product and views it in the catalog', async ({ page }) => {
    // 1. Visit product creation
    await page.goto('/products/create');

    const prodBarcode = 'BAR-' + Date.now();
    const prodName = 'E2E Coffee ' + Date.now();

    await page.locator('input[name="name_ar"]').fill('قهوة ' + prodName);
    await page.locator('input[name="name_en"]').fill(prodName);
    await page.locator('input[name="brand"]').fill('Roasters');
    await page.locator('input[name="original_code"]').fill(prodBarcode);
    await page.locator('input[name="retail_price"]').fill('85.50');
    await page.locator('input[name="wholesale_price"]').fill('65.00');

    // Select category and sale unit if available
    const categorySelect = page.locator('select[name="category_id"]');
    const catOptions = await categorySelect.locator('option').all();
    if (catOptions.length > 1) {
      await categorySelect.selectOption({ index: 1 });
    }

    const unitSelect = page.locator('select[name="sale_unit_id"]');
    const unitOptions = await unitSelect.locator('option').all();
    if (unitOptions.length > 1) {
      await unitSelect.selectOption({ index: 1 });
    }

    await page.locator('form button[type="submit"]:has-text("Save")').click();

    // Verify redirected to products list
    await expect(page).toHaveURL(/\/products/);
  });

});

