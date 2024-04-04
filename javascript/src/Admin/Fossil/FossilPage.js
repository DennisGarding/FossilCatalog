import SystemSeriesStageSelect from './SystemSeriesStageSelect';
import DeleteFossil from './DeleteFossil';

export default class FossilPage
{
    systemSelectSelector = 'select[name="eaSystem"]';
    seriesSelectSelector = 'select[name="eaSeries"]';
    stageSelectSelector = 'select[name="eaStage"]';

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