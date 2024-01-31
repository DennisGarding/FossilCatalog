class StageCreate
{
    isInvalidClass = 'is-invalid';
    formSelector = 'form[name="stage-form"]';
    nameSelector = 'input[name="name"]';
    seriesSelector = 'select[name="series"]';

    constructor(createButton) {
        this.createButton = createButton;

        this._registerEvents();
    }

    onCreateClick(event) {
        event.preventDefault();

        this.modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.stage.form.create'),
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
            seriesSelect = this.form.querySelector(this.seriesSelector),
            nameValue = nameInput.value,
            systemValue = seriesSelect.value;
        let isValid = true;

        if (nameValue.trim().length === 0) {
            nameInput.classList.add(this.isInvalidClass);
            nameInput.addEventListener('change', () => {
                nameInput.classList.remove(this.isInvalidClass);
            });

            isValid = false;
        }

        if (!systemValue) {
            seriesSelect.classList.add(this.isInvalidClass);
            seriesSelect.addEventListener('change', () => {
                seriesSelect.classList.remove(this.isInvalidClass);
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