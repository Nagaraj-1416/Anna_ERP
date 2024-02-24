<script src="{{ asset('js/vendor/gallery.js') }}"></script>
<script>
    var currentProgress = $('.fileListUpload');
    currentProgress.progress();
    var documentView = '{{ route('document.show', 'DOCUMENT_ID') }}';

    function showSuccess() {
        toastr.options = {
            "positionClass": "toast-bottom-right"
        };
        toastr.success('File successfully uploaded!')
    }

    app.filter('fileSizer', function () {
        return function (bytes, precision) {
            if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
            if (typeof precision === 'undefined') precision = 1;
            var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
                number = Math.floor(Math.log(bytes) / Math.log(1024));
            return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' ' + units[number];
        }
    });

    app.filter('inArray', function () {
        return function (array, needle) {
            return array.indexOf(needle) >= 0;
        };
    });

    app.controller('DocumentListController', ['$scope', '$http', function ($scope, $http) {
        var moduleUrl = '{{ route('document.index', ['model' => substr(get_class($model),4), 'modelId' => $model]) }}';
        $scope.imageExtention = ['jpg', 'png', 'jpeg'];
        $scope.videoExtention = ['mp4', 'mng', 'avi', 'mov', 'mkv', 'wmv'];
        $scope.docExtention = ['pdf'];
        $scope.dbSupportedFormats = ['doc', 'docx', 'docm', 'ppt', 'pps', 'ppsx', 'ppsm', 'pptx', 'pptm', 'xls', 'xlsx', 'xlsm', 'rtf'];
        $scope.moduleFiles = []
        $scope.reloadFiles = function () {
            $http.get(moduleUrl + '?ajax=true').then(function (data) {
                $scope.documents = data.data;
            });
        };
        $scope.reloadFiles();
        Dropzone.autoDiscover = false;

        $("div#fileListUpload").dropzone({
            url: "{{ route('document.upload', ['model' => substr(get_class($model),4), 'modelId' => $model]) }}",
            maxFilesize: '{{ fileUploadSize() }}' * 1,
            addedfile: function (file) {
            },
            sending: function (file, xhr, formData) {
                formData.append('_token', '{{ csrf_token() }}');
            },
            success: function () {
                // showSuccess();
                currentProgress.progress('reset');
                $scope.reloadFiles();
            },
            uploadprogress: function (file, progress) {
                currentProgress.progress('set percent', progress);
            },
            error: function (file, message) {
                swal("Failed", message, "error")
            }
        });

        $scope.fileSelected = function () {
            if ($scope.selected && $scope.selected.id === this.document.id) {
                $scope.selected = null;
            } else {
                $scope.selected = this.document;
                $scope.fileDownload = '/document/' + $scope.selected.id + '/download';
                $scope.fileView = '/document/' + $scope.selected.id;
            }
        };

        $scope.fileDelete = function (selected) {
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d01919",
                cancelButtonColor: "#E0E1E2",
                confirmButtonText: "Yes, delete it!",
            }).then(function (response) {
                if (response.dismiss !== 'cancel') {
                    $.ajax({
                        url: '/document/' + selected.id + '/delete?ajax=true',
                        method: 'DELETE',
                        data: {_token: '{{ csrf_token() }}'},
                        success: function () {
                            $scope.reloadFiles();
                            $scope.select = false;
                            swal("Deleted!", "File has been deleted.", "success");
                            $scope.selected = null;
                        }
                    });
                }
            });
        };
    }]).directive('documentLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                $('.documents-links').lightGallery({
                    selector: '.image-link'
                });
            }
        }
    });

    $videoModel = $('#VideoModel');
    $playVideoBtn = $('.play-video-model');
    $playVideoBtn.click(function () {
        $videoModel.modal('show').modal('refresh');
        return false;
    });
</script>