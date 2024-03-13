import { test, expect } from '@playwright/test';
import login from './helper/login';
import goto from './helper/goto';
import extraAssertions from './helper/extra-assertions';
import modal from "./helper/modal";

test('System page test', async ({ page }) => {
    await login.login(page);
    await goto.goto(page, '/admin/system');

    // Check preinstalled systems
    await expect(await page.locator('.system-table > tbody > tr')).toHaveCount( 13);
    await expect(await page.locator('.system-table tbody tr .text-bg-danger')).toHaveCount(3);
    await expect(await page.locator('.system-table tbody tr .text-bg-success')).toHaveCount(10);


    // Check edit preinstalled system
    await page.locator('a[data-edit-system="true"][data-id="13"]').click();
    await modal.hasTitle(page, 'System');
    await modal.fill(page, 'input[name="name"]', 'Präkambrium edited');
    await modal.selectOption(page, 'select[name="active"]', 'Nein');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'System gespeichert');
    await expect(await page.locator('td:has-text("Präkambrium edited")').first()).toBeVisible();
    await expect(await page.locator('.system-table tbody tr .text-bg-danger')).toHaveCount(4);

    // undo change
    await goto.goto(page, '/admin/system');
    await page.locator('a[data-edit-system="true"][data-id="13"]').click();
    await modal.hasTitle(page, 'System');
    await modal.fill(page, 'input[name="name"]', 'Präkambrium');
    await modal.selectOption(page, 'select[name="active"]', 'Ja');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'System gespeichert');
    await expect(await page.locator('td:has-text("Präkambrium")').first()).toBeVisible();
    await expect(await page.locator('.system-table tbody tr .text-bg-danger')).toHaveCount(3);


    // Check create system
    await goto.goto(page, '/admin/system');
    await page.locator('a[data-system-create="true"]').click();
    await modal.hasTitle(page, 'System');
    await modal.click(page, ':text("OK")');
    await modal.elementHasClass(page, 'input[name="name"]', /is-invalid/);
    await modal.fill(page, 'input[name="name"]', 'Test System');
    await modal.selectOption(page, 'select[name="active"]', 'Ja');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'System gespeichert');
    await expect(await page.locator('td:has-text("Test System")').last()).toBeVisible();


    // Check delete system
    await goto.goto(page, '/admin/system');
    await page.locator('a[data-delete-system="true"]').click();
    await modal.hasTitle(page, 'Löschen');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'System Test System gelöscht');
});