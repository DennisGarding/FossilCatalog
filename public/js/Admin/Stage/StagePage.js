class StagePage
{
    editButtonSelector = 'a[data-edit-stage="true"]';
    createButtonSelector = 'a[data-stage-create="true"]';
    deleteButtonSelector = 'a[data-delete-stage="true"]';

    constructor() {
        const createButton = document.querySelector(this.createButtonSelector);
        if (createButton) {
            new StageCreate(createButton);
        }

        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new StageDelete(deleteButton);
        });

        const editButtons = document.querySelectorAll(this.editButtonSelector);
        editButtons.forEach(editButton => {
            new StageEdit(editButton);
        });
    }
}