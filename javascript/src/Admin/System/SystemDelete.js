import Modal from "../Modal";

export default class SystemDelete
{
    constructor(deleteButton) {
        this.deleteButton = deleteButton;

        this._registerEvents();
    }

    onDeleteClick(event) {
        event.preventDefault();

        const modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('base.delete'),
            window.translations.trans('admin.system.messages.deleteQuestion')
        );

        modal.setConfirmCallback(this.onConfirm.bind(this)).show();
    }

    onConfirm() {
        window.location.href = this.deleteButton.href;
    }

    _registerEvents() {
        this.deleteButton.addEventListener('click', this.onDeleteClick.bind(this));
    }
}