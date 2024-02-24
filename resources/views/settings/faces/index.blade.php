@extends('layouts.master')
@section('title', 'Face Ids')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Face Ids') !!}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12">
            <!-- Things You Could Do -->
            <div class="card border-default">
                <div class="card-body">
                    <h3 class="card-title">Face Ids</h3>
                    <hr>
                    <section class="content faces">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="face-container">
                                    <video id="face-video" preload autoplay loop muted></video>
                                    <canvas id="face-canvas"></canvas>
                                </div>
                                <div>
                                    <button class="positive ui button take-photo"><i class="photo icon"></i>Take Photo</button>
                                </div>
                            </div>
                        </div>
                        <div class="hidden">
                            <div class="row">
                                <div class="col-md-4 grid-image" id="img-temp">
                                    <div class="img-container">
                                        <img src="" alt="">
                                        <div class="img-item action">
                                            <button class="ui icon green mini button save-image">
                                                <i class="save icon"></i>
                                            </button>
                                            <button class="ui icon red mini button remove-image">
                                                <i class="remove icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div><div class="col-lg-9 col-md-8 col-sm-12">
            <!-- Things You Could Do -->
            <div class="card border-default" style="min-height: 380px">
                <div class="card-body">
                    <h3 class="card-title">Taken photos</h3>
                    <hr>
                    <div class="row" id="taken-images"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-default">
                <div class="card-body added-images">
                    <h3 class="card-title">Added faces</h3>
                    <hr>
                    <div class="row">
                        @foreach($faceIds as $faceId)
                            <div class="col-md-3 grid-image">
                                <div class="img-container">
                                    <img src="{{ route('setting.face.id.image' , $faceId->id) }}" alt="Face id">
                                    <div class="img-item action">
                                        <button data-id="{{ $faceId->id }}" class="ui icon red mini button remove-face">
                                            <i class="remove icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <link href="{{ asset('css/vendor/face.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/face.js') }}"></script>
    <script>
        window.onload = function() {
            var maxPhotos = 1;
            var takenPhotos = 0;
            var readyTake = false;
            var postRoute = '{{ route('setting.face.id.store', $user->id) }}';
            var $el = {
                takenImages : $('#taken-images'),
                tempImage : $('#img-temp'),
                takeBtn : $('.take-photo'),
                clearBtn : $('.clear-photos'),
                addedImages : $('.added-images'),
            };
            var video = document.getElementById('face-video');
            var canvas = document.getElementById('face-canvas');
            canvas.width = 320;
            canvas.height = 240;
            var context = canvas.getContext('2d');
            var tracker = new tracking.ObjectTracker('face');
            tracker.setInitialScale(4);
            tracker.setStepSize(2);
            tracker.setEdgesDensity(0.1);
            tracking.track('#face-video', tracker, { camera: true });

            tracker.on('track', function(event) {
                context.clearRect(0, 0, canvas.width, canvas.height);
                event.data.forEach(function(rect) {
                    if (rect.y < 81 && rect.y > 60 && rect.height <= 160 && rect.x >= 20 && rect.x <= 300){
                        context.strokeStyle = '#a64ceb';
                        context.strokeRect(rect.x, rect.y, rect.width, rect.height);
                        context.font = '11px Helvetica';
                        context.fillStyle = "#fff";
                        if(readyTake){
                            takePhoto(rect)
                        }
                    }
                });
            });

            $el.takeBtn.click(function () {
                readyTake = true;
                $(this).addClass('loading');
            });

            $el.clearBtn.click(function () {
                takenPhotos = 0;
                readyTake = false;
                $el.takenImages.empty();
            });

            $el.takenImages.delegate('.remove-image','click',function() {
                takenPhotos -= 1;
                readyTake = false;
                $(this).parent().parent().parent().remove();
            });

            $el.takenImages.delegate('.save-image','click',function() {
                var imageSource = $(this).parent().parent().find('img').attr('src');
                $(this).addClass('loading');
                var thisEl = $(this);
                $.post(postRoute + '?ajax=true', {image: imageSource,  _token : '{{ csrf_token() }}'}, function(result){
                    takenPhotos -= 1;
                    readyTake = false;
                    if(result['success'] == true){
                        swal(
                            'Added!',
                            'Your face id has been added.',
                            'success'
                        );
                        thisEl.parent().parent().parent().remove();
                        setTimeout(function () {
                            location.reload();
                        }, 1000)
                    }else{
                        $(this).removeClass('loading');
                        swal(
                            'Failed!',
                            'Your face id adding failed!.',
                            'error'
                        );
                    }
                });
            });

            $el.addedImages.delegate('.remove-face','click',function() {
                $(this).addClass('loading');
                var thisEl = $(this);
                var deleteRoute = '{{ route('setting.face.id.delete', 'ID') }}';
                var id = $(this).data('id');
                deleteRoute = deleteRoute.replace('ID', id);
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function () {
                    $.ajax({
                        url: deleteRoute,
                        type: 'DELETE',
                        data : {_token : '{{ csrf_token() }}'},
                        success: function(result) {
                            if(result['success'] === true){
                                swal(
                                    'Deleted!',
                                    'Your face id has been deleted.',
                                    'success'
                                );
                                thisEl.parent().parent().parent().remove();
                            }else{
                                $(this).removeClass('loading');
                                swal(
                                    'Failed!',
                                    'Your face id deleting failed!.',
                                    'error'
                                );
                            }
                        }
                    });
                });

            });


            function takePhoto(rect) {
                if (takenPhotos < maxPhotos) {
                    takenPhotos += 1;
                    var newImage = $el.tempImage.clone();
                    context.drawImage(video, 0,  0, 320, 240);
                    var data = canvas.toDataURL('image/png');
                    newImage.find('img').attr('src', data);
                    newImage.attr('id', 'image-' + takenPhotos);
                    $el.takenImages.append(newImage);
                }
                if(takenPhotos === maxPhotos){
                    $el.takeBtn.removeClass('loading');
                }
            }
        };
    </script>
@endsection