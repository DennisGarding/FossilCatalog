import Modal from "../Modal";

export default class TagEdit {
    isInvalidClass = 'is-invalid';
    formSelector = 'form[name="tag-form"]';
    idFieldSelector = 'input[name="id"]';
    nameFieldSelector = 'input[name="name"]';

    constructor(editButton) {
        this.editButton = editButton;

        this._registerEvents();
    }

    _registerEvents() {
        this.editButton.addEventListener('click', this._onClickEditButton.bind(this));
    }

    _onClickEditButton(event) {
        event.preventDefault();

        new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('base.edit'),
            this._createForm()
        )
            .setConfirmCallback(this._onConfirmEdit.bind(this))
            .setDoCloseOnConfirm(false)
            .show();
    }

    _createForm() {
        this.form = document.querySelector(this.formSelector);
        const idInput = this.form.querySelector(this.idFieldSelector),
            nameInput = this.form.querySelector(this.nameFieldSelector);

        idInput.value = this.editButton.dataset.id;
        nameInput.value = this.editButton.dataset.value;

        this.form.action = this.editButton.href;

        return this.form;
    }

    _onConfirmEdit() {
        if (!this._validateForm()) {
            return;
        }

        this.form.submit();
    }

    _validateForm() {
        const nameInput = this.form.querySelector(this.nameFieldSelector),
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
}