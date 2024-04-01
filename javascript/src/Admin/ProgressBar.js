export default class ProgressBar {
    progressBar;

    constructor(progressBarSelector) {
        this.progressBarSelector = progressBarSelector;

        this.progressBar = document.querySelector(this.progressBarSelector);
    }

    setProgress = function (currentProgress) {
        this.progressBar.style.width = `${currentProgress}%`;
    }
}