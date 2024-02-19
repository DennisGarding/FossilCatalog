class ImportDelete {
    deleteButton = null;

    constructor(button) {
        this.deleteButton = button;

        this._registerEvents();
    }

    onDeleteButtonClick(event) {
        event.preventDefault();
        this._showDeleteConfirmModal();
    }

    onConfirmDelete() {
        window.location = this.deleteButton.href;
    }

    _registerEvents() {
        this.deleteButton.addEventListener('click', this.onDeleteButtonClick.bind(this));
    }

    _showDeleteConfirmModal() {
        new Modal(
            Modal.BUTTONS.YES_NO_BUTTONS,
            window.translations.trans('base.delete'),
            window.translations.trans('admin.import.deleteFileQuestion')
        ).setConfirmCallback(this.onConfirmDelete.bind(this)).show();
    }
}