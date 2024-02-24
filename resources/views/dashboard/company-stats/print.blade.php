@extends('layouts.master')
@section('title', 'Print View')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Print View') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <a href="" onclick="printDiv('companyStats')"
                           class="btn waves-effect waves-light btn-dark btn-sm"><i class="fa fa-print"></i> Print</a>
                        <a href="{{ route('company.stats.export', $param) }}"
                            class="btn waves-effect waves-light btn-inverse btn-sm">
                            <i class="fa fa-file-pdf-o"></i> Export to PDF
                        </a>
                        <hr>
                        <br/>
                        @include('dashboard.company-stats.export')
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