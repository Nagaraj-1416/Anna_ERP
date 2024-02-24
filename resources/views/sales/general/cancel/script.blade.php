<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $cancelFormEl = {
        cancelBtn: $('.cancelBtn')
    };

    function setup() {
        $('.cancel-form').fadeOut();

    }

    $('#' + '{{ $btnName }}').click(function (e) {
        showForm('{{ isset($formName) ? $formName : 'cancelForm' }}');
    });

    $cancelFormEl.cancelBtn.click(function (e) {
        e.preventDefault();
        setup();
    });

    @if (old($varName))
    setup();
    $('#' + '{{ isset($formName) ? $formName : 'cancelForm' }}').fadeIn();
    @endif
    @if(isset($formName) && $formName == 'paymentCancelForm')
    $('.cancel-payment-btn').click(function () {
        var route = '{{ $route }}';
        var id = $(this).data('id');
        var form = $('#' + '{{ isset($formName) ? $formName : 'cancelForm' }}').find('form');
        form.attr('action', route.replace('PAYMENT', id));
        showForm('{{$formName}}');
    });

    @endif

    function showForm(form) {
        setup();
        $('#' + form).fadeIn();
        $('html, body').animate({
            scrollTop: $('.row.main').offset().top - 150
        }, 1000);
    }
</script>