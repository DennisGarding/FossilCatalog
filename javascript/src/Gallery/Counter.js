import CookieHelper from "./CookieHelper";
import Cookie from "./Cookie";
import Ajax from "../Admin/Ajax";

export default class Counter {
    requiredConsent = 'technicalRequired';
    cookieName = 'CCOY';
    dailyCookieName = 'CCD';
    consent = null;

    cookieConsent;
    counterUrl;
    cookieHelper;

    constructor(cookieConsent) {
        this.cookieConsent = cookieConsent;
        this.counterUrl = this.cookieConsent.getAttribute('data-counter-url');
        this.cookieHelper = new CookieHelper();

        this.consent = this.cookieConsent.value;
        if (this.consent !== null) {
            this.handleCounter();
            return;
        }

        this.registerEvents();
    }

    registerEvents() {
        this.cookieConsent.addEventListener('close', this.onClose.bind(this));
    }

    onClose(event) {
        this.consent = event.detail.accepted;
        this.handleCounter();
    }

    handleCounter() {
        if (!this.consent.includes(this.requiredConsent)) {
            return;
        }

        const cookieValues = this.getCookieValues();
        const url = new URL(this.counterUrl);
        url.search = (new URLSearchParams(cookieValues)).toString();

        const ajax = new Ajax(url.toString());
        ajax.setSuccessCallback(() => {});
        ajax.setErrorCallback(() => {});
        ajax.execute();
    }

    getCookieValues() {
        let result = {
            counterCookie: this.cookieHelper.getCookie(this.cookieName),
            dailyCookie: this.cookieHelper.getCookie(this.dailyCookieName)
        };

        if (result.counterCookie !== null && result.dailyCookie !== null) {
            return result;
        }

        if (result.counterCookie === null) {
            result.counterCookie = this.createCounterCookie(result);
        }

        if (result.dailyCookie === null) {
            result.dailyCookie = this.createDailyCookie(result);
        }

        return result;
    }

    createIdentifier() {
        return  Date.now().toString(36) + Math.random().toString(36).substring(2);
    }

    createCounterCookie() {
        const counterCookie = new Cookie(this.cookieName, this.createIdentifier());
        this.cookieHelper.setCookie(counterCookie);

        return counterCookie.getValue();
    }

    createDailyCookie() {
        const dailyCookie = new Cookie(this.dailyCookieName, this.createIdentifier(), 0);
        this.cookieHelper.setCookie(dailyCookie);

        return dailyCookie.getValue();
    }
}