class LoadingIndicator {
    loadingIndicatorSelector = '.page-loading-indicator';

    showClass = 'show-loading-indicator';

    element = null;

    constructor() {
        this.element = document.querySelector(this.loadingIndicatorSelector)

        if (!this.element) {
            throw new Error('Cannot find loading indicator element with class: ' + this.loadingIndicatorSelector)
        }
    }

    show() {
        this.element.classList.add(this.showClass);
    }

    hide() {
        this.element.classList.remove(this.showClass);
    }
}