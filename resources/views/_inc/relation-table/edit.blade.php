<div class="ui modal modal-fixed " id="edit_data_modal">
    <i class="close icon"></i>
    <div class="header">
        {{ $title }}
    </div>
    <div class="content">
        {{ form()->open(['method' => 'PATCH']) }}
        <div class="row">
            <div class="col-md-12">
                <div class="form-body">
                    <div class="row">
                        <input type="hidden" name="id" id='edited_location'>
                        @foreach($formNames as $formName)
                            @if($formName == 'salutation')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                        <select class="form-control" id="{{ $formName }}" name="{{$formName}}">
                                            @foreach(salutationDropDown() as $index => $item)
                                                <option value="{{ $index }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($formName == 'is_active')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                        <select class="form-control" id="{{ $formName }}" name="{{$formName}}">
                                            @foreach(isActiveDropDown() as $index => $item)
                                                <option value="{{ $index }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($formName == 'notes')
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! form()->bsTextarea($formName, ucfirst(str_replace('_', ' ', $formName)) , null, ['placeholder' => 'enter '. str_replace('_', ' ', $formName), 'class' => 'form-control',
                                        'cols' => '50']) !!}
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! form()->bsText($formName, ucfirst(str_replace('_', ' ', $formName)) , null, ['placeholder' => 'enter '. str_replace('_', ' ', $formName), 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="pull-right" style="margin-bottom: 10px">
            <button type="button" class="btn btn-inverse" id="cancelBtn" data-dismiss="modal"><i class="fa fa-remove"></i> Cancel</button>
            <button type="Submit" class="btn btn-success" ><i class="fa fa-check"></i>Update</button>
        </div>
        {{ form()->close() }}
    </div>
</div>
