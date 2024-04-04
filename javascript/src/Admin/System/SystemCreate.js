import Modal from "../Modal";

export default class SystemCreate
{
    isInvalidClass = 'is-invalid';
    formSelector = 'form[name="system-form"]';
    nameSelector = 'input[name="name"]';
    activeSelector = 'select[name="active"]';

    constructor(createButton) {
        this.createButton = createButton;

        this._registerEvents();
    }

    onCreateClick(event) {
        event.preventDefault();

        const modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.system.form.title'),
            this._createContent()
        );

        modal.setConfirmCallback(this.onConfirm.bind(this))
            .setDoCloseOnConfirm(false)
            .show();
    }

    onConfirm() {
        if (!this._validateForm()) {
            return;
        }

        this.form.submit();
    }

    _validateForm() {
        const nameInput = this.form.querySelector(this.nameSelector),
            nameValue = nameInput.value;
        let isValid = true;

        if (nameValue.trim().length === 0) {
            nameInput.classList.add(this.isInvalidClass);
            nameInput.addEventListener('change', () => {
                nameInput.classList.remove(this.isInvalidClass);
            });

            isValid = false;
        }

        return isValid;
    }

    _registerEvents() {
        this.createButton.addEventListener('click', this.onCreateClick.bind(this));
    }

    _createContent() {
        this.form = document.querySelector(this.formSelector).cloneNode(true);
        const active = this.form.querySelector(this.activeSelector);
        active.value = 0;

        const createInput = document.createElement('input');
        createInput.name = 'create';
        createInput.value = '1';
        createInput.type = 'hidden';

        this.form.append(createInput);

        return this.form;
    }
}