import TagCreate from "./TagCreate";
import TagDelete from "./TagDelete";
import TagEdit from "./TagEdit";

export default class TagPage {
    createButtonSelector = 'a[data-tag-create="true"]';
    deleteButtonSelector = 'a[data-delete-tag="true"]';
    editButtonSelector = 'a[data-edit-tag="true"]';

    constructor() {
        const createButton = document.querySelector(this.createButtonSelector);
        if (createButton) {
            new TagCreate(createButton);
        }

        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new TagDelete(deleteButton);
        });

        const editButtons = document.querySelectorAll(this.editButtonSelector);
        editButtons.forEach(editButton => {
            new TagEdit(editButton);
        });
    }
}