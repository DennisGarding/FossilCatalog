export default class TextLog {
    logContainerSelector = 'div[data-logField="true"]';

    logField;

    static TYPE = {
        ERROR: 'error',
        SUCCESS: 'success',
        INFO: 'info',
        OTHER: 'other'
    };

    constructor() {
        this.logField = document.querySelector(this.logContainerSelector);
    }

    addLog(text, type) {
        const logEntry = this.convertText(text, type);
        this.logField.append(logEntry);

        this._addLineBreak();
    }

    _addLineBreak() {
        const newLine = document.createElement('br');

        this.logField.append(newLine);
        newLine.scrollIntoView();
    }

    convertText(text, type) {
        const span = document.createElement('span');
        switch (type) {
            case TextLog.TYPE.ERROR:
                span.classList.add('text-danger');
                break;
            case TextLog.TYPE.SUCCESS:
                span.classList.add('text-success');
                break;
            case TextLog.TYPE.INFO:
                span.classList.add('text-secondary');
                break;
            default:
                span.classList.add('text-body-secondary');
        }

        span.innerHTML = text;

        return span;
    }
}