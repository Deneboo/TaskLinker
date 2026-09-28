function initSelect2() {
    console.log('initSelect2:', $('.js-select2').length);

    $('.js-select2').select2();
}

document.addEventListener('turbo:load', function () {
    console.log('turbo:load');
    initSelect2();
});

$(document).ready(function () {
    console.log('ready');
    initSelect2();
});