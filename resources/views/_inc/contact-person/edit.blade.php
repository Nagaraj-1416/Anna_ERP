@php
    $formNames = ['salutation', 'first_name', 'last_name', 'full_name', 'phone','mobile', 'email', 'designation', 'department', 'is_active'];
@endphp
<div class="modal fade bd-example-modal-lg" id="edit_modal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog model-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            {{ form()->open(['method' => 'PATCH']) }}
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Edit contact person</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <div class="row">
                        @foreach($formNames as $formName)
                            @if($formName == 'salutation')
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                        <select class="form-control" id="{{ $formName }}" name="{{$formName}}">
                                                @foreach(isActiveDropDown() as $index => $item)
                                                    <option value="{{ $index }}">{{ $item }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ ucfirst(str_replace('_', ' ', $formName)) }}</label>
                                        <input type="text" class="form-control" id="{{ $formName }}"
                                               name="{{ $formName }}"
                                               aria-describedby="fileHelp">
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="Submit" class="btn btn-success waves-effect waves-light">
                    <i class="fa fa-check"></i> Update
                </button>
                <button type="button" class="btn btn-inverse waves-effect waves-light" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Cancel
                </button>
            </div>
            {{ form()->close() }}
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
@endsection