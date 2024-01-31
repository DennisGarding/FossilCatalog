class ClearSearchTerm {
    containerSelector = 'div[data-clearSearchTerm="true"]';
    inputSelector = 'input[name="searchTerm"]';
    clearButtonSelector = '.clearSearchTerm';

    containers = {};

    constructor() {
        this._build();
    }

    _build() {
        document.querySelectorAll(this.containerSelector).forEach((container, index) => {
            this.containers[index] = {
                container: container,
                input: container.querySelector(this.inputSelector),
                button: container.querySelector(this.clearButtonSelector)
            }

            this.containers[index].button.addEventListener('click', this._onClick.bind(this, index));
        });
    }

    _onClick(index) {
        this.containers[index].input.value = null;
    }
}