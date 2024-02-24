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
                                        <label for="opening_at" class="control-label form-control-label">Opening at</label>
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
                                        <div class="col-md-3">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.bill_no') ? 'has-danger' : ''">
                                                <label for="open-bill-no"
                                                       class="control-label form-control-label">Bill no</label>
                                                <input ng-model="reference.bill_no" class="form-control"
                                                       placeholder="bill no" name="bill_no" type="text" min="0"
                                                       id="open-bill-no">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.bill_no') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.bill_date') ? 'has-danger' : ''">
                                                <label for="open-bill-date"
                                                       class="control-label form-control-label">Bill date</label>
                                                <input ng-model="reference.bill_date" class="form-control datepicker"
                                                       placeholder="bill date" name="bill_date" type="text" min="0"
                                                       id="open-bill-no">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.bill_date') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.bill_amount') ? 'has-danger' : ''">
                                                <label for="open-bill-amount"
                                                       class="control-label form-control-label">Bill amount</label>
                                                <input ng-model="reference.bill_amount" class="form-control"
                                                       placeholder="bill amount" name="bill_amount" type="number" min="0"
                                                       id="open-bill-amount">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.bill_amount') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.bill_due') ? 'has-danger' : ''">
                                                <label for="open-bill-due"
                                                       class="control-label form-control-label ">Bill due</label>
                                                <input ng-model="reference.bill_due" class="form-control"
                                                       placeholder="bill due" name="bill_due" type="number" min="0"
                                                       id="open-bill-due">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.bill_due') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group required"
                                                 ng-class="hasError('references.'+ key +'.bill_due_age') ? 'has-danger' : ''">
                                                <label for="open-bill-due-age"
                                                       class="control-label form-control-label">Bill due age</label>
                                                <input ng-model="reference.bill_due_age" class="form-control"
                                                       placeholder="bill due age" name="bill_due_age" type="number" min="0"
                                                       id="open-bill-due-age">
                                                <p class="form-control-feedback">@{{ getErrorMsg('references.'+ key
                                                    +'.bill_due_age') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button class="btn btn-danger m-t-20" ng-click="removeReference(key)">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr>
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
                            <br>
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
