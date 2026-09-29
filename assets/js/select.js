function initSelect2() {
    $('.js-select2').select2();
}

document.addEventListener('turbo:load', function () {
    initSelect2();
});

$(document).ready(function () {
    initSelect2();
});