<div id="customers-sidebar" class="card card-outline-info disabled-dev" ng-controller="AddCustomerController" style="border: none !important;">
    <div class="cus-create-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h3 class="m-b-0 text-white">Customers</h3>
        <h6 class="card-subtitle text-white">Add customers to allocation</h6>
    </div>
    <div class="card-body" id="add-cus-body">
        <div class="form">
            <div class="form-body">
                <div class="alert alert-danger" ng-show="errors.hasOwnProperty('unauthorized')">
                    <h5 class="text-danger">
                        <i class="fa fa-exclamation-circle"></i> This action is unauthorized.
                    </h5>
                </div>

                <div class="row cheque-data">
                    <div class="col-md-12">
                        <div class="form-group required" ng-class="hasError('customers') ? 'has-danger' : ''">
                            <label for="customers" class="control-label form-control-label">Customers</label>
                            <div class="ui fluid search selection multiple dropdown customers-dropdown" >
                                <input type="hidden" name="customers">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose customers</div>
                                <div class="menu">
                                    @foreach(customerDropDown() as $key => $customer)
                                        <div class="item" data-value="{{ $key }}">{{ $customer }}</div>
                                    @endforeach
                                </div>
                            </div>
                            <p class="form-control-feedback">@{{ getErrorMsg('customers') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <button type="button"
                                class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                data-ng-click="submitForm($event)">
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