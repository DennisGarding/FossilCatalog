import ClearSearchTerm from "../Admin/ClearSearchTerm";
import MultiSelectField from "../Admin/MultiSelectField";
import Pagination from "../Admin/Pagination";
import ImageCarousel from "./ImageCarousel";

document.addEventListener("DOMContentLoaded", function () {
    new ClearSearchTerm();

    new Pagination();

    const thumbnails = document.querySelectorAll('.gallery-fossil-thumbnail');
    thumbnails.forEach(function (thumbnail) {
        new ImageCarousel(thumbnail);
    });

    const multiSelectFields = document.querySelectorAll('select[data-multi-select-plugin="true"]');
    multiSelectFields.forEach(function (field) {
        new MultiSelectField(field);
    });
});