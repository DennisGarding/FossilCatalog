import Ajax from "../Ajax";
import Modal from "../Modal";

export default class DeleteFossil {
    deleteButtonSelector = 'a[data-delete-fossil="true"]';

    constructor() {
        this.deleteButton = document.querySelectorAll(this.deleteButtonSelector);

        this._registerEvents();
    }

    onClickDeleteButton(button, event) {
        event.preventDefault();
        const number = button.dataset.number;

        const modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.fossil.deleteFossilTitle'),
            this._createModalContent(number)
        );

        modal.setConfirmCallback(this.onConfirmDelete.bind(this, number, button))
            .setDoCloseOnConfirm(false);

        modal.show();
    }

    onConfirmDelete(number, button, modal) {
        if (this.confirmInput.value !== number) {
            this.confirmInput.classList.add('is-invalid');

            return;
        }

        const url = new URL(button.href);
        url.searchParams.append('number', number);

        const ajax = new Ajax(url.toString());
        ajax.setSuccessCallback(this.onAjaxSuccess.bind(this, number, button, modal))
            .setErrorCallback(this.onAjaxError.bind(this))
            .execute();
    }

    onAjaxSuccess(number, button, modal) {
        modal.close();

        window.location.reload();
    }

    onAjaxError() {
        window.location.reload();
    }

    _registerEvents() {
        this.deleteButton.forEach((button) => {
            button.addEventListener('click', this.onClickDeleteButton.bind(this, button));
        });
    }

    _createModalContent(number) {
        const div = document.createElement('div');
        div.innerHTML = window.translations.trans('admin.fossil.deleteFossilMessage').replace('%s', number);
        this.confirmInput = this.createConfirmInput();

        const innerDiv = document.createElement('div');
        innerDiv.classList.add('mt-3');

        innerDiv.appendChild(this.confirmInput);
        div.appendChild(innerDiv);
        return div;
    }

    createConfirmInput() {
        this.confirmInput = document.createElement('input');
        this.confirmInput.type = 'text';
        this.confirmInput.classList.add('form-control');
        this.confirmInput.addEventListener('input', () => {
            this.confirmInput.classList.remove('is-invalid');
        });

        return this.confirmInput;
    }
}