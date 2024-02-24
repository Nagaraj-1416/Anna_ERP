<div id="add-opening-sidebar" class="card card-outline-info disabled-dev">
    <div class="designation-create-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h4 class="m-b-0 text-white">Enter Opening Balance References</h4>
    </div>
    <div class="card-body" id="add-designation-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group required"
                     ng-class="getRefError('invoice_no') ? 'has-danger' : ''">
                    <label for="open-invoice-no"
                           class="control-label form-control-label">Invoice#</label>
                    <input ng-model="referenceItem.invoice_no" class="form-control"
                           placeholder="invoice#" name="invoice_no" type="text" min="0"
                           id="open-invoice-no">
                    <p class="form-control-feedback">@{{ getRefError('invoice_no') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required"
                     ng-class="getRefError('invoice_date') ? 'has-danger' : ''">
                    <label for="open-invoice-date"
                           class="control-label form-control-label">Invoice date</label>
                    <input ng-model="referenceItem.invoice_date" ng-change="invoiceDateChange()"
                           class="form-control datepicker"
                           placeholder="invoice date" name="invoice_date" type="text" min="0"
                           id="open-invoice-no">
                    <p class="form-control-feedback">@{{ getRefError('invoice_date') }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group required"
                     ng-class="getRefError('invoice_amount') ? 'has-danger' : ''">
                    <label for="open-invoice-amount"
                           class="control-label form-control-label">Invoice amount</label>
                    <input  ng-model="referenceItem.invoice_amount" class="form-control"
                           placeholder="invoice amount" name="invoice_amount" type="number" min="0"
                           id="open-invoice-amount">
                    <p class="form-control-feedback">@{{ getRefError('invoice_amount') }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group required"
                     ng-class="getRefError('invoice_due') ? 'has-danger' : ''">
                    <label for="open-invoice-due"
                           class="control-label form-control-label ">Due amount</label>
                    <input ng-model="referenceItem.invoice_due" class="form-control"
                           placeholder="due amount" name="invoice_due" type="number" min="0"
                           id="open-invoice-due">
                    <p class="form-control-feedback">@{{ getErrorMsg('invoice_due') }}</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group required"
                     ng-class="getRefError('invoice_due_age') ? 'has-danger' : ''">
                    <label for="open-invoice-due-age"
                           class="control-label form-control-label">Due age</label>
                    <input readonly ng-model="referenceItem.invoice_due_age" class="form-control"
                           placeholder="due age" name="invoice_due_age" type="text" min="0"
                           id="open-invoice-due-age">
                    <p class="form-control-feedback">@{{ getRefError('invoice_due_age') }}</p>
                </div>
            </div>
        </div>
        <div class="row m-t-20">
            <div class="col-md-12">
                <h5>Sold products details</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table color-table">
                    <thead>
                        <tr>
                            <td>Product</td>
                            <td style="width: 10%;">Qty</td>
                            <td class="text-right" style="width: 15%;">Rate</td>
                            <td class="text-right" style="width: 15%;">Amount</td>
                            <td style="width: 5%;"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(key, product) in referenceItem.products" product-loop ng-init="productIndex = $index">
                            @include('sales.customer.opening._inc.ref-item')
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <span class="btn btn-primary pull-right btn-sm" ng-click="addProduct($index)">
                    <i class="fa fa-plus"></i> Add more product
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
                <button type="button"
                        class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                        data-ng-click="saveReference($event, refIndex)">
                    <i class="fa fa-check"></i>
                    Submit
                </button>
                <button type="button" class="btn btn-inverse waves-effect waves-light"
                        data-ng-click="closeSideBar($event)">
                    <i class="fa fa-remove"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>
