<div ng-controller="OpeningController">
    <div class="form">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group required"
                     ng-class="getErrorMsg('opening_at') ? 'has-danger' : ''">
                    <label for="opening_at" class="control-label form-control-label">Opening at</label>
                    <input ng-model="opening.opening_at" class="form-control datepicker"
                           placeholder="opening at" name="opening_at" type="text" min="0"
                           id="opening_at">
                    <p class="form-control-feedback">@{{ getErrorMsg('opening_at') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group required" ng-class="getErrorMsg('opening') ? 'has-danger' : ''">
                    <label for="opening" class="control-label form-control-label">Opening</label>
                    <input ng-model="opening.opening" class="form-control text-right"
                           placeholder="0.00" name="opening" type="number" min="0"
                           id="opening">
                    <p class="form-control-feedback">@{{ getErrorMsg('opening') }}</p>
                </div>
            </div>
            <div class="col-md-3">
                <input type="hidden" name="business_type_id" value="1"
                       ng-class="getErrorMsg('balance_type') ? 'has-danger' : ''">
                <div class="form-group required ">
                    <label class="control-label">Balance type</label>
                    <div class="demo-radio-button">
                        <input name="balance_type" value="Debit" type="radio"
                               class="with-gap balance_type"
                               id="Debit" ng-model="opening.balance_type">
                        <label for="Debit">Debit</label>
                        <input name="balance_type" value="Credit" type="radio"
                               class="with-gap balance_type"
                               id="Credit" ng-model="opening.balance_type">
                        <label for="Credit">Credit</label>
                    </div>
                    <p class="text-danger">@{{ getErrorMsg('balance_type') }}</p>
                </div>
            </div>
            <div class="col-md-3 p-t-10">

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <span class="btn btn-sm btn-primary m-r-40" ng-click="addReference()">
                    <i class="fa fa-plus"></i> Click here to add balance references
                </span>
            </div>
        </div>
        <div class="row m-t-15">
            <div class="col-md-12">
                <table class="table color-table muted-table">
                    <thead>
                        <tr>
                            <td style="width: 25%;">Invoice#</td>
                            <td>Invoice date</td>
                            <td class="text-right">Invoice amount</td>
                            <td class="text-right">Due amount</td>
                            <td class="text-right">Due age</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(key, openReferences) in opening.references" ng-init="refIndex = $index" reference-loop>
                            <td>@{{ openReferences.invoice_no }}</td>
                            <td>@{{ openReferences.invoice_date }}</td>
                            <td class="text-right">@{{ openReferences.invoice_amount | number:2 }}</td>
                            <td class="text-right">@{{ openReferences.invoice_due | number:2 }}</td>
                            <td class="text-right">@{{ openReferences.invoice_due_age }}</td>
                            <td class="text-right"><span class="btn btn-info btn-sm" ng-click="editReference(refIndex)">Edit</span></td>
                        </tr>
                        <tr ng-show="opening.references.length == 0">
                            <td colspan="5" ng-class="getErrorMsg('references') ? 'text-danger' : ''">There are no references added.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('sales.customer.opening._inc.add-reference')
</div>
@section('script')
    @include('sales.customer.opening._inc.script')
@endsection
