import ClearSearchTerm from "./Admin/ClearSearchTerm";
import FlashMessage from "./Admin/FlashMessage";
import FossilListTableActionColumn from "./Admin/FossilListTableActionColumn";
import ImageModal from "./Admin/ImageModal";
import KeepSessionAlive from "./Admin/KeepSessionAlive";
import LoadingIndicator from "./Admin/LoadingIndicator";
import MultiSelectField from "./Admin/MultiSelectField";
import Pagination from "./Admin/Pagination";
import CategoryPage from "./Admin/Category/CategoryPage";
import ExportPage from "./Admin/Export/ExportPage";
import FossilPage from "./Admin/Fossil/FossilPage";
import FossilFormFieldPage from "./Admin/FossilFormField/FossilFormFieldPage";
import ImportPage from "./Admin/Import/ImportPage";
import SeriesPage from "./Admin/Series/SeriesPage";
import StagePage from "./Admin/Stage/StagePage";
import SystemPage from "./Admin/System/SystemPage";
import TagPage from "./Admin/Tag/TagPage";
import Translation from "./translations/Translation";
import translation from "./translations/messages.json";
import defaultTranslation from "./translations/messages_defaults.json";

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

    document.loadingIndicator.hide();
});