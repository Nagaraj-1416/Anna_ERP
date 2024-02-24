<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    @php
        $customer = isset($credit) && $credit->customer ? $credit->customer->toArray() : [];
        $businessType = isset($credit) && $credit->businessType ? $credit->businessType->toArray() : [];
        $invoice = isset($credit) &&  $credit->invoice ? $credit->invoice->toArray() : [];
    @endphp;
    @php
        if (old('_token')) {
            if (old('business_type_id') && old('business_type_name')) {
                $businessType = ['id' => old('business_type_id'), 'name' => old('business_type_name')];
            }
            if (old('customer_id') && old('customer_name')) {
                $customer = ['id' => old('customer_id'), 'display_name' => old('customer_name')];
            }
            if (old('invoice_id') && old('invoice_name')) {
                $invoice = ['id' => old('invoice_id'), 'invoice_no' => old('invoice_name')];
            }
        }
    @endphp;
    var customer = @json($customer);
    var businessType = @json($businessType);
    var invoice = @json($invoice);

    var elem = {
        bTDropDown: $('.bt-drop-down'),
        customerDD: $('.customer-drop-down'),
        referenceDD: $('.reference-drop-down')
    };
    var refRouteParam = {
        customerId: null,
        btId: null
    };
    var urls = {
        bTDropDown: '{{ route('setting.business.type.search') }}',
        customerDD: '{{ route('sales.customer.search') }}',
        referenceDD: '{{ route('sales.invoice.reference.search', ['businessType' => 'BT', 'customer' => 'CUS']) }}'
    };
    elem.bTDropDown.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false,
        apiSettings: {
            url: urls.bTDropDown + '/{query}',
            cache: false
        },
        onChange: function (val) {
            refRouteParam.btId = val;
            initReferenceDD();
        }
    });

    elem.customerDD.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false,
        apiSettings: {
            url: urls.customerDD + '/{query}',
            cache: false
        },
        onChange: function (val) {
            refRouteParam.customerId = val;
            initReferenceDD();
        }
    });

    function initReferenceDD() {
        elem.referenceDD.dropdown('clear').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: urls.referenceDD.replace('BT', refRouteParam.btId).replace('CUS', refRouteParam.customerId) + '/{query}',
                cache: false
            }
        });
    }

    if (customer) {
        elem.customerDD
            .dropdown('set text', customer.display_name)
            .dropdown('set value', customer.id);
    }
    if (businessType) {
        elem.bTDropDown
            .dropdown('set text', businessType.name)
            .dropdown('set value', businessType.id);
    }
    if (invoice) {
        elem.referenceDD
            .dropdown('set text', invoice.invoice_no)
            .dropdown('set value', invoice.id);
    }
</script>