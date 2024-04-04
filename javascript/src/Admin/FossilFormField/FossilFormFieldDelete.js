import Modal from "../Modal";

export default class FossilFormFieldDelete
{
    deleteButton = null;

    constructor(deleteButton) {
        this.deleteButton = deleteButton;

        this._registerEvents();
    }

    _registerEvents() {
        this.deleteButton.addEventListener('click', this._onClickDeleteButton.bind(this));
    }

    _onClickDeleteButton(event) {
        event.preventDefault();

        new Modal(
            Modal.BUTTONS.YES_NO_BUTTONS,
            window.translations.trans('base.delete'),
            window.translations.trans('admin.formFields.messages.deleteQuestion')
        )
            .setConfirmCallback(this._onConfirmDelete.bind(this))
            .show();
    }

    _onConfirmDelete() {
        window.location.href = this.deleteButton.href;
    }
}