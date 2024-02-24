<div class="col-md-3">
    <div class="card card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <h2><b>TOTAL</b></h2>
                    <input type="hidden" name="salesOrder" value="@{{ salesOrder }}">
                    <input type="text" readonly
                           class="form-control text-center m-t-5 sales-total m-b-10" name="sales_total"
                           placeholder="0.00" value="@{{ getTotal() | number:2 }}" style="font-size: 20px;">

                    <div class="row" ng-show="payment.payment_mode === 'Cash'">
                        <div class="col-md-6 m-t-10">
                            <h4 class="pull-right">RECEIVED </h4>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="received"
                                   class="form-control text-right m-b-10" ng-model="received" name="received"
                                   placeholder="0.00" style="font-size: 20px;">
                        </div>
                    </div>
                    <div class="row" ng-show="payment.payment_mode === 'Cash'">
                        <div class="col-md-6 m-t-10">
                            <h4 class="pull-right">CHANGE </h4>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="change" readonly
                                   class="form-control text-right sales-total m-b-10"
                                   value="@{{ ((received && received >= getTotal() ) ? (received - getTotal()) : '0' ) | number:2 }}"
                                   name="change"
                                   placeholder="0.00" style="font-size: 20px;">
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-success @{{ !edit ? 'btn-lg' : '' }}" type="submit"><i class="ti-check"></i>
                        SAVE & PRINT
                    </button>
                    <button ng-show="edit" class="btn btn-warning" ng-click="handleCancelClick()" type="button">
                        <i class="ti-close"></i> CANCEL
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>