<div ng-controller="BrandCreateController">
    <div id="add-brand-sidebar" class="card card-outline-info disabled-dev">
        <div class="brand-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Brand</h3>
            <h6 class="card-subtitle text-white">Create new brand and associate</h6>
        </div>
        <div class="card-body" id="add-brand-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title">Basic Details</h4>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('name') ? 'has-danger' : ''">
                                <label for="brand-name" class="control-label form-control-label">Name</label>
                                <input ng-model="brand.name" class="form-control"
                                       placeholder="enter name" name="name" type="text" id="brand-name">
                                <p class="form-control-feedback">@{{ getErrorMsg('name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('description') ? 'has-danger' : ''">
                                <label for="brand-description" class="control-label form-control-label">Description</label>
                                <textarea ng-model="brand.description" class="form-control"
                                       placeholder="enter description" name="description" type="text" id="brand-description">
                                </textarea>
                                <p class="form-control-feedback">@{{ getErrorMsg('description') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                    data-ng-click="saveBrand($event)">
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
    @include('_inc.brand._inc.script')
@endsection