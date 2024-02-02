class ExportPage
{
    exportButtonSelector = 'button[data-start-export="true"]';

    progressBarSelector = 'div[data-progress-bar="true"]';

    constructor()
    {
        // TODO: REMOVE AFTER DEBUG
        console.log('ExportPage class initialized');
        // TODO: REMOVE AFTER DEBUG
        const exportButton = document.querySelector(this.exportButtonSelector);
        const progressBars = document.querySelectorAll(this.progressBarSelector);

        if (exportButton && progressBars) {
            // TODO: REMOVE AFTER DEBUG
            console.log('Export will bee  initialized');
            // TODO: REMOVE AFTER DEBUG
            new Export(exportButton, progressBars);
        }
    }
}