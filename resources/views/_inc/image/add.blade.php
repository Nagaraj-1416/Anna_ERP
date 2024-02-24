<div>
    <div id="image-sidebar" class="card card-outline-info disabled-dev">
        <div class="image-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Pick an image to upload</h3>
        </div>
        <div class="card-body" id="image-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('image') ? 'has-danger' : ''">
                                <input type="file" name="image" id="image" class="form-control">
                                <p class="form-control-feedback">@{{ getErrorMsg('image') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button"  data-ng-click="saveImage($event)"
                                    class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar">
                                <i class="fa fa-upload"></i>
                                Upload
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