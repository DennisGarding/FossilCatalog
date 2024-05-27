import SystemSeriesStageSelect from './SystemSeriesStageSelect';
import DeleteFossil from './DeleteFossil';
import ImageDeleteButton from "../ImageDeleteButton";

export default class FossilPage
{
    systemSelectSelector = 'select[name="eaSystem"]';
    seriesSelectSelector = 'select[name="eaSeries"]';
    stageSelectSelector = 'select[name="eaStage"]';
    imageDeleteButtonsSelector = 'span[data-fossilImageDeleteButton="true"]';

    constructor() {
        const systemSelect = document.querySelector(this.systemSelectSelector);
        const serieSelect = document.querySelector(this.seriesSelectSelector);
        const stageSelect = document.querySelector(this.stageSelectSelector);
        const imageDeleteButtons = document.querySelectorAll(this.imageDeleteButtonsSelector);

        if (systemSelect && serieSelect && stageSelect) {
            new SystemSeriesStageSelect(systemSelect, serieSelect, stageSelect);
        }

        new DeleteFossil();

        imageDeleteButtons.forEach((button) =>  {
            new ImageDeleteButton(button);
        });
    }
}