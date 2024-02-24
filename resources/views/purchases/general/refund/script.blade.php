<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    $refundFormEl = {
        refundBtn: $('.refundBtn')
    };

    function setup() {
        $('.refund-form').fadeOut();

    }

    $('#' + '{{ $btnName }}').click(function (e) {
        showForm('{{ isset($formName) ? $formName : 'refundForm' }}');
    });

    $refundFormEl.refundBtn.click(function (e) {
        e.preventDefault();
        setup();
    });

    @if (old('refund_'. $varName))
    setup();
    $('#' + '{{ isset($formName) ? $formName : 'refundForm' }}').fadeIn();
    @endif
    @if(isset($formName) && $formName == 'paymentRefundForm')
    $('.refund-payment-btn').click(function () {
        var route = '{{ $route }}';
        var id = $(this).data('id');
        var form = $('#' + '{{ isset($formName) ? $formName : 'refundForm' }}').find('form');
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