class ExportPage
{
    exportButtonSelector = 'button[data-start-export="true"]';

    progressBarSelector = 'div[data-progress-bar="true"]';

    constructor()
    {
        const exportButton = document.querySelector(this.exportButtonSelector);
        const progressBars = document.querySelectorAll(this.progressBarSelector);

        if (exportButton && progressBars) {
            new Export(exportButton, progressBars);
        }
    }
}