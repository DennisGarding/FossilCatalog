import Modal from './Modal'
import Ajax from "./Ajax";

export default class ImageDeleteButton {
    deleteButton;

    constructor(button) {
        this.deleteButton = button;

        this.registerEvents();
    }

    registerEvents() {
        this.deleteButton.addEventListener('click', this.onDeleteButtonClick.bind(this));
    }

    onDeleteButtonClick() {
        const modal = new Modal(
            Modal.BUTTONS.YES_NO_BUTTONS,
            window.translations.trans('admin.fossil.image.deleteModalTitle'),
            window.translations.trans('admin.fossil.image.deleteModalText'),
        );
        modal.setConfirmCallback(this.onDeleteImage.bind(this));
        modal.show();
    }

    onDeleteImage() {
        const ajax = new Ajax(this.deleteButton.getAttribute('data-url'));
        ajax.setErrorCallback(this.onAjaxError.bind(this));
        ajax.setSuccessCallback(this.onAjaxSuccess.bind(this));
        ajax.execute()
    }

    onAjaxError() {
        const modal = new Modal(
            Modal.BUTTONS.OK_BUTTON,
            window.translations.trans('admin.genericError'),
            window.translations.trans('admin.genericErrorText'),
        );

        modal.show();
    }

    onAjaxSuccess() {
        this.deleteButton.parentNode.remove();
    }
}