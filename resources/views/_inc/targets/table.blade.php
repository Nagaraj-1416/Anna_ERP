<div class="card">
    <div class="card-body">
        <h3><b>TARGETS</b> <span class="pull-right">Total Targets: {{ count($modal->targets()->get()) }}</span></h3>
        <hr>
        <div class="table-responsive">
            <table class="table color-table muted-table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Is active?</th>
                    <th class="text-right">Target</th>
                    <th class="text-right">Achieved</th>
                    <th class="text-right">Balance</th>
                    <th style="width: 10%;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @if(count($modal->targets()->get()))
                    @foreach($modal->targets()->get() as $target)
                        <tr>
                            <td>
                                {{ $target->type }}
                            </td>
                            <td>{{ $target->start_date }}</td>
                            <td>{{ $target->end_date }}</td>
                            <td>{{ $target->is_active }}</td>
                            <td class="text-right text-info">{{ number_format($target->target, 2) }}</td>
                            <td class="text-right text-success">{{ number_format($target->achieved, 2) }}</td>
                            <td class="text-right text-warning">{{ number_format(($target->target - $target->achieved), 2) }}</td>
                            <td>
                                <div class="button-group">
                                    <button data-id="{{$target->id}}" class="btn waves-effect waves-light btn-sm btn-primary"
                                            onclick="editTarget(this)">Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@php
    $formNames = ['is_active', 'type', 'start_date', 'end_date', 'target'];
@endphp
<div class="ui large modal modal-fixed" id="target_modal">
    <div class="header">Target Edit</div>
    <div class="content">
        {{ form()->model($modal, ['url' => route('setting.rep.vehicle.status.change', ['method' => 'Block', 'rep' => $modal]), 'method' => 'POST', 'id' => 'targetEditForm'])  }}
        <input type="hidden" name="target_id" id="targetId">
        <div class="form-body">
            <div class="row">
                <div class="row">
                    @foreach($formNames as $formName)
                        @if($formName == 'type')
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                    <select class="form-control" id="{{ $formName }}" name="{{$formName}}">
                                        @foreach(typeDD() as $index => $item)
                                            <option value="{{ $index }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <p class="form-control-feedback"></p>
                                </div>
                            </div>
                        @elseif($formName == 'is_active')
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                    <select class="form-control" id="{{ $formName }}" name="{{$formName}}">
                                        @foreach(isActiveDropDown() as $index => $item)
                                            <option value="{{ $index }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    <p class="form-control-feedback"></p>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{ $formName }}" class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                    <input type="text" class="form-control {{ ($formName == 'start_date' || $formName == 'end_date') ? 'datepicker' : '' }}" id="{{ $formName }}"
                                           name="{{ $formName }}"
                                           aria-describedby="fileHelp">
                                    <p class="form-control-feedback"></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <hr>
        <div >
            <button type="button" class="btn btn-inverse cancelBtn" id="cancelBtn" onclick="cancelEdit()" data-dismiss="modal"><i
                        class="fa fa-remove"></i>
                Cancel
            </button>
            <button type="Submit" class="btn btn-success pull-right"><i class="fa fa-check"></i>Submit</button>
        </div>
    </div>
    {{ form()->close() }}
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection