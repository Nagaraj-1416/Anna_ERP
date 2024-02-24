<script src="{{ asset('js/vendor/basic.js') }}"></script>
<script src="{{ asset('js/theme/script.js') }}"></script>
@if (Session::has('sweet_alert.alert'))
    <script>
        swal({!! Session::get('sweet_alert.alert') !!});
    </script>
@endif

<script>
    const app = angular.module('app', ['angularUtils.directives.dirPagination']);
    app.config(['$httpProvider', function($httpProvider) {
        $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
    }]);
    app.config(function(paginationTemplateProvider) {
        paginationTemplateProvider.setPath('/template/dirPagination.tpl.html');
    });

    $('#logScroll').slimScroll({
        height: '250px'
    });

    $('.submission-disable').closest('form').submit(function () {
        $('.submission-disable').prop('disabled', true);
    });

</script>

@yield('script')
