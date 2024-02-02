class Export {
    progressBarStripedClass = 'progress-bar-striped';

    progressBarAnimatedClass = 'progress-bar-animated';

    constructor(exportButton, progressBars) {
        this.exportButton = exportButton;
        this.progressBars = progressBars;

        this._registerEvents();
    }

    onClickButton(event) {
        event.preventDefault();
        this._resetProgressBars();
        this._showStartModal();
    }

    onConfirmExport() {
        this._disableExportButton();
        this.loadingIndicator = new SetLoading(this.exportButton);
        this._export();
    }

    onSuccess(response) {
        let allFinished = true;
        Object.keys(response.status).forEach((status) => {
            // TODO Create status class

            if (!response.status[status].isFinished) {
                allFinished = false;
            }

            this._setViewState(response.status[status]);
        });

        if (allFinished) {
            const ajaxCall = new Ajax(this.exportButton.dataset.clearurl);
            ajaxCall.setSuccessCallback(this.onSessionCleared.bind(this));
            ajaxCall.setErrorCallback(this.onError.bind(this));
            ajaxCall.execute();

            return;
        }

        this._export();
    }

    onSessionCleared() {
        this.loadingIndicator.remove();
        this._enableExportButton();
    }

    onError(response) {
        this.loadingIndicator.remove();
        this._enableExportButton();
        this._showErrorModal(response.responseText);
    }

    _export() {
        const ajaxCall = new Ajax(this.exportButton.dataset.url);
        ajaxCall.setSuccessCallback(this.onSuccess.bind(this));
        ajaxCall.setErrorCallback(this.onError.bind(this));
        ajaxCall.execute();
    }

    _setViewState(status) {
        const selector = 'div[data-type="%s"]'.replace('%s', status.type)
        const progressBar = document.querySelector(selector);

        const onStep = 100 / status.inExportQueue;
        const currentPercent = onStep * status.exported;

        progressBar.style.width = currentPercent + '%';

        if (status.isFinished) {
            progressBar.classList.remove(this.progressBarStripedClass);
            progressBar.classList.remove(this.progressBarAnimatedClass);

            progressBar.style.width = '100%';
        }
    }

    _showErrorModal(content) {
        const modal = new Modal(
            Modal.BUTTONS.OK_BUTTON,
            window.translations.trans('admin.genericError'),
            content,
        );
    }

    _showStartModal() {
        const modalContent = `
            <p>${window.translations.trans('admin.export.startQuestion')}</p>
            <p>${window.translations.trans('admin.export.timeHint')}</p>
            <p>${window.translations.trans('admin.export.doNotLeaveThePage')}</p>
        `;

        const modal = new Modal(
            Modal.BUTTONS.OK_CANCEL_BUTTON,
            window.translations.trans('admin.export.doNotLeaveThePage'),
            modalContent,
        );

        modal.setConfirmCallback(this.onConfirmExport.bind(this))
            .show();
    }

    _resetProgressBars() {
        this.progressBars.forEach((progressBar) => {
            if (!progressBar.classList.contains(this.progressBarStripedClass)) {
                progressBar.classList.add(this.progressBarStripedClass);
            }

            if (!progressBar.classList.contains(this.progressBarAnimatedClass)) {
                progressBar.classList.add(this.progressBarAnimatedClass);
            }

            progressBar.style.width = '0%';
        });
    }

    _registerEvents() {
        this.exportButton.addEventListener('click', this.onClickButton.bind(this));
    }

    _disableExportButton() {
        this.exportButton.setAttribute('disabled', 'disabled');
    }

    _enableExportButton() {
        this.exportButton.removeAttribute('disabled');
    }
}