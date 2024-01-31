class FossilFormFieldPage {
    formSelector = 'form[name="fossil_form_field"]';

    deleteButtonSelector = 'a[data-delete-form-field="true"]';

    constructor() {
        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new FossilFormFieldDelete(deleteButton);
        });

        const form = document.querySelector(this.formSelector);
        if (form) {
            new FossilFormFieldForm(form);
        }
    }
}