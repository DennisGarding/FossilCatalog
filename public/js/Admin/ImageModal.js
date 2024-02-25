class ImageModal {
    closeButtonSelector = 'button[data-modal-close-button="true"]';

    modalIsOpenClass = 'modal-is-open';

    constructor(modal, thumbnails) {
        this.thumbnails = thumbnails;
        this.modal = modal;
        this.body = document.querySelector('body');

        this._registerElements();
        this._registerEvents();
    }

    openModal() {
        this.body.classList.add(this.modalIsOpenClass);
        this.modal.style.display = "block";
    }

    closeModal() {
        this.body.classList.remove(this.modalIsOpenClass);
        this.modal.style.display = "none";
    }

    onThumbnailClick(event) {
        const thumbnail = event.target;
        const selector = `div[data-index="${thumbnail.dataset.index}"]`;
        const nextActive = this.modal.querySelector(selector);

        this._disableAllActive();
        nextActive.classList.add('active');

        this.openModal();
    }

    _registerElements() {
        this.closeButtons = this.modal.querySelectorAll(this.closeButtonSelector);
    }

    _registerEvents() {
        const self = this;
        this.thumbnails.forEach(function (thumbnail) {
            thumbnail.addEventListener('click', self.onThumbnailClick.bind(self));
        });

        this.closeButtons.forEach(function (button) {
            button.addEventListener('click', self.closeModal.bind(self));
        });
    }

    _disableAllActive() {
        this.modal.querySelectorAll('.carousel-item.active').forEach(function (item) {
            item.classList.remove('active');
        });
    }
}