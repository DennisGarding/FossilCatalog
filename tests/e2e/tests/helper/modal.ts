import {expect, Locator, Page} from '@playwright/test';

const modalSelector = '.modal.modal-is-open';
const modalTitleSelector = 'h5[class="modal-title"]';

export default (() => {
    return {
        click: async function (page: Page, selector: string) {
            const element = await page.locator(`${modalSelector} ${selector}`);

            await element.click();
        },

        fill: async function (page: Page, selector: string, value: string) {
            const element = await page.locator(`${modalSelector} ${selector}`);

            await element.fill(value);
        },

        selectOption: async function (page: Page, selector: string, value: string) {
            const element = await page.locator(`${modalSelector} ${selector}`);

            await element.selectOption({label: value});
        },

        hasTitle: async function (page: Page, title: string) {
            let modalTitle = await page.locator(`${modalSelector} ${modalTitleSelector}`).first();

            await expect(modalTitle).toHaveText(title);
        },

        elementHasClass: async function (page: Page, selector: string, className: string | RegExp) {
            const element = await page.locator(`${modalSelector} ${selector}`);

            await expect(element).toHaveClass(className);
        }
    }
})();