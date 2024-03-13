import { test, expect } from '@playwright/test';
import login from './helper/login';
import goto from './helper/goto';
import extraAssertions from './helper/extra-assertions';
import modal from "./helper/modal";

test('Category page test', async ({ page }) => {
    await login.login(page);
    await goto.goto(page, '/admin/category');

    // Category create
    await page.locator('a[data-category-create="true"]').click();
    await modal.hasTitle(page, 'Kategorie erstellen');
    await modal.click(page, ':text("OK")');
    await modal.elementHasClass(page, 'input[name="name"]', /is-invalid/);
    await modal.fill(page, 'input[name="name"]', 'Test Category');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Kategorie erstellt');
    await expect(await page.locator('td:has-text("Test Category")').first()).toBeVisible();


    // Category edit
    await goto.goto(page, '/admin/category');
    const editButton = await page.locator('a[data-edit-category="true"]');
    await editButton.first().click();
    await modal.hasTitle(page, 'Bearbeiten');
    await modal.fill(page, 'input[name="name"]', 'Test Category updated');
    await modal.click(page, ':text("OK")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Erfolgreich gespeichert');


    // Category delete
    await goto.goto(page, '/admin/category');
    const deleteButton = await page.locator('a[data-delete-category="true"]');
    await deleteButton.first().click();
    await modal.hasTitle(page, 'Löschen');
    await modal.click(page, ':text("Ja")');
    await page.waitForLoadState('load');
    await extraAssertions.toastHaveText(page, 'Erfolgreich gelöscht');


    // Check if the category is deleted
    await goto.goto(page, '/admin/category');
    await expect(await page.locator('td:has-text("Test Category updated")')).not.toBeVisible();
});
