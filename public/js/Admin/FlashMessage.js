class FlashMessage
{
    elements;

    flashMessageSelector = '.flash-message';
    closeButtonSelector = '.toast-close-button';

    constructor() {
        this.elements = document.querySelectorAll(this.flashMessageSelector);
        this._registerEvents();
    }

    _registerEvents() {
        this.elements.forEach(toast => {
            this._getCloseButton(toast).addEventListener('click', this._onClickCloseButton.bind(this, toast));
        });
    }

    _getCloseButton(toast) {
        return toast.querySelector(this.closeButtonSelector);
    }

    _onClickCloseButton(toast, event) {
        event.preventDefault();
        toast.remove();
    }
}