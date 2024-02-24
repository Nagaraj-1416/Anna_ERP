@extends('layouts.master')
@section('title', 'Confirm Purchase Request')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Purchase') !!}
@endsection
@section('content')
    <div class="row" ng-controller="PoRequestController">
        <div class="col-md-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">{{ $purchaseRequest->request_no }}</h4>
                </div>
                <div class="card-body">
                    {!! form()->model($purchaseRequest, ['url' => route('purchase.request.do.confirm', $purchaseRequest), 'method' => 'PATCH']) !!}
                    <div class="row custom-top-margin">
                        <div class="col-md-8">
                            <div class="card card-body printableArea">
                                <h3>
                                    <b>PURCHASE REQUEST</b> |
                                    <small class="{{ statusLabelColor($purchaseRequest->status) }}">
                                        {{ $purchaseRequest->status }}
                                    </small>
                                    <span class="pull-right">#{{ $purchaseRequest->request_no }}</span></h3>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <address>
                                                <h4><b class="text-danger">{{ $company->name }}</b></h4>
                                                @include('_inc.address.view', ['address' => $companyAddress])
                                            </address>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                            <p><b>Request Date :</b> {{ $purchaseRequest->request_date }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive" style="clear: both;">
                                            <table class="table color-table inverse-table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 3%;"></th>
                                                    <th class="text-center" style="width: 3%;">#</th>
                                                    <th>Items & Description</th>
                                                    <th class="text-center" style="width: 25%;">Quantity</th>
                                                    <th class="text-right" style="width: 25%;">Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($items))
                                                    @foreach($items as $itemKey => $item)
                                                        <tr>
                                                            <td style="width: 3%;">
                                                                <div class="demo-checkbox" style="width: 20px !important;">
                                                                    <input type="checkbox" id="{{ 'md_checkbox_29_' . $item->product_id }}"
                                                                           name="items[product_id][{{ $item->product_id }}]"
                                                                           class="chk-col-cyan item-check">
                                                                    <label for="{{ 'md_checkbox_29_' . $item->product_id }}"></label>
                                                                </div>
                                                            </td>
                                                            <td class="text-center" style="width: 3%;">{{ $itemKey+1 }}</td>
                                                            <td>
                                                                {{ $item->product->name }}
                                                            </td>
                                                            <td class="text-center">{{ $item->quantity }}</td>
                                                            <td class="text-right">{{ $item->status }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- recent logs -->
                        <div class="col-md-4">
                            <div class="card border-warning po-approval-panel">
                                <div class="card-body">
                                    <h3 class="card-title text-danger text-center"><i class="fa fa-clock-o"></i> Pending</h3>
                                    <p class="card-subtitle text-center"> This purchase request is waiting for your confirmation for move to production.</p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group required {{ $errors->has('supplier_id') ? 'has-danger' : '' }}">
                                                <label class="control-label">Supplier</label>
                                                <div class="ui fluid search selection dropdown supplier-drop-down {{ $errors->has('supplier_id') ? 'error' : '' }}">
                                                    <input type="hidden" name="supplier_id">
                                                    <i class="dropdown icon"></i>
                                                    <div class="default text">choose a supplier</div>
                                                    <div class="menu">
                                                        {{--@if($purchaseRequest->request_for == 'PUnit')
                                                            @foreach(pUnitSuppliersDropDown() as $key => $supplier)
                                                                <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                                                            @endforeach
                                                        @endif--}}
                                                        @if($purchaseRequest->request_for == 'Store')
                                                            @foreach(pUnitAndStoreSuppliersDropDown() as $key => $supplier)
                                                                <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                                                            @endforeach
                                                        @endif
                                                        @if($purchaseRequest->request_for == 'Shop')
                                                            @foreach(shopSuppliersDropDown() as $key => $supplier)
                                                                <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                                                            @endforeach
                                                        @endif
                                                        {{--@foreach(supplierDropDown() as $key => $supplier)
                                                            <div class="item" data-value="{{ $key }}">{{ $supplier }}</div>
                                                        @endforeach--}}
                                                    </div>
                                                </div>
                                                <p class="form-control-feedback">{{ $errors->first('supplier_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group required">
                                                <label class="control-label">Mode of purchase order</label>
                                                <div class="demo-radio-button">
                                                    <input name="po_mode" value="Internal" type="radio" class="with-gap" id="Internal" checked="" {{ (old('po_mode') == 'Internal') ? 'checked' : ''}}>
                                                    <label for="Internal">Internal</label>
                                                    <input name="po_mode" value="Virtual" type="radio" class="with-gap" id="Virtual" {{ (old('po_mode') == 'Virtual') ? 'checked' : ''}}>
                                                    <label for="Virtual">Virtual</label>
                                                    <input name="po_mode" value="Outside" type="radio" class="with-gap" id="Outside" {{ (old('po_mode') == 'Outside') ? 'checked' : ''}}>
                                                    <label for="Outside">Outside</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div>
                                        <div class="pull-left">
                                            {!! form()->bsSubmit('Confirm Request', 'btn btn-success waves-effect waves-light m-r-10', 'Confirm', 'submit') !!}
                                        </div>
                                        <div class="pull-right">
                                            <a class="btn btn-inverse" href="{{ route('purchase.order.request') }}">
                                                <i class="fa fa-arrow-left"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        app.controller('PoRequestController', function ($scope, $timeout, $http) {
            $scope.dropdowns = {
                supplier: $('.supplier-drop-down')
            };
            $scope.dropdowns.supplier.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });
        });
    </script>
@endsection