<div ng-controller="CustomerCreateController">
    <div id="add-cus-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
        <div class="cus-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Customer</h3>
            <h6 class="card-subtitle text-white">Create new customer and associate to your shop</h6>
        </div>
        <div class="card-body" id="add-cus-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title">Basic Details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group with-m-b"
                                 ng-class="hasError('salutation') ? 'has-danger' : ''">
                                <label class="control-label">Salutation</label>
                                <div class="ui fluid normal selection dropdown salutation-drop-down"
                                     ng-class="hasError('salutation') ? 'error' : ''">
                                    <input name="salutation" type="hidden" value="">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a salutation</div>
                                    <div class="menu">
                                        @foreach(salutationDropDown() as $key => $salutation)
                                            <div class="item" data-value="{{ $key }}">{{ $salutation }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('salutation') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('first_name') ? 'has-danger' : ''">
                                <label for="first_name" class="control-label form-control-label">First name</label>
                                <input ng-model="customer.first_name" class="form-control"
                                       placeholder="enter first name" name="first_name" type="text" id="first_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('first_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('last_name') ? 'has-danger' : ''">
                                <label for="last_name" class="control-label form-control-label">Last name</label>
                                <input ng-model="customer.last_name" class="form-control" placeholder="enter last name"
                                       name="last_name" type="text" id="last_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('last_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('display_name') ? 'has-danger' : ''">
                                <label for="display_name" class="control-label form-control-label">Display name</label>
                                <input ng-model="customer.display_name" class="form-control"
                                       placeholder="enter display name" name="display_name" type="text"
                                       id="display_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('display_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('phone') ? 'has-danger' : ''">
                                <label for="phone" class="control-label form-control-label">Phone no</label>
                                <input ng-model="customer.phone" class="form-control" placeholder="eg: 0215555551"
                                       name="phone" type="text" id="phone">
                                <p class="form-control-feedback">@{{ getErrorMsg('phone') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('mobile') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Mobile no</label>
                                <input ng-model="customer.mobile" class="form-control" placeholder="eg: 0775555553"
                                       name="mobile" type="text" id="mobile">
                                <p class="form-control-feedback">@{{ getErrorMsg('mobile') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="saveCustomer($event)">
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
@section('script')
    @parent
    @include('_inc.cash-customer._inc.script')
@endsection