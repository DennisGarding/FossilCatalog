import Ajax from "./Ajax";
import Modal from "./Modal";

export default class Updater {
    hiddenClass = 'visually-hidden';

    updateCard;
    updateButton;
    oldVersionSpan;
    newVersionSpan;
    progressBarContainer;
    progressBar;

    checkUrl;
    downloadUrl;
    extractUrl;
    executeUrl;
    cleanupUrl;

    constructor(updateCard) {
        this.updateCard = updateCard;

        this.initialize();
        this.registerEvents();
        this.checkForUpdates();
    }

    initialize() {
        this.checkUrl = this.updateCard.getAttribute('data-checkUrl');
        this.downloadUrl = this.updateCard.getAttribute('data-downloadUrl');
        this.extractUrl = this.updateCard.getAttribute('data-extractUrl');
        this.executeUrl = this.updateCard.getAttribute('data-executeUrl');
        this.cleanupUrl = this.updateCard.getAttribute('data-cleanupUrl');

        this.updateButton = this.updateCard.querySelector('.update-button');
        this.oldVersionSpan = this.updateCard.querySelector('.old-version');
        this.newVersionSpan = this.updateCard.querySelector('.new-version');
        this.progressBarContainer = this.updateCard.querySelector('.progress-bar-container');
        this.progressBar = this.updateCard.querySelector('.progress-bar');
    }

    registerEvents() {
        this.updateButton.addEventListener('click', this.onUpdateButtonClick.bind(this));
    }

    checkForUpdates() {
        const ajax = new Ajax(this.checkUrl);
        ajax.setSuccessCallback(this.onCheckSuccess.bind(this));
        ajax.setErrorCallback(this.onError.bind(this));
        ajax.execute();
    }

    onCheckSuccess(response) {
        if (!response.hasUpdate) {
            return;
        }

        this.oldVersionSpan.innerText = response.currentVersion;
        this.newVersionSpan.innerText = response.latestVersion;
        this.updateCard.classList.remove(this.hiddenClass);
    }

    onUpdateButtonClick() {
        const modal = new Modal(
            Modal.BUTTONS.YES_NO_BUTTONS,
            window.translations.trans('admin.overview.update'),
            window.translations.trans('admin.overview.updateQuestion')
        );

        modal.setConfirmCallback(this.startUpdate.bind(this));
        modal.show();
    }

    startUpdate() {
        document.loadingIndicator.show();
        this.progressBarContainer.classList.remove(this.hiddenClass);

        const ajax = new Ajax(this.downloadUrl);
        ajax.setSuccessCallback(this.onDownloadComplete.bind(this));
        ajax.setErrorCallback(this.onError.bind(this));
        ajax.execute();
    }

    onDownloadComplete() {
        this.updateProgressBar(25);
        const ajax = new Ajax(this.extractUrl);
        ajax.setSuccessCallback(this.onExtractComplete.bind(this));
        ajax.setErrorCallback(this.onError.bind(this));
        ajax.execute();
    }

    onExtractComplete() {
        this.updateProgressBar(50);
        const ajax = new Ajax(this.executeUrl);
        ajax.setSuccessCallback(this.onExecuteComplete.bind(this));
        ajax.setErrorCallback(this.onError.bind(this));
        ajax.execute();
    }

    onExecuteComplete() {
        this.updateProgressBar(75);
        const ajax = new Ajax(this.cleanupUrl);
        ajax.setSuccessCallback(this.onCleanupComplete.bind(this));
        ajax.setErrorCallback(this.onError.bind(this));
        ajax.execute();
    }

    onCleanupComplete() {
        this.updateProgressBar(100);
        document.loadingIndicator.hide();
        this.progressBarContainer.classList.add(this.hiddenClass);

        const modal = new Modal(
            Modal.BUTTONS.OK_BUTTON,
            window.translations.trans('admin.overview.updateComplete'),
            window.translations.trans('admin.overview.updateCompleteMessage')
        );
        modal.setConfirmCallback(this.reloadPage.bind(this));
        modal.show();
    }

    updateProgressBar(currentWith) {
        this.progressBar.style.width = currentWith + '%';
    }

    reloadPage() {
        window.location.reload();
    }

    onError(error) {
        console.error(error);
        const modal = new Modal(
            Modal.BUTTONS.OK_BUTTON,
            window.translations.trans('admin.genericError'),
            error.error,
        );
        modal.show();
        document.loadingIndicator.hide();
    }
}