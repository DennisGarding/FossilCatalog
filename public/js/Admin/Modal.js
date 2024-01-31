class Modal {
    static BUTTONS = {
        OK_BUTTON: 1,
        YES_NO_BUTTONS: 2,
        OK_CANCEL_BUTTON: 3,
        CONFIRM_INPUT: 4
    };

    modal = null;
    modalTitleContainer = null;
    modalContentContainer = null;
    closeButton = null;
    yesButton = null;
    noButton = null;
    okButton = null;
    cancelButton = null;
    confirmInput = null;

    confirmCallback = null;
    cancelCallback = null;

    modalBasicContainerSelector = 'modal-template-container';
    modalTitleSelector = '.modal-content .modal-title';
    modalContentSelector = '.modal-content .modal-body .modal-inner-content';
    closeButtonSelector = '.close';
    yesButtonSelector = '.yesButton';
    noButtonSelector = '.noButton';
    okButtonSelector = '.okButton';
    cancelButtonSelector = '.cancelButton';
    confirmInputSelector = 'input[name="confirmInput"]';

    hiddenClass = 'visually-hidden';

    buttons;
    title;
    content;
    doCloseOnConfirm = true;

    constructor(buttons, title, content) {
        this.buttons = buttons;
        this.title = title;
        this.content = content;

        this.modal = document.getElementById(this.modalBasicContainerSelector)?.cloneNode(true);
        if (!this.modal) {
            throw new Error('Modal template not found');
        }

        this._initializeModalElements();
        this._handleButtonVisibility();
        this._registerEvents();
        this._setContents();
    }

    setConfirmCallback(callback) {
        this.confirmCallback = callback;

        return this;
    }

    setCancelCallback(callback) {
        this.cancelCallback = callback;

        return this;
    }

    setDoCloseOnConfirm(doCloseOnConfirm) {
        this.doCloseOnConfirm = doCloseOnConfirm;

        return this;
    }

    show() {
        document.body.appendChild(this.modal);
        this.modal.style.display = 'block';

        return this;
    }

    close() {
        this._hideModal();
        this.destroy();
    }

    destroy() {
        this.modal.remove();
    }

    _initializeModalElements() {
        this.modalTitleContainer = this.modal.querySelector(this.modalTitleSelector);
        this.modalContentContainer = this.modal.querySelector(this.modalContentSelector);
        this.closeButton = this.modal.querySelector(this.closeButtonSelector);
        this.yesButton = this.modal.querySelector(this.yesButtonSelector);
        this.noButton = this.modal.querySelector(this.noButtonSelector);
        this.okButton = this.modal.querySelector(this.okButtonSelector);
        this.cancelButton = this.modal.querySelector(this.cancelButtonSelector);
        this.confirmInput = this.modal.querySelector(this.confirmInputSelector);
    }

    _handleButtonVisibility() {
        if (this.buttons === Modal.BUTTONS.OK_BUTTON) {
            this.okButton.classList.remove(this.hiddenClass);

            return
        }

        if (this.buttons === Modal.BUTTONS.YES_NO_BUTTONS) {
            this.yesButton.classList.remove(this.hiddenClass);
            this.noButton.classList.remove(this.hiddenClass);

            return;
        }

        if (this.buttons === Modal.BUTTONS.OK_CANCEL_BUTTON) {
            this.okButton.classList.remove(this.hiddenClass);
            this.cancelButton.classList.remove(this.hiddenClass);

            return;
        }

        if (this.buttons === Modal.BUTTONS.CONFIRM_INPUT) {
            this.confirmInput.classList.remove(this.hiddenClass);
        }
    }

    _registerEvents() {
        this.closeButton.addEventListener('click', this._onCloseButtonClick.bind(this));

        if (this.buttons === Modal.BUTTONS.OK_BUTTON) {
            this.okButton.addEventListener('click', this._onConfirm.bind(this));
        }

        if (this.buttons === Modal.BUTTONS.YES_NO_BUTTONS) {
            this.yesButton.addEventListener('click', this._onConfirm.bind(this));
            this.noButton.addEventListener('click', this._onCancel.bind(this));
        }

        if (this.buttons === Modal.BUTTONS.OK_CANCEL_BUTTON) {
            this.okButton.addEventListener('click', this._onConfirm.bind(this));
            this.cancelButton.addEventListener('click', this._onCancel.bind(this));
        }
    }

    _setContents() {
        this.modalTitleContainer.append(this.title);
        this.modalContentContainer.append(this.content);
    }

    _isFunction(callback) {
        return callback && {}.toString.call(callback) === '[object Function]';
    }

    _hideModal() {
        this.modal.style.display = 'none';
    }

    _onCloseButtonClick() {
        this._hideModal();
    }

    _onConfirm() {
        if (this.doCloseOnConfirm) {
            this._hideModal();
        }

        if (this.confirmCallback !== null && this._isFunction(this.confirmCallback)) {
            this.confirmCallback(this);
        }

        if (this.doCloseOnConfirm) {
            this.destroy();
        }
    }

    _onCancel() {
        this._hideModal();

        if (this.cancelCallback !== null && this._isFunction(this.cancelCallback)) {
            this.cancelCallback(this);
        }

        this.destroy();
    }
}