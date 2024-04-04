export default class FossilListTableActionColumn {
    actionColumnHeaderSelector = '.fossil-list-action-column';
    actionColumnSelector = '.fossil-list-action-column-buttons';

    constructor(table) {
        this.table = table;

        this.actionColumn = this.table.querySelector(this.actionColumnSelector);
        this.actionColumnHeader = this.table.querySelector(this.actionColumnHeaderSelector);

        if (!this.actionColumn || !this.actionColumnHeader) {
            return;
        }

        this._registerEvents();
        this._calculateActionColumnWidth();
    }

    _registerEvents() {
        window.addEventListener('resize', this._calculateActionColumnWidth.bind(this));
    }

    _calculateActionColumnWidth() {
        const actionColumnWidth = this.actionColumn.offsetWidth;
        this.actionColumnHeader.style.width = `${actionColumnWidth}px`;
    }
}