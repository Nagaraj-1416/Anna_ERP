@extends('auth.layouts.master-right')
@section('title', 'Login')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="javascript:void(0)" class="text-center db"><img src="{{ asset('images/logo-icon.png') }}"
                                                                     alt="Home"/><img
                        src="{{ asset('images/logo-text.png') }}" alt="Home"/></a>
            <hr>
            <div class="text-center">
                <h3 class="text-muted">Verify your face</h3>
                <small class="text-muted">Please show your face in front of camera</small>
            </div>

            <hr>
            <div class="face-container">
                <div class="face">
                    <video id="face-video" class="dimmer" preload autoplay loop muted></video>
                    <canvas id="face-canvas"></canvas>
                </div>
                <div class="face-loader hidden">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-4">
            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light hidden try-again-btn">
                Try Again
            </button>
            <a class="btn btn-danger btn-lg btn-block text-uppercase waves-effect waves-light"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Cancel
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .face-container {
            width: 320px;
            height: 240px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }

        .face-container video, .face-container canvas {
            top: 5px;
            width: 320px;
            height: 240px;
            margin: auto;
            display: block;
            position: absolute;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            text-align: center;
        }

        @media only screen and (max-width: 320px) {
            .face-container {
                margin-left: -16%;
            }
        }

        @media only screen and (max-width: 375px) and (min-width: 321px) {
            .face-container {
                margin-left: -4%;
            }
        }

        .face-loader {
            width: 320px;
            height: 240px;
        }

        .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/face.js') }}"></script>
    <script>
        $(document).ready(function () {
            var scopedLogin = false;
            var processing = false;
            var maxAttemp = 3;
            var attemp = 0;
            var face = $('.face');
            var faceLoader = $('.face-loader');
            var video = document.getElementById('face-video');
            var canvas = document.getElementById('face-canvas');
            var tryAgainBtn = $('.try-again-btn');
            canvas.width = 320;
            canvas.height = 240;
            var context = canvas.getContext('2d');
            var tracker = new tracking.ObjectTracker('face');
            tracker.setInitialScale(4);
            tracker.setStepSize(2);
            tracker.setEdgesDensity(0.1);
            tracking.track('#face-video', tracker, {camera: true});
            setTimeout(function () {
                tracker.on('track', function (event) {
                    context.clearRect(0, 0, canvas.width, canvas.height);
                    event.data.forEach(function (rect) {
                        if (rect.y < 100 && rect.y > 40 && rect.height <= 180 && rect.x >= 20 && rect.x <= 300) {
                            context.strokeStyle = '#a64ceb';
                            context.strokeRect(rect.x, rect.y, rect.width, rect.height);
                            context.font = '11px Helvetica';
                            context.fillStyle = "#fff";
                            if (scopedLogin === false && processing === false && attemp < maxAttemp) {
                                attemp += 1;
                                identfyFace();
                                processing = true;
                            }

                            if (!(attemp < maxAttemp) && scopedLogin === false) {
                                loginFail();
                                scopedLogin = true;
                                tryAgainBtn.removeClass('hidden');
                            }
                        }
                    });

                    function loginFail() {
                        swal({
                            title: 'Your face not matched.',
                            text: ".",
                            type: 'warning',
                        });
                    }

                    function identfyFace() {
                        faceLoader.removeClass('hidden');
                        face.addClass('hidden');
                        var loginRoute = '{{ route('auth.tfa.verify') }}';
                        canvas.width = 130;
                        canvas.height = 100;
                        context.drawImage(video, 0, 0, 130, 100);
                        var data = canvas.toDataURL('image/png');
                        var postData = {image: data, _token: '{{ csrf_token() }}'};
                        $.post(loginRoute + '?ajax=true', postData, function (result) {
                            if(result['success'] == true){
                                document.location.href = '/';
                            }else{
                                processing = false;
                                faceLoader.addClass('hidden');
                                face.removeClass('hidden');
                            }
                        });
                        canvas.width = 320;
                        canvas.height = 240;
                    }
                });
            }, 2500);

            tryAgainBtn.click(function (e) {
                e.preventDefault();
                scopedLogin = false;
                processing = false;
                attemp = 0;
                $(this).addClass('hidden');
            });
        });
    </script>
@endsection
