@extends('layouts.master')
@section('title', 'Customer Statement')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <a href="{{ route('sales.customer.statement.export', [$customer]) }}" class="btn waves-effect waves-light btn-inverse btn-sm">
                            <i class="fa fa-file-pdf-o"></i> Export to PDF
                        </a>
                        <a href="{{ route('sales.customer.show', [$customer]) }}" class="btn waves-effect waves-light btn-dark btn-sm">
                            <i class="fa fa-shopping-cart"></i> Go to Customer
                        </a>
                        <hr>
                        <br />
                        <br />
                        @include('sales.customer.statement.preview')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
