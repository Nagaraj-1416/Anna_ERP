<div data-ng-controller="DocumentListController">
    <div class="row">
        <div class="col-md-6">
            <div class="card-body attachment-list-panel">
                <div class="documents-links">
                    <a class="btn btn-sm btn-success" data-ng-cloak data-ng-show="selected" data-ng-href="@{{ fileDownload }}"><i class="fa fa-download"></i> Download</a>

                    <a class="btn btn-sm btn-info image-link"  data-ng-href="@{{ fileView }}" data-lightbox="image-@{{ selected.id }}" data-title="@{{ selected.name }}" data-ng-cloak data-ng-show="imageExtention | inArray:selected.extension.toLowerCase()" >
                        <i class="fa fa-eye"></i>
                        Show
                    </a>

                    <a class="btn btn-sm btn-info play-video-model" data-link="@{{ fileView }}" data-title="@{{ selected.name }}" data-ng-cloak data-ng-show="videoExtention| inArray:selected.extension.toLowerCase()">
                        <i class="fa fa-play"></i>Play
                    </a>

                    @if(!isset($delete) || (isset($delete) && $delete))
                        <button type="button" class="btn btn-sm btn-danger" data-ng-cloak data-ng-show="selected" data-ng-click="fileDelete(selected);"><i class="fa fa-remove"></i> Delete</button>
                    @endif
                </div>
                <div class="file-list">
                    <div class="file-item" dir-paginate="document in documents | itemsPerPage: 3" data-ng-cloak data-ng-click="fileSelected();" data-ng-class="{'text text-info': document.id == selected.id}" document-loop>
                        <div class="file-left">
                            <span><i class="fa fa-file-text-o"></i></span>
                        </div>
                        <div class="file-right">
                            <span class="small">
                                <b>Name: </b>@{{ document.name }} <br/>
                                <b>File type: </b> @{{ document.extension }}
                                {{--<b>Size : </b>@{{ document.size }} <br/>--}}
                            </span>
                        </div>
                    </div>
                </div>
                <p class="card-text" data-ng-show="!documents.length">No Attachments Found</p>
            </div>
            <hr>
            <div class="pull-right"><dir-pagination-controls></dir-pagination-controls></div>
        </div>
        <div class="col-md-6">
            <div class="card-body">
                @if(!isset($upload) || (isset($upload) && $upload))
                    <div class="file-dropzone" id="fileListUpload">
                        <div class="dz-default dz-message"><span><i class="ti-upload"></i> Drop your files here to upload</span></div>
                    </div>
                    <div class="fileListUpload ui bottom green attached progress file-list-upload-progress">
                        <div class="bar"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="ui basic modal model-resolved" id="VideoModel">
        <div class="header">@{{ selected.name }}</div>
        <div class="content">
            <video controls style="width: 90%;" src="@{{ fileView }}"></video>
        </div>
    </div>
</div>
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/gallery.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/dropzone.css') }}">
@endsection

@section('script')
    @parent
    <script src="{{ asset('js/vendor/dropzone.js') }}"></script>
@endsection