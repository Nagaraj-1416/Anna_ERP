@extends('layouts.master')
@section('title', 'Account Balances')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Account Balances') !!}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-b-0">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="card-title"><i class="ti-receipt"></i> Account Balances</h3>
                        <h6 class="card-subtitle">Should print the date range here...</h6>
                    </div>
                    <div class="pull-right"></div>
                </div>
            </div>
            <hr>

            <div class="card-body p-b-5">
                <div class="form-filter">

                </div>
                <div class="clearfix m-t-10">
                    <div class="pull-left">
                        <button class="btn btn-info"><i class="ti-filter"></i>
                            Generate
                        </button>
                        <button class="btn btn-inverse"><i class="ti-eraser"></i>
                            Reset
                        </button>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn waves-effect waves-light btn-inverse"><i class="fa fa-file-pdf-o"></i> Export to PDF</a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="ui celled structured table collapse-table">
                    <thead>
                        <tr>
                            <th>ACCOUNT</th>
                            <th class="text-right">STARTING BALANCE</th>
                            <th class="text-right">DEBIT</th>
                            <th class="text-right">CREDIT</th>
                            <th class="text-right">NET MOVEMENT</th>
                            <th class="text-right">ENDING BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($accounts)
                            @foreach($accounts as $cat => $account)
                                <tr style="background-color: #e0e7eb;">
                                    <td colspan="6">
                                        <b>{{ getAccCat($cat)->name }}</b>
                                    </td>
                                </tr>
                                @foreach($account as $accKey => $accVal)
                                    <tr>
                                        <td>{{ $accVal->name or 'None' }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
