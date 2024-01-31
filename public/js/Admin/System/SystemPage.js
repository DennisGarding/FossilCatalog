class SystemPage {
    editButtonSelector = 'a[data-edit-system="true"]';
    createButtonSelector = 'a[data-system-create="true"]';
    deleteButtonSelector = 'a[data-delete-system="true"]';

    constructor() {
        const createButton = document.querySelector(this.createButtonSelector);
        if (createButton) {
            new SystemCreate(createButton);
        }

        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new SystemDelete(deleteButton);
        });

        const editButtons = document.querySelectorAll(this.editButtonSelector);
        editButtons.forEach(editButton => {
            new SystemEdit(editButton);
        });
    }
}