import Modal from "../Modal";

export default class SeriesCreate {
    isInvalidClass = 'is-invalid';
    formSelector = 'form[name="series-form"]';
    nameSelector = 'input[name="name"]';
    systemSelector = 'select[name="system"]';

    constructor(createButton) {
        this.createButton = createButton;

        this._registerEvents();
    }

    onCreateClick(event) {
        event.preventDefault();

        this.modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.system.form.title'),
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
        const nameInput = this.form.querySelector(this.nameSelector),
            systemSelect = this.form.querySelector(this.systemSelector),
            nameValue = nameInput.value,
            systemValue = systemSelect.value;
        let isValid = true;

        if (nameValue.trim().length === 0) {
            nameInput.classList.add(this.isInvalidClass);
            nameInput.addEventListener('change', () => {
                nameInput.classList.remove(this.isInvalidClass);
            });

            isValid = false;
        }

        if (!systemValue) {
            systemSelect.classList.add(this.isInvalidClass);
            systemSelect.addEventListener('change', () => {
                systemSelect.classList.remove(this.isInvalidClass);
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

        const createInput = document.createElement('input');
        createInput.name = 'create';
        createInput.value = '1';
        createInput.type = 'hidden';

        this.form.append(createInput);

        return this.form;
    }
}