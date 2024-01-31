class FossilPage
{
    systemSelectSelector = 'select[name="earthAgeSystem"]';
    seriesSelectSelector = 'select[name="earthAgeSeries"]';
    stageSelectSelector = 'select[name="earthAgeStage"]';

    constructor() {
        const systemSelect = document.querySelector(this.systemSelectSelector);
        const serieSelect = document.querySelector(this.seriesSelectSelector);
        const stageSelect = document.querySelector(this.stageSelectSelector);

        if (systemSelect && serieSelect && stageSelect) {
            new SystemSeriesStageSelect(systemSelect, serieSelect, stageSelect);
        }

        new DeleteFossil();
    }
}