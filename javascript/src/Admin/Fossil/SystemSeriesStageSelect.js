export default class SystemSeriesStageSelect {
    systemSelect = null;
    seriesSelect = null;
    stageSelect = null;

    allSeriesOptions = {};
    allStageOptions = {};

    constructor(systemSelect, seriesSelect, stageSelect) {
        this.systemSelect = systemSelect;
        this.seriesSelect = seriesSelect;
        this.stageSelect = stageSelect;

        [...this.seriesSelect.options].forEach(option => {
            if (this.allSeriesOptions[option.dataset.parentId] === undefined) {
                this.allSeriesOptions[option.dataset.parentId] = {};
            }
            this.allSeriesOptions[option.dataset.parentId][option.value] = option;
        });

        [...this.stageSelect.options].forEach(option => {
            if (this.allStageOptions[option.dataset.parentId] === undefined) {
                this.allStageOptions[option.dataset.parentId] = {};
            }
            this.allStageOptions[option.dataset.parentId][option.value] = option;
        });

        this._removeSeriesAndStageOptions();
        this._addSeriesAndStageOptions();
        this._registerEvents();
    }

    _registerEvents() {
        this.systemSelect.addEventListener('change', this._onChangeSystemSelect.bind(this));
        this.seriesSelect.addEventListener('change', this._onChangeSeriesSelect.bind(this));
    }

    _onChangeSystemSelect(event) {
        const systemId = event.target.item(event.target.selectedIndex).dataset.id;
        this._removeOptions(this.seriesSelect);

        if (this.allSeriesOptions[systemId]) {
            this._addOptions(this.seriesSelect, Object.values(this.allSeriesOptions[systemId]));
        }
    }

    _onChangeSeriesSelect(event) {
        const seriesId = event.target.item(event.target.selectedIndex).dataset.id;
        this._removeOptions(this.stageSelect);

        if (this.allStageOptions[seriesId]) {
            this._addOptions(this.stageSelect, Object.values(this.allStageOptions[seriesId]));
        }
    }

    _addOptions(select, options) {
        this._addPlaceholderOption(select);
        let selectedIndex = 0;
        options.forEach((option, index) => {
            if (select.dataset.selected === option.value) {
                selectedIndex = index+1;
            }
            select.add(option);
        });

        select.selectedIndex = selectedIndex;
        select.options[selectedIndex].selected = true;
    }

    _removeOptions(select) {
        [...select.options].forEach(option => {
            select.removeChild(option);
        });
    }

    _removeSeriesAndStageOptions() {
        this._removeOptions(this.seriesSelect);
        this._removeOptions(this.stageSelect);
    }

    _addSeriesAndStageOptions() {
        const initialSeriesSet = this._findOptionSet(this.systemSelect.item(this.systemSelect.selectedIndex).dataset.id, this.allSeriesOptions);
        this._addOptions(this.seriesSelect, Object.values(initialSeriesSet));
        const initialStageSet = this._findOptionSet(this.seriesSelect.item(this.seriesSelect.selectedIndex).dataset.id, this.allStageOptions);
        this._addOptions(this.stageSelect, Object.values(initialStageSet));
    }

    _addPlaceholderOption(select) {
        const option = document.createElement('option');
        option.text = window.translations.trans('base.pleaseSelect');
        option.value = '0';
        option.attributes.setNamedItem(document.createAttribute('disabled'));
        select.add(option);
    }

    _findOptionSet(searchKey, options) {
        if (searchKey === undefined) {
            const keys = Object.keys(options).filter((value) => {
                return value !== '0';
            });

            const min = Math.min(...keys);
            return options[min];
        }

        if (options[searchKey]) {
            return options[searchKey];
        }
    }
}