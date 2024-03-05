export default (function () {
    return {
        goto: async function (page, url) {
            await page.goto(url);
            await page.waitForLoadState('networkidle');
        }
    };
}());