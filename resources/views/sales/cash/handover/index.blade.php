<div id="handover-sidebar" class="card card-outline-inverse disabled-dev" >
    <div class="cus-create-preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
        </svg>
    </div>
    <div class="card-header ">
        <h3 class="m-b-0 text-white">CONFIRM DAILY CASH SALES</h3>
        <h6 class="card-subtitle text-white">DATE</h6>
    </div>
    <div class="card-body" id="add-cus-body">
        <div class="form">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" ng-show="errors.error">
                            <h4 class="text-danger">
                                <i class="fa fa-exclamation-circle"></i> @{{ errors.error }}
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><b>Collection from today's sales</b></h6>
                                <table class="ui celled structured table">
                                    <tbody>
                                    <tr>
                                        <td><b>Cash</b></td>
                                        <td class="text-right">@{{ collection.payment_collection.cash | number:2 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Cheque</b></td>
                                        <td class="text-right">@{{ collection.payment_collection.cheque | number:2 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Deposit</b></td>
                                        <td class="text-right">@{{ collection.payment_collection.direct_deposit |
                                            number:2 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Card</b></td>
                                        <td class="text-right">@{{ collection.payment_collection.credit_card | number:2
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-bg-info"><b>Total</b></td>
                                        <td class="td-bg-success text-right">
                                            <b>@{{ collection.total_collection | number:2 }}</b>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6><b>Summary</b></h6>
                                <table class="ui celled structured table">
                                    <tbody>
                                    <tr>
                                        <td style="width: 50%;"><b>Total collection</b></td>
                                        <td class="text-right">@{{ collection.total_collection | number:2 }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Allowance</b></td>
                                        <td class="text-right">@{{collection.allowance | number:2 }}</td>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td><b>Shortage</b></td>--}}
                                    {{--<td class="text-right">--}}
                                    {{--<input class="form-control text-right"--}}
                                    {{--placeholder="enter shortage amount"--}}
                                    {{--name="shortage"--}}
                                    {{--type="text"--}}
                                    {{--id="shortage"--}}
                                    {{--autocomplete="off"--}}
                                    {{--value="{{ old('shortage') ?? '' }}">--}}
                                    {{--</td>--}}
                                    {{--</tr>--}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="m-t-20">
                                    <h4 class="box-title">Cheques</h4>
                                    <h6 style="padding-top: 2px;">
                                        {{--<small>Pick cheques to associate</small>--}}
                                        {{--<br>--}}
                                    </h6>
                                    <table class="ui table bordered celled striped">
                                        <thead>
                                        <tr>
                                            <th style="width: 25%;">CHEQUE NO</th>
                                            <th style="width: 25%;">CHEQUE DATE</th>
                                            <th style="width: 25%;">WRITTEN BANK</th>
                                            <th style="width: 25%;" class="text-right">AMOUNT</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr ng-repeat="cheque in collection.payments.cheque">
                                            <td>@{{ cheque.cheque_no }}</td>
                                            <td>@{{ cheque.cheque_date }}</td>
                                            <td>@{{ cheque.bank.name }}</td>
                                            <td class="text-right">@{{ cheque.payment | number:2 }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr>
                        <button type="button"
                                class="btn btn-btn btn-success waves-effect waves-light m-r-10 cus-save-sidebar"
                                data-ng-click="saveHandover($event)">
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