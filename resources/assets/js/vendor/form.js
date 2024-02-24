$(document).ready(function () {
    // date picker
    if ($.fn.datepicker) {
        $.fn.datepicker.defaults.format = "yyyy-mm-dd";
        var date = $(".datepicker");
        date.datepicker({
            'autoclose': true,
        });
        // clock picker
        $('.clockpicker').clockpicker({
            'placement': 'bottom',
            'align': 'left',
            'autoclose': true
        });
    }
});