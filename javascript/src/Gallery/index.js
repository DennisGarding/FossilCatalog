import ClearSearchTerm from "../Admin/ClearSearchTerm";
import MultiSelectField from "../Admin/MultiSelectField";
import Pagination from "../Admin/Pagination";

document.addEventListener("DOMContentLoaded", function () {
    new ClearSearchTerm();

    new Pagination();

    const multiSelectFields = document.querySelectorAll('select[data-multi-select-plugin="true"]');
    multiSelectFields.forEach(function (field) {
        new MultiSelectField(field);
    });
});