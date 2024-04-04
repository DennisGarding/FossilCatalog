export default class MultiSelectField {
    hiddenClass = 'visually-hidden';
    searchFieldSelector = '.search-field-input';

    constructor(field) {
        this.field = field;

        this._createElements();
        this._composeElements();
        this._createOptions();
        this._registerEvents();
    }

    _registerEvents() {
        window.addEventListener("resize", this.onWindowResize.bind(this));
        this.searchFieldInput.addEventListener('focus', this.onFocusInputElement.bind(this));
        this.chevronDown.addEventListener('click', this.onClickChevronDown.bind(this));

        this.list.querySelectorAll('.list-group-item').forEach(listItem => {
            listItem.addEventListener('mousedown', this.onListItemClick.bind(this));
        });

        this.selectedList.querySelectorAll('.remove-button').forEach(selectedBadgeButton => {
            selectedBadgeButton.addEventListener('click', this.onSelectedBadgeButtonClick.bind(this));
        });

        this.searchFieldInput.addEventListener('blur', this.onBlurInputElement.bind(this));
        this.searchFieldInput.addEventListener('input', this.onInputSearchFieldInput.bind(this));
    }

    _createOptions() {
        this.fieldOptions = this.field.querySelectorAll('option');
        this.selectedFieldOptions = this.field.querySelectorAll('option[selected]');

        this.selectedFieldOptions.forEach((selectedOption) => {
            this._createSelectedTag(selectedOption);
        });

        this.fieldOptions.forEach((option) => {
            this._createListItem(option);
        });
    }

    _createSelectedTag(option) {
        const removeButton = document.createElement('a');
        removeButton.setAttribute("href", "#");
        removeButton.innerHTML = '<i class="bi-x-circle"></i>';
        removeButton.setAttribute('data-tagId', option.value);
        removeButton.classList.add('remove-button');
        removeButton.style = 'margin-left: 5px; color: #fff;';
        removeButton.addEventListener('click', this.onSelectedBadgeButtonClick.bind(this));

        const tag = document.createElement('span');
        tag.classList.add('badge', 'text-bg-secondary', 'me-1', 'mb-1');
        tag.id = option.value;
        tag.innerHTML = option.text;
        tag.appendChild(removeButton);

        this.selectedList.appendChild(tag);
    }

    _createListItem(option) {
        const listItem = document.createElement('a');
        listItem.innerHTML = option.text;
        listItem.classList.add('list-group-item', 'list-group-item-action')
        listItem.setAttribute('id', option.value);

        if (option.attributes.selected) {
            listItem.classList.add('bg-primary');
        }

        this.list.appendChild(listItem);
    }

    _composeElements() {
        this.searchField.appendChild(this.searchFieldInput);
        this.searchField.appendChild(this.list);
        this.searchField.appendChild(this.chevronDown);

        this.field.parentNode.replaceChild(this.fieldWrapper, this.field);

        this.fieldWrapper.appendChild(this.selectedList);
        this.fieldWrapper.appendChild(this.searchField);
        this.fieldWrapper.appendChild(this.field);
    }

    _createElements() {
        this._createSelectedList();
        this._createFieldWrapper();
        this._createSearchField();
        this._createSearchFieldInput();
        this._createChevronDown();
        this._createList();
    }

    _createFieldWrapper() {
        this.fieldWrapper = document.createElement('div');
        this.fieldWrapper.classList.add('multi-select-wrapper', 'form-control');
    }

    _createSearchField() {
        this.searchField = document.createElement('div');
        this.searchField.classList.add('search-field');
    }

    _createSearchFieldInput() {
        this.searchFieldInput = document.createElement('input');
        this.searchFieldInput.classList.add('form-control', 'search-field-input');
    }

    _createChevronDown() {
        const chevronDownIcon = document.createElement('i');
        chevronDownIcon.classList.add('bi', 'bi-chevron-down');

        this.chevronDown = document.createElement('span');
        this.chevronDown.classList.add('multi-select-chevron-down');
        this.chevronDown.append(chevronDownIcon);
    }

    _createList() {
        this.list = document.createElement('div');
        this.list.classList.add('list-group', 'field-list', 'form-control', this.hiddenClass);
    }


    _createSelectedList() {
        this.selectedList = document.createElement('div');
    }

    _adjustListWidth(input) {
        this.list.style = `width: ${input.clientWidth}px;`;
    }

    onFocusInputElement(event) {
        this.list.classList.remove(this.hiddenClass);
        this._adjustListWidth(event.currentTarget);
    }

    onClickChevronDown(event) {
        event.currentTarget.parentElement.querySelector(this.searchFieldSelector).focus();
    }

    onBlurInputElement() {
        this.list.classList.add(this.hiddenClass);
    }

    onSelectedBadgeButtonClick(event) {
        event.preventDefault();

        const id = event.currentTarget.dataset.tagid;
        const option = this.field.querySelector(`option[value="${id}"]`);
        option.selected = false;

        const badge = this.selectedList.querySelector(`span[id="${id}"]`);
        if (badge) {
            badge.remove();
        }
        this.list.querySelector(`a[id="${id}"]`).classList.remove('bg-primary');
    }

    onWindowResize() {
        this._adjustListWidth(this.fieldWrapper.querySelector(this.searchFieldSelector));
    }

    onListItemClick(event) {
        event.preventDefault();

        if (event.which !== 1) {
            return;
        }

        this.fieldWrapper.querySelector(this.searchFieldSelector).focus();

        const id = event.currentTarget.id;
        const option = this.field.querySelector(`option[value="${id}"]`);

        if (event.currentTarget.classList.contains('bg-primary')) {
            event.currentTarget.classList.remove('bg-primary');
            this.selectedList.querySelector(`span[id="${id}"]`).remove();
            option.selected = false;

            return;
        }

        this._createSelectedTag(option);
        event.currentTarget.classList.add('bg-primary');
        option.selected = true;
    }

    onInputSearchFieldInput(event) {
        const value = event.currentTarget.value.toLowerCase();

        this.list.querySelectorAll('.list-group-item').forEach(listItem => {
            if (listItem.innerHTML.toLowerCase().indexOf(value) > -1) {
                listItem.classList.remove(this.hiddenClass);
            } else {
                listItem.classList.add(this.hiddenClass);
            }
        });
    }
}