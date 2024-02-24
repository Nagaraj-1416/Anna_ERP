<div id="assign_product_wizard" class="hidden">
    <hr>
    <form action="{{ $actionURL }}" method="POST">
        {!! csrf_field() !!}
        <div class="row">
            <div class="col-md-12">
                <div class="ui fluid search selection dropdown {{ ($errors->has('products')) ? 'error' : '' }}"
                     id="product_drop_down">
                    <input type="hidden" name="products">
                    <i class="dropdown icon"></i>
                    <div class="default text">choose products to assign</div>
                    <div class="menu"></div>
                </div>
            </div>
        </div>
        <div id="formData">

        </div>
        <div class="row">
            <div class="col-md-12 m-t-10">
                <button type="Submit" class="btn btn-success pull-right m-l-5"><i class="fa fa-check"></i> Assign
                </button>
                <button type="button" class="btn btn-inverse pull-right" id="assign_product_close_btn"><i
                            class="fa fa-remove"></i> Cancel
                </button>
            </div>
        </div>
    </form>
    <hr>
</div>

<div class="hidden" id="product_template">
    <div class="row m-t-10" id="formElem">
        <div class="col-md-6">
            {{ form()->bsText('name', 'Name', null, ['readonly' => true]) }}
        </div>
        <div class="col-md-6">
            {{ form()->bsText('products[PRODUCT][default_qty]', 'Default Qty', null, ['placeholder' => 'enter product default qty']) }}
        </div>
    </div>
</div>

@section('script')
    @parent
    <script>
        $assignProEl = {
            btn: $('#assign_products'),
            wizard: $('#assign_product_wizard'),
            dropDown: $('#product_drop_down'),
            closeBtn: $('#assign_product_close_btn')
        };
        var productRoute = '{{ route('setting.product.show', ['ID']) }}';
        var products = [];
        $assignProEl.btn.click(function (e) {
            e.preventDefault();
            $assignProEl.wizard.removeClass('hidden');
        });

        $assignProEl.closeBtn.click(function (e) {
            e.preventDefault();
            $assignProEl.wizard.addClass('hidden');
        });

        @if ($errors->has('products'))
        $assignProEl.wizard.removeClass('hidden');
        @endif

        $assignProEl.dropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false,
            apiSettings: {
                url: '{{ $searchURL }}/{query}',
                cache: false
            },
            onChange: function (val) {
                if (val) {
                    $.get(productRoute.replace('ID', val), function (response) {
                        if (!products.hasOwnProperty(val)) {
                            products[val] = response;
                            addProduct(response);
                        }
                    });
                }
                $assignProEl.dropDown.dropdown('clear');
            }
        });

        function addProduct(product) {
            var form = $('#product_template').find('#formElem').clone();
            var append = $('#formData');
            var name = product.name + '(' + product.code + ')';
            form.html(form.html().replace(/PRODUCT/g, product.id));
            form.find('#name').val(name);
            append.append(form)
        }
    </script>
@endsection