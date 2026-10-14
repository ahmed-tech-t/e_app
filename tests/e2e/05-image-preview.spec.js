import { test, expect } from '@playwright/test';

test.describe('Image Preview & Fallback Verification Across App Pages', () => {

  test('products catalog renders all images with valid natural dimensions or clean fallback', async ({ page }) => {
    await page.goto('/products');

    // Wait for product table to load
    const productTable = page.locator('table.divide-y');
    await expect(productTable).toBeVisible();

    // Verify all images rendered in the table
    const images = productTable.locator('img');
    const count = await images.count();
    expect(count).toBeGreaterThan(0);

    for (let i = 0; i < count; i++) {
      const img = images.nth(i);
      await expect(img).toBeVisible();

      // Check image loaded properly in DOM (complete and naturalWidth > 0)
      const isLoaded = await img.evaluate((el) => {
        return el.complete && el.naturalWidth > 0;
      });
      expect(isLoaded).toBe(true);
    }
  });

  test('expandable image modal opens and displays full preview on click', async ({ page }) => {
    await page.goto('/products');

    const firstExpandable = page.locator('.expandable-image').first();
    await expect(firstExpandable).toBeVisible();

    // Click the image to trigger expansion
    await firstExpandable.click();

    // Wait for the expanded image clone in the body
    const expandedImg = page.locator('img[id^="expanded-image-"]');
    await expect(expandedImg).toBeVisible();

    const isLoaded = await expandedImg.evaluate((el) => el.complete && el.naturalWidth > 0);
    expect(isLoaded).toBe(true);

    // Click the expanded image to dismiss
    await expandedImg.click();
    await expect(expandedImg).toHaveCount(0, { timeout: 3000 });
  });

  test('product detail view renders image cleanly with valid dimensions', async ({ page }) => {
    await page.goto('/products');

    // Click view on the first product
    const firstViewBtn = page.locator('a:has-text("View"), a:has-text("عرض")').first();
    await firstViewBtn.click();
    await expect(page).toHaveURL(/\/products\/\d+/);

    // Detail view image must be visible and loaded
    const detailImg = page.locator('main img').first();
    await expect(detailImg).toBeVisible();

    const isLoaded = await detailImg.evaluate((el) => el.complete && el.naturalWidth > 0);
    expect(isLoaded).toBe(true);
  });

  test('product edit view renders current image or upload placeholder without breaking', async ({ page }) => {
    await page.goto('/products');

    const firstEditBtn = page.locator('a:has-text("Edit"), a:has-text("تعديل")').first();
    await firstEditBtn.click();
    await expect(page).toHaveURL(/\/products\/\d+\/edit/);

    const imgPreview = page.locator('form img');
    const imgCount = await imgPreview.count();

    if (imgCount > 0) {
      const isLoaded = await imgPreview.first().evaluate((el) => el.complete && el.naturalWidth > 0);
      expect(isLoaded).toBe(true);
    }
  });

  test('product creation with image upload persists and previews properly', async ({ page }) => {
    await page.goto('/products/create');

    const prodBarcode = 'IMG-' + Date.now();
    const prodName = 'Coffee With Image ' + Date.now();

    await page.locator('input[name="name_ar"]').fill('قهوة صورة ' + prodName);
    await page.locator('input[name="name_en"]').fill(prodName);
    await page.locator('input[name="brand"]').fill('Roasters Direct');
    await page.locator('input[name="original_code"]').fill(prodBarcode);
    await page.locator('input[name="retail_price"]').fill('120.00');
    await page.locator('input[name="wholesale_price"]').fill('95.00');

    // Select category and unit if available
    const categorySelect = page.locator('select[name="category_id"]');
    if (await categorySelect.count() > 0) {
      await categorySelect.selectOption({ index: 1 });
    }

    const unitSelect = page.locator('select[name="sale_unit_id"]');
    if (await unitSelect.count() > 0) {
      await unitSelect.selectOption({ index: 1 });
    }

    // Attach sample image
    const fileInput = page.locator('input[name="image"][type="file"]');
    await fileInput.setInputFiles({
      name: 'test-product.png',
      mimeType: 'image/png',
      buffer: Buffer.from(
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
        'base64'
      ),
    });

    await page.locator('form button[type="submit"]:has-text("Save")').click();
    await expect(page).toHaveURL(/\/products/);

    // Verify product exists by name_ar in the table
    const uploadedRow = page.locator('tr', { hasText: prodName });
    await expect(uploadedRow).toBeVisible();

    const rowImg = uploadedRow.locator('img');
    await expect(rowImg).toBeVisible();

    const isLoaded = await rowImg.evaluate((el) => el.complete && el.naturalWidth > 0);
    expect(isLoaded).toBe(true);
  });

});
