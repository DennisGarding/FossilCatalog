import ClearSearchTerm from "./ClearSearchTerm";
import FlashMessage from "./FlashMessage";
import FossilListTableActionColumn from "./FossilListTableActionColumn";
import ImageModal from "./ImageModal";
import KeepSessionAlive from "./KeepSessionAlive";
import LoadingIndicator from "./LoadingIndicator";
import MultiSelectField from "./MultiSelectField";
import Pagination from "./Pagination";
import CategoryPage from "./Category/CategoryPage";
import ExportPage from "./Export/ExportPage";
import FossilPage from "./Fossil/FossilPage";
import FossilFormFieldPage from "./FossilFormField/FossilFormFieldPage";
import ImportPage from "./Import/ImportPage";
import SeriesPage from "./Series/SeriesPage";
import StagePage from "./Stage/StagePage";
import SystemPage from "./System/SystemPage";
import TagPage from "./Tag/TagPage";
import Translation from "../translations/Translation";
import translation from "../translations/messages.json";
import defaultTranslation from "../translations/messages_defaults.json";
import Updater from "./Updater";

document.addEventListener("DOMContentLoaded", function () {
    document.loadingIndicator = new LoadingIndicator();
    document.loadingIndicator.show();

    window.translations = new Translation(translation, defaultTranslation);

    // Initialize all classes
    new KeepSessionAlive();
    new FlashMessage();
    new ClearSearchTerm();
    new TagPage();
    new CategoryPage();
    new FossilFormFieldPage();
    new FossilPage();
    new SystemPage();
    new SeriesPage();
    new StagePage();
    new Pagination();
    new ExportPage();
    new ImportPage();

    const multiSelectFields = document.querySelectorAll('select[data-multi-select-plugin="true"]');
    multiSelectFields.forEach(function (field) {
        new MultiSelectField(field);
    });

    const fossilTables = document.querySelectorAll('table[data-fossil-list-table="true"]');
    fossilTables.forEach(function (table) {
        new FossilListTableActionColumn(table);
    });

    const thumbnailImages = document.querySelectorAll('img[data-image-thumbnail="true"]');
    const modal = document.querySelector('div[data-image-modal="true"]');
    if (modal !== null && thumbnailImages.length > 0) {
        new ImageModal(modal, thumbnailImages);
    }

    // Enable tooltips everywhere
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    const updateCard = document.querySelector('div[data-updater="true"]');
    if (updateCard !== null) {
        console.log('updateCard');
        new Updater(updateCard);
    }

    // const submitButtons = document.querySelectorAll('*[data-submitButton="true"]');
    // submitButtons.forEach((submitButton) => {
    //     new SubmitButton(submitButton);
    // });

    document.loadingIndicator.hide();
});