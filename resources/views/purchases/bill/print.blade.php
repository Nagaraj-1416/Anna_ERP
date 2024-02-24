@extends('layouts.master')
@section('title', 'Print Bill')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <a href="" onclick="printDiv('bill')"
                           class="btn waves-effect waves-light btn-dark btn-sm"><i class="fa fa-print"></i> Print</a>
                        @can('export', $bill)
                            <a href="{{ route('purchase.bill.export', [$bill]) }}"
                               class="btn waves-effect waves-light btn-inverse btn-sm">
                                <i class="fa fa-file-pdf-o"></i> Export to PDF
                            </a>
                        @endcan
                        @can('show', $bill)
                            <a href="{{ route('purchase.bill.show', [$bill]) }}"
                               class="btn waves-effect waves-light btn-dark btn-sm">
                                <i class="fa fa-shopping-cart"></i> Go to Bill
                            </a>
                        @endcan
                        <hr>
                        <br/>
                        <br/>
                        @include('purchases.bill.export')
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