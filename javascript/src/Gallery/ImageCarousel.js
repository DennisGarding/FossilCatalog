export default class ImageCarousel {
    overlaySelector = '.gallery-overlay';

    carouselInnerSelector = '.carousel-inner';

    closeButtonSelector = '.gallery-carousel-close';

    hiddenClass = 'visually-hidden';

    carouselItemClass = 'carousel-item';

    activeClass = 'active';

    modalIsOpenClass = 'modal-is-open';

    carouselItems = [];

    thumbnail;

    overlay;

    innerCarousel;

    body;

    constructor(thumbnail) {
        this.thumbnail = thumbnail;

        this._registerElements();
        this._registerEvents();
    }

    onThumbnailClick() {
        const imageList = JSON.parse(this.thumbnail.dataset.imagelist)

        let counter = 0;
        imageList.forEach((imageUrl) => {
            if (counter === 0) {
                this.innerCarousel.append(this._createImageItem(imageUrl, true));
                counter++;

                return;
            }

            this.innerCarousel.append(this._createImageItem(imageUrl, false));
            counter++;
        });

        this._show();
    }

    onCloseButtonClick(event) {
        this._reset();
    }

    _reset() {
        this._hide();

        this.carouselItems.forEach((image) => {
            image.remove();
        });

        this.carouselItems = [];
    }

    onKeyDown(event) {
        if (event.key === 'Escape') {
            this._reset();
        }
    }

    _registerEvents() {
        this.thumbnail.addEventListener('click', this.onThumbnailClick.bind(this));
        this.closeButton.addEventListener('click', this.onCloseButtonClick.bind(this))
        document.addEventListener('keydown', this.onKeyDown.bind(this));
    }

    _registerElements() {
        this.overlay = document.querySelector(this.overlaySelector);
        this.closeButton = this.overlay.querySelector(this.closeButtonSelector);
        this.innerCarousel = this.overlay.querySelector(this.carouselInnerSelector);
        this.body = document.querySelector('body');
    }

    _createImageItem(url, active) {
        const image = document.createElement('img');
        image.setAttribute('src', url);

        const imageContainer = document.createElement('div');
        imageContainer.classList.add(this.carouselItemClass);
        imageContainer.append(image);

        if (active) {
            imageContainer.classList.add(this.activeClass);
        }

        this.carouselItems.push(imageContainer);

        return imageContainer;
    }

    _show() {
        this.body.classList.add(this.modalIsOpenClass);
        this.overlay.classList.remove(this.hiddenClass);
    }

    _hide() {
        this.body.classList.remove(this.modalIsOpenClass);
        this.overlay.classList.add(this.hiddenClass);
    }
}