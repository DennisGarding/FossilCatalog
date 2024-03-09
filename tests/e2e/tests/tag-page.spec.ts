import { test, expect } from '@playwright/test';
import login from './helper/login';
import goto from './helper/goto';
import extraAssertions from './helper/extra-assertions';
import modal from "./helper/modal";

test('Tag page test', async ({ page }) => {
    await login.login(page);
    await goto.goto(page, '/admin/tags');

    // Tag create
    await page.locator('a[data-tag-create="true"]').click();
    await modal.hasTitle(page, 'Tag erstellen');
    await modal.click(page, ':text("OK")');
    await modal.elementHasClass(page, 'input[name="name"]', /is-invalid/);
    await modal.fill(page, 'input[name="name"]', 'Test Tag');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Tag erstellt');
    await expect(await page.locator('td:has-text("Test Tag")').first()).toBeVisible();


    // Tag edit
    await goto.goto(page, '/admin/tags');
    const editButton = await page.locator('a[data-edit-tag="true"]');
    await editButton.first().click();
    await modal.hasTitle(page, 'Bearbeiten');
    await modal.fill(page, 'input[name="name"]', 'Test Tag updated');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Erfolgreich gespeichert');


    // Tag delete
    await goto.goto(page, '/admin/tags');
    const deleteButton = await page.locator('a[data-delete-tag="true"]');
    await deleteButton.first().click();

    await modal.hasTitle(page, 'Löschen');
    await modal.click(page, ':text("Ja")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Erfolgreich gelöscht');


    // Check if the Tag is deleted
    await goto.goto(page, '/admin/tags');
    await expect(await page.locator('td:has-text("Test Tag updated")')).not.toBeVisible();
});