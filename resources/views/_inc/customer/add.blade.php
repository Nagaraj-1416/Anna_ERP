<div ng-controller="CustomerCreateController">
    <div id="add-cus-sidebar" class="card card-outline-inverse disabled-dev" style="border: none !important;">
        <div class="cus-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Customer</h3>
            <h6 class="card-subtitle text-white">Create new customer and associate</h6>
        </div>
        <div class="card-body" id="add-cus-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group with-m-b required"
                                 ng-class="hasError('route_id') ? 'has-danger' : ''">
                                <label class="control-label">Sales route</label>
                                <div class="ui fluid normal search selection dropdown route-drop-down"
                                     ng-class="hasError('route_id') ? 'error' : ''">
                                    <input name="route_id" type="hidden" value="">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a sales route</div>
                                    <div class="menu">
                                        @foreach(routeDropDown() as $key => $route)
                                            <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('route_id') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group with-m-b required"
                                 ng-class="hasError('location_id') ? 'has-danger' : ''">
                                <label class="control-label">Route location</label>
                                <div class="ui fluid normal search selection dropdown location-drop-down"
                                     ng-class="hasError('location_id') ? 'error' : ''">
                                    <input name="location_id" type="hidden" value="">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a route location</div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('location_id') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="box-title">Basic Details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group with-m-b required"
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
                            <div class="form-group required" ng-class="hasError('first_name') ? 'has-danger' : ''">
                                <label for="first_name" class="control-label form-control-label">First name</label>
                                <input ng-model="customer.first_name" class="form-control"
                                       placeholder="enter first name" name="first_name" type="text" id="first_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('first_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('last_name') ? 'has-danger' : ''">
                                <label for="last_name" class="control-label form-control-label">Last name</label>
                                <input ng-model="customer.last_name" class="form-control" placeholder="enter last name"
                                       name="last_name" type="text" id="last_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('last_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('display_name') ? 'has-danger' : ''">
                                <label for="display_name" class="control-label form-control-label">Display name</label>
                                <input ng-model="customer.display_name" class="form-control"
                                       placeholder="enter display name" name="display_name" type="text"
                                       id="display_name">
                                <p class="form-control-feedback">@{{ getErrorMsg('display_name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="box-title">Contact Details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('phone') ? 'has-danger' : ''">
                                <label for="phone" class="control-label form-control-label">Phone no</label>
                                <input ng-model="customer.phone" class="form-control" placeholder="eg: 0215555551"
                                       name="phone" type="text" id="phone">
                                <p class="form-control-feedback">@{{ getErrorMsg('phone') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('fax') ? 'has-danger' : ''">
                                <label for="fax" class="control-label form-control-label">Fax no</label>
                                <input ng-model="customer.fax" class="form-control" placeholder="eg: 0215555552"
                                       name="fax" type="text" id="fax">
                                <p class="form-control-feedback">@{{ getErrorMsg('fax') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('mobile') ? 'has-danger' : ''">
                                <label for="mobile" class="control-label form-control-label">Mobile no</label>
                                <input ng-model="customer.mobile" class="form-control" placeholder="eg: 0775555553"
                                       name="mobile" type="text" id="mobile">
                                <p class="form-control-feedback">@{{ getErrorMsg('mobile') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('email') ? 'has-danger' : ''">
                                <label for="email" class="control-label form-control-label">Email address</label>
                                <input ng-model="customer.email" class="form-control"
                                       placeholder="eg: example@gmail.com" name="email" type="text" id="email">
                                <p class="form-control-feedback">@{{ getErrorMsg('email') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('website') ? 'has-danger' : ''">
                                <label for="website" class="control-label form-control-label">Website</label>
                                <input ng-model="customer.website" class="form-control"
                                       placeholder="https://samplesite.com" name="website" type="text" id="website">
                                <p class="form-control-feedback">@{{ getErrorMsg('website') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="box-title">Customer Address</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('street_one') ? 'has-danger' : ''">
                                <label for="street_one" class="control-label form-control-label">Street one</label>
                                <input ng-model="customer.street_one" class="form-control"
                                       placeholder="enter street one address" name="street_one" type="text"
                                       id="street_one">
                                <p class="form-control-feedback">@{{ getErrorMsg('street_one') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('city') ? 'has-danger' : ''">
                                <label for="city" class="control-label form-control-label">City</label>
                                <input ng-model="customer.city" class="form-control" placeholder="enter city"
                                       name="city" type="text" id="city">
                                <p class="form-control-feedback">@{{ getErrorMsg('city') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('province') ? 'has-danger' : ''">
                                <label for="province" class="control-label form-control-label">Province</label>
                                <input ng-model="customer.province" class="form-control" placeholder="enter province"
                                       name="province" type="text" id="province">
                                <p class="form-control-feedback">@{{ getErrorMsg('province') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group  required" ng-class="hasError('postal_code') ? 'has-danger' : ''">
                                <label for="postal_code" class="control-label form-control-label">Postal code</label>
                                <input ng-model="customer.postal_code" class="form-control"
                                       placeholder="enter postal code" name="postal_code" type="text" id="postal_code">
                                <p class="form-control-feedback">@{{ getErrorMsg('postal_code') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('country_id') ? 'has-danger' : ''">
                                <label class="control-label">Country</label>
                                <div class="ui fluid search normal selection dropdown country-drop-down"
                                     ng-class="hasError('country_id') ? 'error' : ''">
                                    <input name="country_id" type="hidden" value="">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a country</div>
                                    <div class="menu">
                                        @foreach(countryDropDown() as $key => $country)
                                            <div class="item" data-value="{{ $key }}">{{ $country }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="form-control-feedback">@{{ getErrorMsg('country_id') }}</p>
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
    @include('_inc.customer._inc.script')
@endsection