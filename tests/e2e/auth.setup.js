import { test as setup, expect } from '@playwright/test';

const authFile = 'playwright/.auth/user.json';

setup('authenticate', async ({ page }) => {
  await page.goto('/login');
  await page.locator('input[name="email"]').fill('admin@rashed.com');
  await page.locator('input[name="password"]').fill('!RA1998');
  await page.locator('button[type="submit"]:has-text("Sign In")').click();
  await page.waitForURL(/\/(dashboard)?$/);
  await expect(page.locator('body')).not.toContainText('Sign In to POS System');
  await page.context().storageState({ path: authFile });
});

