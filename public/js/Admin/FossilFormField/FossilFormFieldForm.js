class FossilFormFieldForm {
    submitButtonSelector = '[data-submit-fossil-form-field]';

    disabledFieldsSelector = '[disabled="disabled"]'

    form = null;

    constructor(form) {
        this.form = form;
        this.submitButton = form.querySelector(this.submitButtonSelector);

        if (!this.submitButton) {
            return;
        }

        this._registerEvents();
    }

    _registerEvents() {
        this.submitButton.addEventListener('click', this._onClickSubmitButton.bind(this));
    }

    _onClickSubmitButton(event) {
        event.preventDefault();
        document.loadingIndicator.show();

        this.form.querySelectorAll(this.disabledFieldsSelector);

        const disabledFields = this.form.querySelectorAll(this.disabledFieldsSelector);
        disabledFields.forEach((field) => {
            field.attributes.removeNamedItem('disabled');
        });

        this.form.submit();
    }
}