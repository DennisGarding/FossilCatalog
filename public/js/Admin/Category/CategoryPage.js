class CategoryPage {
    deleteButtonSelector = 'a[data-delete-category="true"]';
    editButtonSelector = 'a[data-edit-category="true"]';
    createButtonSelector = 'a[data-category-create="true"]';

    constructor() {
        const createButton = document.querySelector(this.createButtonSelector);
        if (createButton) {
            new CategoryCreate(createButton);
        }

        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new CategoryDelete(deleteButton);
        });

        const editButtons = document.querySelectorAll(this.editButtonSelector);
        editButtons.forEach(editButton => {
            new CategoryEdit(editButton);
        });
    }
}