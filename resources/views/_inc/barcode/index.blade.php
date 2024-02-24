<div class="row">
    <div class="col-md-12">
        <div class="text-center">
            @if($product->barcode_number)
                <div>
                    <svg class="barcode" id="barcode"
                         jsbarcode-format="ean13"
                         jsbarcode-value="{{ $product->barcode_number ?? '' }}"
                         jsbarcode-textmargin="0"
                         jsbarcode-height="60"
                         jsbarcode-width="2"
                         jsbarcode-fontoptions="bold"
                         jsbarcode-fontsize="22"
                    >
                    </svg>
                </div>
                {{--<img src="{{ route('setting.product.barcode.image', $product->id) }}" alt="barcode"   />--}}
                <div>
                    <button class="btn btn-sm btn-primary" id="download-barcode" type="button">Download</button>
                    {{--<a class="btn btn-sm pull-right btn-primary" href="{{ route('setting.product.barcode.download', $product->id) }}">Download</a>--}}
                </div>
            @else
                <p class="text-muted text-left">Barcode not founded!</p>
            @endif
        </div>
    </div>
</div>


@section('script')
    @parent
    <script src="{{ asset('js/vendor/barcode.js') }}"></script>
    <script>
        JsBarcode(".barcode").init();
        $("#download-barcode").click(function () {
            var svg = document.getElementById("barcode");
            var svg_xml = (new XMLSerializer).serializeToString(svg);   // extract the data as SVG text
            var data_uri = "data:image/svg+xml;base64," + window.btoa(svg_xml);
            var image = new Image;
            image.src = data_uri;
            image.onload = function () {
                var canvas = document.createElement("canvas");
                canvas.width = image.width;
                canvas.height = image.height;

                var context = canvas.getContext("2d");
                context.clearRect(0, 0, image.width, image.height);
                context.drawImage(image, 0, 0);

                var a = document.createElement("a");
                a.download = "{{$product->name ?? 'barcode'}}.png";
                a.href = canvas.toDataURL("image/png");
                a.click();
            };
        });
    </script>
@endsection
