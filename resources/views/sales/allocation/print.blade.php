@extends('layouts.master')
@section('title', 'Print Allocation')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <a href="" onclick="printDiv('salesAllocation')"
                           class="btn waves-effect waves-light btn-dark btn-sm"><i class="fa fa-print"></i> Print</a>
                        @can('export')
                            <a href="{{ route('sales.allocation.export', [$allocation]) }}"
                               class="btn waves-effect waves-light btn-inverse btn-sm">
                                <i class="fa fa-file-pdf-o"></i> Export to PDF
                            </a>
                        @endcan
                        <a href="{{ route('sales.allocation.show', [$allocation]) }}"
                           class="btn waves-effect waves-light btn-dark btn-sm">
                            <i class="fa fa-shopping-cart"></i> Go to Allocation
                        </a>
                        <hr>
                        <br/>
                        <br/>
                        @include('sales.allocation.export')
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