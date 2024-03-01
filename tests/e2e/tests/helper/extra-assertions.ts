import { expect } from '@playwright/test';

export default (() => {
    return {
        toastHaveText: async function (page, text) {
            let successText = await page.locator('.toast-container-lane .toast-container .toast-message-text').first();
            await expect(successText).toContainText(text);
        }
    }
})();