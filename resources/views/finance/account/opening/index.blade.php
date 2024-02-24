<div ng-controller="OpeningController">
    <div id="add-opening-sidebar" class="card card-outline-info disabled-dev">
        <div class="designation-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Opening Balance Details</h3>
        </div>
        <div class="card-body" id="add-designation-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group required" ng-class="hasError('opening') ? 'has-danger' : ''">
                                        <label for="opening" class="control-label form-control-label">Opening</label>
                                        <input ng-model="opening.opening" class="form-control text-right"
                                               placeholder="0.00" name="opening" type="number" min="0"
                                               id="opening">
                                        <p class="form-control-feedback">@{{ getErrorMsg('opening') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group required"
                                         ng-class="hasError('opening_at') ? 'has-danger' : ''">
                                        <label for="opening_at" class="control-label form-control-label">Opening
                                            at</label>
                                        <input ng-model="opening.opening_at" class="form-control datepicker"
                                               placeholder="opening at" name="opening_at" type="text" min="0"
                                               id="opening_at">
                                        <p class="form-control-feedback">@{{ getErrorMsg('opening_at') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input type="hidden" name="business_type_id" value="1"
                                           ng-class="hasError('balance_type') ? 'has-danger' : ''">
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
                                        <p class="form-control-feedback">@{{ getErrorMsg('balance_type') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" ng-repeat="(key, reference) in opening.references"
                                     reference-loop>
                                    <div class="row">
                                        {{--<div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Customer</label>
                                                <div class="ui fluid action input">
                                                    <div class="ui fluid search selection dropdown cus-drop-down">
                                                        <input type="hidden" name="customer_id">
                                                        <i class="dropdown icon"></i>
                                                        <div class="default text">choose a customer</div>
                                                        <div class="menu"></div>
                                                    </div>
                                                </div>
                                                <p class="form-control-feedback"></p>
                                            </div>
                                        </div>--}}
                                        {{--<div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Supplier</label>
                                                <div class="ui fluid action input">
                                                    <div class="ui fluid search selection dropdown sup-drop-down">
                                                        <input type="hidden" name="supplier_id">
                                                        <i class="dropdown icon"></i>
                                                        <div class="default text">choose a supplier</div>
                                                        <div class="menu"></div>
                                                    </div>
                                                </div>
                                                <p class="form-control-feedback"></p>
                                            </div>
                                        </div>--}}
                                        <div class="col-md-8">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.reference_no') ? 'has-danger' : ''">
                                                <label for="reference"
                                                       class="control-label form-control-label">Reference </label>
                                                <input ng-model="reference.reference_no" class="form-control"
                                                       placeholder="reference" name="reference" type="text" min="0"
                                                       id="reference">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.reference_no') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.amount') ? 'has-danger' : ''">
                                                <label for="amount"
                                                       class="control-label form-control-label">Amount</label>
                                                <input ng-model="reference.amount" class="form-control"
                                                       placeholder="amount" name="amount" type="text" min="0"
                                                       id="amount">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.amount') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-danger m-t-20" ng-click="removeReference(key)">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" ng-click="addReference()">
                                        <i class="fa fa-plus"></i> Add more reference
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="saveOpening($event)">
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
        </div>
    </div>
</div>
