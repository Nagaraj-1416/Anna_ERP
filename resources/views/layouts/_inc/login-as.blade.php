<div id="login-as-sidebar" class="card card-outline-info disabled-dev" ng-controller="LoginAsControllerController">
    <div class="cus-create-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h3 class="m-b-0 text-white">Login As</h3>
    </div>
    <div class="card-body" id="add-cus-body">
        <div class="form">
            <div class="form-body">
                <div class="alert alert-danger" ng-show="errors.hasOwnProperty('unauthorized')">
                    <h5 class="text-danger">
                        <i class="fa fa-exclamation-circle"></i> This action is unauthorized.
                    </h5>
                </div>
                {{--Direct Deposit--}}
                <div class="row cheque-data">
                    <div class="col-md-12">
                        <div class="form-group required" ng-class="hasError('user_id') ? 'has-danger' : ''">
                            <label for="user_id" class="control-label form-control-label">User</label>
                            <div class="ui fluid search selection  dropdown user-dropdown">
                                <input type="hidden" name="user_id">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a user</div>
                                <div class="menu"></div>
                            </div>
                            <p class="form-control-feedback">@{{ getErrorMsg('user_id') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <button type="button"
                                class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                data-ng-click="LoginAsPost($event)">
                            <i class="fa fa-check"></i>
                            @{{ edit ? 'Update':'Submit' }}
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