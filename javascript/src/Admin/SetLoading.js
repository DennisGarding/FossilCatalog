export default class SetLoading
{
    relativeClass = 'position-relative';

    constructor(element, append = false, asOverLay = false) {
        this.element = element;
        this.append = append;
        this.asOverLay = asOverLay;

        this.setElementLoading();
    }

    setElementLoading() {
        if (this.append) {
            if (!this.asOverLay) {
                this.element.append(this.createPlaceHolder());
            }

            this.element.append(this.createSpinner());

            return;
        }

        if (!this.asOverLay) {
            this.element.append(this.createPlaceHolder());
        }

        this.element.prepend(this.createSpinner());
    }

    createSpinner() {
        const spinnerContainer = document.createElement('span');
        spinnerContainer.classList.add('overlaySpinner');

        this.indicator = document.createElement('span');

        this.indicator.classList.add('spinner-border');
        this.indicator.classList.add('spinner-border-sm');

        if (this.asOverLay) {
            this.indicator.classList.add('overlay');
            spinnerContainer.append(this.indicator);
            this.indicator = spinnerContainer;
            this.element.classList.add(this.relativeClass)
        }

        return this.indicator;
    }

    remove() {
        this.indicator.remove();
        if (this.placeholder) {
            this.placeholder.remove();
        }

        this.element.classList.remove(this.relativeClass);
    }

    createPlaceHolder() {
        this.placeholder = document.createElement('span');
        this.placeholder.classList.add('overlayPlaceHolder');

        return this.placeholder;
    }
}