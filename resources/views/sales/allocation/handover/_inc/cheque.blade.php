<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <h4 class="box-title"><b>Cheques</b></h4>
        <h6 style="padding-top: 2px;">
            <span>Pick cheques if you want to change cheque's details</span>
        </h6>
        <table class="ui table bordered celled striped">
            <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th>CUSTOMER</th>
                <th>CHEQUE TYPE</th>
                <th>CHEQUE NO</th>
                <th>CHEQUE DATE</th>
                <th>WRITTEN BANK</th>
                <th class="text-right">AMOUNT</th>
                <th>
                    SHORTAGE
                </th>
            </tr>
            </thead>
            <tbody>
            @if($cheques->count())
                @foreach($cheques as $cheque)
                    <tr>
                        <td style="width: 3%;">
                            <div class="demo-checkbox">
                                <input type="checkbox" id="{{ 'md_checkbox_28_' . $cheque->id }}"
                                       data-id="{{ $cheque->id }}"
                                       name="cheques[id][{{ $cheque->id }}]"
                                       class="chk-col-cyan cheque-check"
                                       {{ (old('_token') && old('cheques') && array_get(old('cheques'), 'id') && isset(old('cheques')['id'][$cheque->id]) ) ? 'checked' : ''}}
                                       ng-click="chequeCheck($event)">
                                <label for="{{ 'md_checkbox_28_' . $cheque->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <p>{{ $cheque->customer->display_name }}</p>
                        </td>
                        <td style="width: 10%;">
                            <p ng-show="!getShow('{{ $cheque->id }}')">{{ $cheque->cheque_type }}</p>
                            <div class="ui fluid selection dropdown cheque-type-dropdown"
                                 ng-show="getShow('{{ $cheque->id }}')">
                                <input type="hidden" name="cheques[cheque_type][{{$cheque->id}}]"
                                       value="{{ $cheque->cheque_type }}">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a type</div>
                                <div class="menu">
                                    <div class="item" ng-repeat="(key, value) in chequeTypes" data-value="@{{ key}}">
                                        @{{ value }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="width: 10%;">
                            <p ng-show="!getShow('{{ $cheque->id }}')">{{ $cheque->cheque_no }}</p>
                            <input ng-show="getShow('{{ $cheque->id }}')"
                                   type="text"
                                   class="form-control"
                                   name="cheques[cheque_no][{{ $cheque->id }}]"
                                   ng-class="hasErrorForCheque('cheques', 'cheque_no', '{{ $cheque->id }}', true)"
                                   value="{{ (old('_token') && old('cheques')) ? old('cheques')['cheque_no'][$cheque->id] :  $cheque->cheque_no }}">
                            <p class="form-control-feedback"></p>
                        </td>
                        <td style="width: 10%;">
                            <p ng-show="!getShow('{{ $cheque->id }}')">{{ $cheque->cheque_date }}</p>
                            <input ng-show="getShow('{{ $cheque->id }}')"
                                   type="text" class="form-control datepicker "
                                   ng-class="hasErrorForCheque('cheques', 'cheque_date', '{{ $cheque->id }}', true)"
                                   name="cheques[cheque_date][{{ $cheque->id }}]"
                                   value="{{ (old('_token') && old('cheques')) ? old('cheques')['cheque_date'][$cheque->id] : $cheque->cheque_date }}"
                            >
                            <p class="form-control-feedback"></p>
                        </td>
                        <td style="width: 15%;">
                            <p ng-show="!getShow('{{ $cheque->id }}')">
                                {{ $cheque->bank->name ?? 'None'}}
                            </p>
                            <div class="ui fluid selection dropdown bank-dropdown"
                                 ng-show="getShow('{{ $cheque->id }}')">
                                <input type="hidden" name="cheques[cheque_bank][{{$cheque->id}}]"
                                       value="{{ $cheque->bank->id }}">
                                <i class="dropdown icon"></i>
                                <div class="default text">choose a bank</div>
                                <div class="menu">
                                    <div class="item" ng-repeat="(key, value) in banks" data-value="@{{ key}}">
                                        @{{ value }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="width: 10%;" class="text-right">
                            <p class="text-right amount-p" data-amount="{{ $cheque->payment }}"
                               ng-show="!getShow('{{ $cheque->id }}')">{{ number_format($cheque->payment, 2) }}</p>
                            <?php
                            $cheque->payment = (old('_token') && old('cheques')) ? old('cheques')['payment'][$cheque->id] : $cheque->payment
                            ?>
                            <input ng-show="getShow('{{ $cheque->id }}')"
                                   type="number"
                                   min="0"
                                   string-to-number
                                   class="form-control text-right"
                                   name="cheques[payment][{{ $cheque->id }}]"
                                   ng-init="addAmount('{{ $cheque->id }}', '{{ $cheque->payment }}', true)"
                                   ng-class="hasErrorForCheque('cheques', 'payment', '{{ $cheque->id }}', true)"
                                   ng-model="chequeAmounts['{{ $cheque->id }}']"
                                   ng-change="updateChequePayment()"
                                   value="{{ (old('_token') && old('cheques')) ? old('cheques')['payment'][$cheque->id] :  $cheque->payment }}">
                            <p class="form-control-feedback"></p>
                        </td>
                        <td style="width: 15%;">
                            <div class="form-group">
                                <div class="ui fluid selection dropdown shortage-dropdown" data-id="{{$cheque->id}}">
                                    <input type="hidden" name="cheques[shortage][{{$cheque->id}}]">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">choose a shortage</div>
                                    <div class="menu">
                                        <div class="item" ng-repeat="(key, value) in shortages" data-value="@{{ key}}">
                                            @{{ value }}
                                        </div>
                                    </div>
                                </div>
                                <p class="form-control-feedback"></p>
                            </div>
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2">No Cheques Found...</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>

