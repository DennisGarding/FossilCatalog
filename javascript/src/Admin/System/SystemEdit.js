import Modal from "../Modal";

export default class SystemEdit
{
    formSelector = 'form[name="system-form"]';
    idSelector = 'input[name="id"]';
    nameSelector = 'input[name="name"]';
    activeSelector = 'select[name="active"]';

    constructor(editButton) {
        this.editButton = editButton;

        this._registerEvents();
    }

    onEditClick(event) {
        event.preventDefault();

        const modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.system.form.title'),
            this._createContent()
        );

        modal.setConfirmCallback(this.onConfirm.bind(this)).show();
    }

    onConfirm() {
        this.form.submit();
    }

    _registerEvents() {
        this.editButton.addEventListener('click', this.onEditClick.bind(this));
    }

    _createContent() {
        this.form = document.querySelector(this.formSelector).cloneNode(true);

        const id = this.form.querySelector(this.idSelector);
        id.value = this.editButton.dataset.id;

        const name = this.form.querySelector(this.nameSelector);
        name.value = this.editButton.dataset.name;

        const active = this.form.querySelector(this.activeSelector);
        active.value = this.editButton.dataset.active;

        return this.form;
    }
}