<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <div class="row">
            <div class="col-md-12">
                <h5><b>Summary</b></h5>
                <table class="ui celled structured table">
                    <tbody>
                    <tr>
                        <td colspan="6">
                            <b class="text-purple">Total Collection: </b>@{{ total_collect | number:2 }}
                            <input type="hidden" name="total_collect" value="@{{ total_collect }}">
                        </td>
                    </tr>
                    <tr>
                        <td width="15%" class="text-info text-right"><b>Cash Collection</b></td>
                        <td width="15%" class="text-danger text-right"><b>Total expenses</b></td>
                        <td width="15%" class="text-danger text-right"><b>Refunded</b></td>
                        <td width="15%" class="text-danger text-right"><b>Shortage</b></td>
                        <td width="15%" class="text-warning text-right"><b>Excess</b></td>
                        <td width="15%" class="text-green text-right"><b>Balance</b></td>
                    </tr>
                    <tr>
                        <td class="td-bg-info text-right">
                            @{{ cash_collection | number:2 }}
                            <input type="hidden" name="cash_collection" value="@{{ cash_collection }}">
                        </td>
                        <td class="td-bg-danger text-right">
                            @{{ total_expense | number:2 }}
                        </td>
                        <td class="td-bg-danger text-right">
                            @{{ refundedAmount | number:2 }}
                        </td>
                        <td class="td-bg-danger text-right">
                            {{--@{{ shortage | number:2 }}--}}
                            <input type="text" class="form-control" name="shortage" value="@{{ shortage }}">
                        </td>
                        <td class="td-bg-warning text-right">
                            {{--@{{ excess | number:2 }}--}}
                            <input type="text" class="form-control"  name="excess" value="@{{ excess }}">
                        </td>
                        <td class="td-bg-success text-right">
                            @{{ balance | number:2 }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <b class="text-megna text-right">
                                Cheque Collection: </b>@{{ cheque_sales | number:2 }}
                            <input type="hidden" name="cheque_sales" value="@{{ cheque_sales }}">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>