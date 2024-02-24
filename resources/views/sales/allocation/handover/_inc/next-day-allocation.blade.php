<div class="card">
    <div class="card-body" style="background-color: #EFEFEF;">
        <h4 class="box-title"><b>Next day allocation details</b></h4>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <p><b>Rep:</b> {{ $allocation->rep->name ?? 'None' }}</p>
            </div>
            <div class="col-md-4">
                <p><b>Vehicle:</b> {{ $allocation->salesLocation->name ?? 'None' }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                @if($allocation->nxt_day_al_route)
                    <div class=""><b>Selected route by {{ $allocation->rep ? $allocation->rep->name : 'Rep' }}:</b></div>
                @else
                    <div class=""><b>Select a route on behalf of rep:</b></div>
                @endif
                <div class="form-group {{ $errors->has('route_id') ? 'has-danger' : '' }}">
                    <div class="ui fluid search normal selection dropdown drop-down route-drop-down {{ $errors->has('route_id') ? 'error' : '' }}">
                        @if($allocation->nxt_day_al_route)
                            <input name="route_id" type="hidden" value="{{ $allocation->nxt_day_al_route }}">
                        @else
                            <input name="route_id" type="hidden" value="{{ old('_token') ? old('route_id'): '' }}">
                        @endif
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a route</div>
                        <div class="menu">
                            @foreach(routeDropDownByAllocation($allocation) as $key => $route)
                                <div class="item" data-value="{{ $key }}">{{ $route }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('route_id') }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class=""><b>Store:</b></div>
                <div class="form-group {{ $errors->has('store_id') ? 'has-danger' : '' }}">
                    <div class="ui fluid search normal selection dropdown drop-down store-drop-down {{ $errors->has('store_id') ? 'error' : '' }}">
                        <input name="store_id" type="hidden" value="{{ old('_token') ? old('store_id'): '' }}">
                        <i class="dropdown icon"></i>
                        <div class="default text">choose a store</div>
                        <div class="menu">
                            @foreach(storeDropDownByAllocation($allocation) as $key => $store)
                                <div class="item" data-value="{{ $key }}">{{ $store }}</div>
                            @endforeach
                        </div>
                    </div>
                    <p class="form-control-feedback">{{ $errors->first('store_id') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>