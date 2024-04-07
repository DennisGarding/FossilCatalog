import Ajax from "../Admin/Ajax";
import ProgressBar from "../Admin/ProgressBar";
import TextLog from "./TextLog";

export default class Installation {
    installationContainerSelector = '.installation-data';

    toLoginButtonSelector = '#toLoginButton';

    progressBar;

    installationLog;

    urls = {};

    snippets = {};

    currentStep = 1;
    lastStep = 6;

    chain = {
        1: {
            ready: false,
            progress: 15,
            inProgress: false,
            snippet: 'tryToCreateDatabaseText',
            url: 'createDatabaseUrl'
        },
        2: {
            ready: false,
            progress: 30,
            inProgress: false,
            snippet: 'tryToCreateTablesText',
            url: 'createTablesUrl'
        },
        3: {
            ready: false,
            progress: 45,
            inProgress: false,
            snippet: 'tryToCreateDefaultData',
            url: 'createDefaultDataUrl'
        },
        4: {
            ready: false,
            progress: 60,
            inProgress: false,
            snippet: 'tryToCreateUser',
            url: 'createUserUrl'
        },
        5: {
            ready: false,
            progress: 75,
            inProgress: false,
            snippet: 'tryToCreateTranslationFiles',
            url: 'createTranslationFileUrl'
        },
        6: {
            ready: false,
            progress: 100,
            inProgress: false,
            snippet: 'tryToCreateLockFile',
            url: 'createLockFileUrl'
        },
    };

    constructor() {
        this.progressBar = new ProgressBar('div[id="installation-progress"]');
        this.installationLog = new TextLog();

        const dataContainer = document.querySelector(this.installationContainerSelector);

        this._initializeUrls(dataContainer);
        this._initializeSnippets(dataContainer);
    }

    _startProgress() {
        this._executeInstallation();
    }

    _finishProgress() {
        this._stopProgress();
        this._enableToLoginButton();
    }

    _stopProgress() {
        clearInterval(this.interval);
    }

    _executeInstallation() {
        this.installationLog.addLog(this.snippets[this.chain[this.currentStep].snippet], TextLog.TYPE.INFO);
        this.chain[this.currentStep].inProgress = true;

        new Ajax(this.urls[this.chain[this.currentStep].url])
            .setSuccessCallback(this._onSuccess.bind(this))
            .setErrorCallback(this._onError.bind(this))
            .execute();
    }

    _initializeUrls(dataContainer) {
        this.urls['createDatabaseUrl'] = dataContainer.dataset.createdatabaseurl;
        this.urls['createTablesUrl'] = dataContainer.dataset.createtablesurl;
        this.urls['createDefaultDataUrl'] = dataContainer.dataset.createdefaultdataurl;
        this.urls['createUserUrl'] = dataContainer.dataset.createuserurl;
        this.urls['createTranslationFileUrl'] = dataContainer.dataset.createtranslationfileurl;
        this.urls['createLockFileUrl'] = dataContainer.dataset.createlockfileurl;
    }

    _initializeSnippets(dataContainer) {
        this.snippets['tryToCreateDatabaseText'] = dataContainer.dataset.trytocreatedatabasetext;
        this.snippets['tryToCreateTablesText'] = dataContainer.dataset.trytocreatetablestext;
        this.snippets['tryToCreateDefaultData'] = dataContainer.dataset.trytocreatedefaultdata;
        this.snippets['tryToCreateUser'] = dataContainer.dataset.trytocreateuser;
        this.snippets['tryToCreateTranslationFiles'] = dataContainer.dataset.trytocreatetranslationfiles;
        this.snippets['tryToCreateLockFile'] = dataContainer.dataset.trytocreatelockfile;
    }

    _onSuccess(response) {
        this.installationLog.addLog(response.message, TextLog.TYPE.SUCCESS);

        this.progressBar.setProgress(this.chain[this.currentStep].progress);

        this.chain[this.currentStep].inProgress = false;
        this.chain[this.currentStep].ready = true;

        if (this.currentStep === this.lastStep) {
            this._finishProgress()

            return;
        }

        this.currentStep++;

        this._executeInstallation();
    }

    _onError(response) {
        this.installationLog.addLog(response.message, TextLog.TYPE.ERROR);
        this.installationLog.addLog(response.exceptionMessage, TextLog.TYPE.ERROR);
        this.installationLog.addLog(response.trace, TextLog.TYPE.INFO);
        this._stopProgress();
        // TODO: Handle go back
    }

    _enableToLoginButton() {
        document.querySelector(this.toLoginButtonSelector).classList.remove('disabled')
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.installation = new Installation();
    document.installation._startProgress();
});