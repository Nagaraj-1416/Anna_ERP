@extends('layouts.master')
@section('title', 'Supplier Statement')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <a href="{{ route('purchase.supplier.statement.export', [$supplier]) }}" class="btn waves-effect waves-light btn-inverse btn-sm">
                            <i class="fa fa-file-pdf-o"></i> Export to PDF
                        </a>
                        <a href="{{ route('purchase.supplier.show', [$supplier]) }}" class="btn waves-effect waves-light btn-dark btn-sm">
                            <i class="fa fa-shopping-cart"></i> Go to Supplier
                        </a>
                        <hr>
                        <br />
                        <br />
                        @include('purchases.supplier.statement.preview')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
