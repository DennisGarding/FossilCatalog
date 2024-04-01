import SeriesCreate from "./SeriesCreate";
import SeriesDelete from "./SeriesDelete";
import SeriesEdit from "./SeriesEdit";

export default class SeriesPage
{
    editButtonSelector = 'a[data-edit-series="true"]';
    createButtonSelector = 'a[data-series-create="true"]';
    deleteButtonSelector = 'a[data-delete-series="true"]';

    constructor() {
        const createButton = document.querySelector(this.createButtonSelector);
        if (createButton) {
            new SeriesCreate(createButton);
        }

        const deleteButtons = document.querySelectorAll(this.deleteButtonSelector);
        deleteButtons.forEach(deleteButton => {
            new SeriesDelete(deleteButton);
        });

        const editButtons = document.querySelectorAll(this.editButtonSelector);
        editButtons.forEach(editButton => {
            new SeriesEdit(editButton);
        });
    }
}