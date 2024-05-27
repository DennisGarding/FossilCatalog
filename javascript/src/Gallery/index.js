import ClearSearchTerm from "../Admin/ClearSearchTerm";
import MultiSelectField from "../Admin/MultiSelectField";
import Pagination from "../Admin/Pagination";
import ImageCarousel from "./ImageCarousel";
import Counter from "./Counter";

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

    const consent = document.querySelector('cookie-consent');
    new Counter(consent);
    // TODO: REMOVE AFTER DEBUG
    console.log('bla');
    // TODO: REMOVE AFTER DEBUG
    // consent.addEventListener('close', function (om, er, dsd) {
    //     // TODO: REMOVE AFTER DEBUG
    //     console.log(om);
    //     console.log(er);
    //     console.log(dsd);
    //     // TODO: REMOVE AFTER DEBUG
    // });
});