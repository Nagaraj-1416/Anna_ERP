@extends('layouts.master')
@section('title', 'Print Order')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <a href="" onclick="printDiv('salesOrder')" class="btn waves-effect waves-light btn-dark btn-sm"><i class="fa fa-print"></i> Print</a>
                    <a href="{{ route('sales.order.export', [$order]) }}" class="btn waves-effect waves-light btn-inverse btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                    </a>
                    <a href="{{ route('sales.order.show', [$order]) }}" class="btn waves-effect waves-light btn-dark btn-sm">
                        <i class="fa fa-shopping-cart"></i> Go to Order
                    </a>
                    <hr>
                    <br />
                    <br />
                    @include('sales.order.export')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection