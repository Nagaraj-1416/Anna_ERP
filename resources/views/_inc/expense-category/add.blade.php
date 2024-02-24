<div ng-controller="ExpenseCategoryController">
    <div id="add-expense-cat-sidebar" class="card card-outline-info disabled-dev">
        <div class="expense-cat-create-preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
            </svg>
        </div>
        <div class="card-header ">
            <h3 class="m-b-0 text-white">Expense Category</h3>
            <h6 class="card-subtitle text-white">Create new expense category and associate</h6>
        </div>
        <div class="card-body" id="add-expense-cat-body">
            <div class="form">
                <div class="form-body">
                    <div class="row">
                        {{--<div class="col-md-12">
                            <h4 class="box-title">Expense Category Details</h4>
                            <hr>
                        </div>--}}
                        <div class="col-md-12">
                            <div class="form-group required" ng-class="hasError('name') ? 'has-danger' : ''">
                                <label for="name" class="control-label form-control-label">Name</label>
                                <input ng-model="expenseCategory.name" class="form-control" placeholder="enter category name" name="name" type="text" id="name">
                                <p class="form-control-feedback">@{{ getErrorMsg('name') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" ng-class="hasError('notes') ? 'has-danger' : ''">
                                <label for="notes" class="control-label form-control-label">Notes</label>
                                <textarea name="notes" placeholder="enter category related notes..." ng-model="expenseCategory.notes" id="notes" cols="30" rows="6" class="form-control" ></textarea>
                                <p class="form-control-feedback">@{{ getErrorMsg('notes') }}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                            <button type="button" class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar" data-ng-click="saveExpenseCategory($event)">
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
    @include('_inc.expense-category._inc.script')
@endsection