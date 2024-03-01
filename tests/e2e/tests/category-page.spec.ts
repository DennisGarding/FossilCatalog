import { test, expect } from '@playwright/test';
import login from './helper/login';
import goto from './helper/goto';
import extraAssertions from './helper/extra-assertions';
const modalSelector = '.modal.modal-is-open';

test('Category page test', async ({ page }) => {
    await login.login(page);

    // Category create
    await goto.goto(page, '/admin/category');
    await page.locator('a[data-category-create="true"]').click();

    await expect(await page.locator('.modal.modal-is-open h5[class="modal-title"]')).toHaveText('Kategorie erstellen');
    let submitButton = await page.locator(`${modalSelector} :text("OK")`);
    await submitButton.click();

    let nameInput = await page.locator(`${modalSelector} input[name="name"]`);
    await expect(nameInput).toHaveClass(/is-invalid/);

    await nameInput.fill('Test Category');
    await submitButton.click();
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'Kategorie erstellt');

    await expect(await page.locator('td:has-text("Test Category")').first()).toBeVisible();


    // Category edit
    await goto.goto(page, '/admin/category');
    const editButton = await page.locator('a[data-edit-category="true"]');
    await editButton.first().click();

    await expect(await page.locator(`${modalSelector} h5[class="modal-title"]`)).toHaveText('Bearbeiten');

    nameInput = await page.locator(`${modalSelector} input[name="name"]`).first();

    await nameInput.fill('Test Category updated');

    submitButton = await page.locator(`${modalSelector} :text("OK")`);
    await submitButton.click();
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'Erfolgreich gespeichert');


    // Category delete
    await goto.goto(page, '/admin/category');
    const deleteButton = await page.locator('a[data-delete-category="true"]');
    await deleteButton.first().click();

    await expect(await page.locator(`${modalSelector} h5[class="modal-title"]`)).toHaveText('Löschen');

    submitButton = await page.locator(`${modalSelector} :text("Ja")`);
    await submitButton.click();
    await page.waitForLoadState('load');

    await extraAssertions.toastHaveText(page, 'Erfolgreich gelöscht');

    // Check if the category is deleted
    await goto.goto(page, '/admin/category');
    await expect(await page.locator('td:has-text("Test Category updated")')).not.toBeVisible();
});
