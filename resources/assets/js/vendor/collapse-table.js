$(document).ready(function () {
    //Fixing jQuery Click Events for the iPad
    var ua = navigator.userAgent,
        event = (ua.match(/iPad/i)) ? "touchstart" : "click";
    if ($('.collapse-table').length > 0) {
        $('.collapse-table .parent-row').on(event, function () {
            $(this).toggleClass("active", "").nextUntil('.parent-row').css('display', function (i, v) {
                return this.style.display === 'table-row' ? 'none' : 'table-row';
            });
        });
    }
});