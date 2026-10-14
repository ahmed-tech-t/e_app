import { test, expect } from '@playwright/test';

test.describe('Comprehensive Full-Page Translation Verification (Arabic & English)', () => {

  const pagesToTest = [
    {
      url: '/',
      arKeywords: ['لوحة التحكم', 'مبيعات اليوم', 'مشتريات اليوم', 'إجمالي المنتجات'],
      enKeywords: ['Dashboard', 'Sales Today', 'Purchases Today', 'Total Products'],
    },
    {
      url: '/products',
      arKeywords: ['المنتجات', 'المزيد', 'إضافة منتج', 'الكود'],
      enKeywords: ['Products', 'More', 'Add Product', 'CODE'],
    },
    {
      url: '/categories',
      arKeywords: ['الفئات', 'إضافة فئة'],
      enKeywords: ['Categories', 'Add Category'],
    },
    {
      url: '/sale-units',
      arKeywords: ['وحدات البيع', 'إضافة وحدة بيع'],
      enKeywords: ['Sale Units', 'Add Sale Unit'],
    },
    {
      url: '/locations',
      arKeywords: ['المواقع', 'إضافة موقع'],
      enKeywords: ['Locations', 'Add Location'],
    },
    {
      url: '/suppliers',
      arKeywords: ['الموردون', 'إضافة مورد'],
      enKeywords: ['Suppliers', 'Add Supplier'],
    },
    {
      url: '/sales',
      arKeywords: ['المبيعات', 'فاتورة بيع جديدة'],
      enKeywords: ['Sales', 'New Sale'],
    },
    {
      url: '/sales/create',
      arKeywords: ['البيانات الأساسية', 'الأصناف', 'معاينة', 'اسم العميل'],
      enKeywords: ['Header', 'Items', 'Preview', 'Customer Name'],
    },
    {
      url: '/purchases',
      arKeywords: ['المشتريات', 'فاتورة شراء جديدة'],
      enKeywords: ['Purchases', 'New Purchase'],
    },
    {
      url: '/purchases/create',
      arKeywords: ['البيانات الأساسية', 'الأصناف', 'معاينة', 'المورد'],
      enKeywords: ['Header', 'Items', 'Preview', 'Supplier'],
    },
    {
      url: '/sales-returns',
      arKeywords: ['مرتجع المبيعات'],
      enKeywords: ['Sales Returns'],
    },
    {
      url: '/purchase-returns',
      arKeywords: ['مرتجع المشتريات'],
      enKeywords: ['Purchase Returns'],
    },
    {
      url: '/stock',
      arKeywords: ['حركات المخزون', 'جميع المواقع', 'جميع الأنواع'],
      enKeywords: ['Stock Movements', 'All Locations', 'All Types'],
    },
    {
      url: '/stock/transfer',
      arKeywords: ['تحويل المخزون', 'من موقع', 'إلى موقع', 'معاينة التحويل'],
      enKeywords: ['Stock Transfer', 'From Location', 'To Location', 'Preview Transfer'],
    },
  ];

  test('all pages render localized content and correct direction in Arabic', async ({ page }) => {
    // 1. Switch locale to Arabic via language toggle in topbar
    await page.goto('/');
    const arabicBtn = page.locator('button:has-text("ع")');
    if (await arabicBtn.isVisible()) {
      await arabicBtn.click();
      await page.waitForLoadState('networkidle');
    }

    // Verify document attributes
    await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');
    await expect(page.locator('html')).toHaveAttribute('lang', 'ar');

    // Test each page under Arabic locale
    for (const pageItem of pagesToTest) {
      await page.goto(pageItem.url);
      await page.waitForLoadState('networkidle');

      // Verify HTML still RTL
      await expect(page.locator('html')).toHaveAttribute('dir', 'rtl');

      const bodyText = await page.locator('body').innerText();
      for (const keyword of pageItem.arKeywords) {
        expect(bodyText).toContain(keyword);
      }
    }
  });

  test('all pages render localized content and correct direction in English', async ({ page }) => {
    // 1. Switch locale to English via language toggle in topbar
    await page.goto('/');
    const englishBtn = page.locator('button:has-text("EN")');
    if (await englishBtn.isVisible()) {
      await englishBtn.click();
      await page.waitForLoadState('networkidle');
    }

    // Verify document attributes
    await expect(page.locator('html')).toHaveAttribute('dir', 'ltr');
    await expect(page.locator('html')).toHaveAttribute('lang', 'en');

    // Test each page under English locale
    for (const pageItem of pagesToTest) {
      await page.goto(pageItem.url);
      await page.waitForLoadState('networkidle');

      // Verify HTML is LTR
      await expect(page.locator('html')).toHaveAttribute('dir', 'ltr');

      const bodyText = await page.locator('body').innerText();
      for (const keyword of pageItem.enKeywords) {
        expect(bodyText).toContain(keyword);
      }
    }
  });

});
