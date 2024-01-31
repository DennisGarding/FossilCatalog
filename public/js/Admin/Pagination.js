class Pagination {
    paginationSelector = '.pagination';
    paginationItemSelector = 'a[class="page-link"]';
    formSelector = 'form[name="filters"]';

    constructor() {
        this.pagination = document.querySelector(this.paginationSelector);
        if (!this.pagination) {
            return;
        }

        this.pageLinks = this.pagination.querySelectorAll(this.paginationItemSelector);
        this.form = document.querySelector(this.formSelector);
        if (!this.form) {
            return;
        }

        this._createHiddenInput();
        this._registerEvents();
    }

    _registerEvents() {
        this.pageLinks.forEach((link) => {
            link.addEventListener('click', this.onPaginationLinkClick.bind(this));
        });
    }

    _setPage(element) {
        this.pageInput.value = element.dataset.page
    }

    _createHiddenInput() {
        this.pageInput = document.createElement('input');
        this.pageInput.type = 'hidden';
        this.pageInput.name = 'page';

        this.form.appendChild(this.pageInput);
    }

    onPaginationLinkClick(event) {
        event.preventDefault();

        this._setPage(event.target);
        this.form.submit();
    }
}