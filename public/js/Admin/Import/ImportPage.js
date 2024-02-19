class ImportPage {
    importButtonSelector = 'a[data-start-importButton="true"]';

    deleteImportButtonSelector = 'a[data-deleteImportButton="true"]';

    constructor() {
        const importButtons = document.querySelectorAll(this.importButtonSelector);
        const deleteButtons = document.querySelectorAll(this.deleteImportButtonSelector);

        if (importButtons) {
            importButtons.forEach((button) => {
                new Import(button);
            });
        }

        if (deleteButtons.length > 0) {
            deleteButtons.forEach((button) => {
                new ImportDelete(button);
            });
        }
    }
}