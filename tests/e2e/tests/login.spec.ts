import { test, expect } from '@playwright/test';

test('login', async ({ page }) => {
    await page.goto('/login');

    const loginTitle = await page.locator('h5[class="card-title"]');
    await expect(loginTitle).toHaveText('Login');

    const emailInput = await page.locator('input[type="email"]');
    await emailInput.fill('test@example.com');

    const passwordInput = await page.locator('input[type="password"]');
    await passwordInput.fill('test1234');

    await page.locator('button[type="submit"]').click();

    const brand = await page.locator('a[class="navbar-brand"]');
    await expect(brand).toHaveText('Fossil-Katalog');
});