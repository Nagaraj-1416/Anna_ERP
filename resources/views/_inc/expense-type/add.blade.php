<div ng-controller="ExpenseTypeController">
    <div id="add-expense-type-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
        <div class="expense-type-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Expense Type</h3>
            <h6 class="card-subtitle text-white">Create new expense type and associate</h6>
        </div>
        <div class="card-body" id="add-expense-type-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('name') ? 'has-danger' : ''">
                                <label for="name" class="control-label form-control-label">Name</label>
                                <input ng-model="expenseType.name" class="form-control" placeholder="enter type name" name="name" type="text" id="name">
                                <p class="form-control-feedback">@{{ getErrorMsg('name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('account_id') ? 'has-danger' : ''">
                                <label class="control-label">Account</label>
                                <div class="ui fluid  search selection dropdown account-drop-down"  ng-class="hasError('account_id') ? 'error' : ''">
                                    <input type="hidden" name="account_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a account</div>
                                    <div class="menu"></div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('account_id') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('is_mobile_enabled') ? 'has-danger' : ''">
                                <label class="control-label">Is mobile enabled?</label>
                                <div class="demo-radio-button">
                                    <input name="is_mobile_enabled" ng-model="expenseType.is_mobile_enabled" value="Yes" type="radio" class="with-gap expense-items" id="m-yes">
                                    <label for="m-yes">Yes</label>
                                    <input name="is_mobile_enabled" ng-model="expenseType.is_mobile_enabled" value="No" type="radio" class="with-gap expense-items" id="m-no">
                                    <label for="m-no">No</label>
                                </div>
                                <p class="form-control-feedback">{{ $errors->first('is_mobile_enabled') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('description') ? 'has-danger' : ''">
                                <label for="description" class="control-label form-control-label">Description</label>
                                <textarea name="notes" placeholder="enter type related description..." ng-model="expenseType.description" id="description" cols="30" rows="6" class="form-control" ></textarea>
                                <p class="form-control-feedback">@{{ getErrorMsg('description') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button" class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar" data-ng-click="saveExpenseType($event)">
                                <i class="fa fa-check"></i>
                                Submit
                            </button>
                            <button type="button" class="btn btn-inverse waves-effect waves-light" data-ng-click="closeSideBar($event)">
                                <i class="fa fa-remove"></i> Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    @parent
    @include('_inc.expense-type._inc.script')
@endsection