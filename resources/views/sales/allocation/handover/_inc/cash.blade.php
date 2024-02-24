<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <h5><b>Record cash breakdown</b></h5>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label form-control-label">Rupee</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label form-control-label">Count</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label form-control-label">Amount</label>
                </div>
            </div>
        </div>
        <div class="row" ng-repeat="(key, cash) in cashCollection">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group  required" ng-class="hasError('cashCollection', key)">
                            <input class="form-control"
                                   placeholder="rupee"
                                   ng-model="cashCollection[key].type"
                                   ng-change="handleCashUpdated(key)"
                                   name="@{{ 'cashCollection['+key+'][type]' }}"
                                   type="text"
                                   id="cashCollection[][type]"
                                   autocomplete="off">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group  required" ng-class="hasError('cashCollection', key)">
                            <input class="form-control"
                                   placeholder="count"
                                   ng-model="cashCollection[key].count"
                                   ng-change="handleCashUpdated(key)"
                                   name="@{{ 'cashCollection['+ key +'][count]' }}"
                                   type="text"
                                   id="@{{ 'cashCollection['+ key +'][count]' }}" autocomplete="off">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input class="form-control text-right"
                                   placeholder="cash total"
                                   ng-model="cashCollection[key].total"
                                   readonly=""
                                   name="@{{ 'cashCollection['+key+'][total]' }}"
                                   type="text"
                                   id="cashCollection[][total]">
                            <p class="form-control-feedback"></p>
                        </div>
                    </div>
                    <div class="col-md-2 pad-t-10 clearfix">
                        <div class="pull-left">
                            <button class="btn btn-danger" ng-show="isRemoveable !== 1" ng-click="removeCash(key)">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                        <div class="pull-right"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="form-control-feedback error">@{{ hasError('cashCollection', key, true) === 'error' ? '' :
                    hasError('cashCollection', key, true)}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="btn btn-sm btn-primary" ng-click="addMoreCash()">
                    <i class="fa fa-plus"></i> Add more
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div class="form-group" ng-class="hasError('total_cash_breakdown')">
                    <label for="total_cash_breakdown" readonly="true" class="control-label form-control-label">Total</label>
                    <input id="total_cash_breakdown" name="total_cash_breakdown" class="form-control text-right" ng-model="totalCash" readonly="">
                    <p class="form-control-feedback error">@{{ hasError('total_cash_breakdown', null, true) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>