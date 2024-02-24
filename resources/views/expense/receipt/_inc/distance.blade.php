<div class="row" ng-show="expenseType == '1' && calculateMileage">
    <div ng-class="calculateMileage == 'Distance' ? 'col-md-3' : 'col-md-3'">
        <div class="form-group {{ $errors->has('staff') ? 'has-danger' : '' }}">
            <label class="control-label">Staff</label>
            <div class="ui fluid search selection dropdown staff-drop-down {{ $errors->has('staff') ? 'error' : '' }}">
                <input type="hidden" name="staff">
                <i class="dropdown icon"></i>
                <div class="default text">choose a staff</div>
                <div class="menu"></div>
            </div>
            <p class="form-control-feedback">{{ $errors->first('staff') }}</p>
        </div>
    </div>

    {{--Dostance--}}
    <div class="col-md-3" ng-show="calculateMileage == 'Distance'">
        {!! form()->bsText('distance', 'Distance (km)', null, ['placeholder' => 'enter distance (Kilometer/s)', 'ng-model' => 'distance', 'ng-change'=>'distanceChanged()']) !!}
    </div>

    {{--Odometer--}}
    <div class="col-md-3" ng-show="calculateMileage == 'Odometer'">
        {!! form()->bsText('start_reading', 'Odometer start reading', null, ['placeholder' => 'enter reading', 'ng-model' => 'odometer.start', 'ng-change' => 'odometerChanges()']) !!}
    </div>
    <div class="col-md-3" ng-show="calculateMileage == 'Odometer'">
        {!! form()->bsText('end_reading', 'Odometer end reading', null, ['placeholder' => 'enter reading',  'ng-model' => 'odometer.end', 'ng-change' => 'odometerChanges()']) !!}
    </div>

    {{--Total --}}
    <div ng-class="calculateMileage == 'Distance' ? 'col-md-3' : 'col-md-3'">
        <div class="form-group">
            <label class="control-label form-control-label">Amount</label>
            <input type="text" name="mileage_amount" disabled class="form-control" ng-model="mileageAmount">
        </div>
    </div>
</div>