@extends('layouts.master')
@section('title', 'Print Expense Report')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Expense') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <a href="" onclick="printDiv('expenseReport')" class="btn waves-effect waves-light btn-dark btn-sm"><i class="fa fa-print"></i> Print</a>
                    <a href="{{ route('expense.reports.export', [$report]) }}" class="btn waves-effect waves-light btn-inverse btn-sm">
                        <i class="fa fa-file-pdf-o"></i> Export to PDF
                    </a>
                    <hr>
                    <br />
                    <br />
                    @include('expense.reports.export')
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