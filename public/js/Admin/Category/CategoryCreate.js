class CategoryCreate {
    isInvalidClass = 'is-invalid';
    formSelector = 'form[name="category-form"]';
    nameFieldSelector = 'input[name="name"]';

    constructor(createButton) {
        this.createButton = createButton;

        this._registerEvents();
    }

    onClickCreateButton(event) {
        event.preventDefault();

        this.modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.category.createCategoryTitle'),
            this._createContent()
        );

        this.modal.setConfirmCallback(this.onConfirm.bind(this))
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

    _registerEvents() {
        this.createButton.addEventListener('click', this.onClickCreateButton.bind(this));
    }

    _createContent() {
        this.form = document.querySelector(this.formSelector);

        return this.form;
    }
}