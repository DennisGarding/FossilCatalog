import SetLoading from "../SetLoading";
import Ajax from "../Ajax";
import Modal from "../Modal";

export default class Import {
    importStatusCardSelector = 'div[data-import-status-card="true"]';

    progressBarStripedClass = 'progress-bar-striped';

    progressBarAnimatedClass = 'progress-bar-animated';

    hiddenClass = 'visually-hidden';

    constructor(importButton) {
        this.importButton = importButton;
        this.statusCard = document.querySelector(this.importStatusCardSelector);

        this._registerEvents();
    }

    onImportButtonClick(event) {
        event.preventDefault();

        this.loadingIndicator = new SetLoading(this.importButton);
        this._disableImportButton();
        this._showImportModal();
    }

    onConfirmImport() {
        this._showStausCard();

        this._analyze();
    }

    onSuccess(response) {
        let allFinished = true;
        Object.keys(response.status).forEach((status) => {
            if (!response.status[status].isFinished) {
                allFinished = false;
            }

            this._setViewState(response.status[status]);
        });

        if (allFinished) {
            const ajaxCall = new Ajax(this.importButton.dataset.clearurl);
            ajaxCall.setSuccessCallback(this.onSessionCleared.bind(this));
            ajaxCall.setErrorCallback(this.onError.bind(this));
            ajaxCall.execute();

            return;
        }

        this._import();
    }

    onError(response) {
        this.loadingIndicator.remove();
        this._enableImportButton();
        this._showErrorModal(response);
    }

    onSessionCleared() {
        window.location.reload();
    }

    onAnalyzeSuccess(response) {
        Object.keys(response.status).forEach((status) => {
            this._addProgressBar(response.status[status]);
        });

        this._import();
    }

    _setViewState(status) {
        const selector = 'div[data-type="%s"]'.replace('%s', status.type)
        const progressBar = document.querySelector(selector);

        const onStep = 100 / status.inImportQueue;
        const currentPercent = onStep * status.imported;

        progressBar.style.width = currentPercent + '%';

        if (status.isFinished) {
            progressBar.classList.remove(this.progressBarStripedClass);
            progressBar.classList.remove(this.progressBarAnimatedClass);

            progressBar.style.width = '100%';
        }
    }

    _analyze() {
        const ajaxCall = new Ajax(this.importButton.dataset.analyzeurl);
        ajaxCall.setSuccessCallback(this.onAnalyzeSuccess.bind(this));
        ajaxCall.setErrorCallback(this.onError.bind(this));
        ajaxCall.execute();
    }

    _import() {
        const ajaxCall = new Ajax(this.importButton.href);
        ajaxCall.setSuccessCallback(this.onSuccess.bind(this));
        ajaxCall.setErrorCallback(this.onError.bind(this));
        ajaxCall.execute();
    }

    _registerEvents() {
        this.importButton.addEventListener('click', this.onImportButtonClick.bind(this));
    }

    _showImportModal() {
        const modalContent = document.createElement('div'),
            p_question = document.createElement('p'),
            p_hint = document.createElement('p'),
            p_doNotLeaveThePage = document.createElement('p');

        p_question.append(window.translations.trans('admin.import.startQuestion'));
        p_hint.append(window.translations.trans('admin.import.timeHint'));
        p_doNotLeaveThePage.append(window.translations.trans('admin.import.doNotLeaveThePage'));

        modalContent.classList.add('alert', 'alert-warning');
        modalContent.append(p_question);
        modalContent.append(p_hint);
        modalContent.append(p_doNotLeaveThePage);

        new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.import.startImport'),
            modalContent
        ).setConfirmCallback(this.onConfirmImport.bind(this))
            .show();
    }

    _showErrorModal(response) {
        const modal = new Modal(
            Modal.BUTTONS.OK_BUTTON,
            window.translations.trans('admin.genericError'),
            this.createModalErrorContent(response),
        ).show();
    }

    createModalErrorContent(response) {
        const container = document.createElement('div');
        const message = document.createElement('p');
        message.innerText = response.message;

        const trace = document.createElement('div');
        trace.classList.add('overflow-auto', 'text-break');
        trace.innerText = response.trace;

        container.append(message);
        container.append(trace);

        return container;
    }

    _showStausCard() {
        this.statusCard.classList.remove(this.hiddenClass);
    }

    _disableImportButton() {
        this.importButton.disabled = true;
    }

    _getTranslation(type) {
        switch (type) {
            case 'category':
                return window.translations.trans('admin.export.categories');
            case 'category_relation':
                return window.translations.trans('admin.export.categoryRelations');
            case 'tag':
                return window.translations.trans('admin.export.tags');
            case 'tag_relation':
                return window.translations.trans('admin.export.tagRelations');
            case 'system':
                return window.translations.trans('admin.export.systems');
            case 'series':
                return window.translations.trans('admin.export.series');
            case 'stage':
                return window.translations.trans('admin.export.stages');
            case 'form_field':
                return window.translations.trans('admin.export.formFields');
            case 'image':
                return window.translations.trans('admin.export.images');
            case 'image_relation':
                return window.translations.trans('admin.export.imagesRelations');
            case 'fossil':
                return window.translations.trans('admin.export.fossils');
            case 'settings':
                return window.translations.trans('admin.export.settings');
        }
    }

    _enableImportButton() {
        this.importButton.disabled = false;
    }

    _addProgressBar(status) {
        const progressBar = document.createElement('div');
        progressBar.setAttribute('data-type', status.type);
        progressBar.dataset.progressBar = 'true';
        progressBar.classList.add('progress-bar', 'progress-bar-striped', 'progress-bar-animated');

        const progressBarWrapper = document.createElement('div');
        progressBarWrapper.classList.add('progress', 'mt-1')
        progressBarWrapper.setAttribute('role', 'progressbar');
        progressBarWrapper.setAttribute('aria-valuenow', '0');
        progressBarWrapper.setAttribute('aria-valuemin', '0');
        progressBarWrapper.setAttribute('aria-valuemax', '100');
        progressBarWrapper.append(progressBar);

        const label = document.createElement('label');
        label.innerText = this._getTranslation(status.type);

        const progressBarContainer = document.createElement('div');
        progressBarContainer.classList.add('mb3');
        progressBarContainer.append(label);
        progressBarContainer.append(progressBarWrapper);

        this.statusCard.querySelector('.import_progress_container').appendChild(progressBarContainer);
    }
}