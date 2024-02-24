<script src="{{ asset('js/vendor/form.js') }}"></script>
<script>
    @php
        $supplier = isset($credit) && $credit->supplier ? $credit->supplier->toArray() : [];
        $businessType = isset($credit) && $credit->businessType ? $credit->businessType->toArray() : [];
        $bill = isset($credit) &&  $credit->bill ? $credit->bill->toArray() : [];
    @endphp;
    @php
        if (old('_token')) {
            if (old('business_type_id') && old('business_type_name')) {
                $businessType = ['id' => old('business_type_id'), 'name' => old('business_type_name')];
            }
            if (old('supplier_id') && old('supplier_name')) {
                $supplier = ['id' => old('supplier_id'), 'display_name' => old('supplier_name')];
            }
            if (old('invoice_id') && old('invoice_name')) {
                $invoice = ['id' => old('invoice_id'), 'invoice_no' => old('invoice_name')];
            }
        }
    @endphp;
    var supplier = @json($supplier);
    var businessType = @json($businessType);
    var bill = @json($bill);

    var elem = {
        bTDropDown: $('.bt-drop-down'),
        supplierDD: $('.supplier-drop-down'),
        referenceDD: $('.reference-drop-down')
    };
    var refRouteParam = {
        supplierId: null,
        btId: null
    };
    var urls = {
        bTDropDown: '{{ route('setting.business.type.search') }}',
        supplierDD: '{{ route('purchase.supplier.search') }}',
        referenceDD: '{{ route('purchase.bill.reference.search', ['businessType' => 'BT', 'supplier' => 'SUP']) }}'
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

    elem.supplierDD.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false,
        apiSettings: {
            url: urls.supplierDD + '/{query}',
            cache: false
        },
        onChange: function (val) {
            refRouteParam.supplierId = val;
            initReferenceDD();
        }
    });

    function initReferenceDD() {
        elem.referenceDD.dropdown('clear').dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: urls.referenceDD.replace('BT', refRouteParam.btId).replace('SUP', refRouteParam.supplierId) + '/{query}',
                cache: false
            }
        });
    }

    if (supplier) {
        elem.supplierDD
            .dropdown('set text', supplier.display_name)
            .dropdown('set value', supplier.id);
    }
    if (businessType) {
        elem.bTDropDown
            .dropdown('set text', businessType.name)
            .dropdown('set value', businessType.id);
    }
    if (bill) {
        elem.referenceDD
            .dropdown('set text', bill.bill_no)
            .dropdown('set value', bill.id);
    }
</script>