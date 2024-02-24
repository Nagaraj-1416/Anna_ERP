@extends('layouts.cash-sales')
@section('title', 'Cash Sales')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Sales') !!}
@endsection
@section('content')
    {{--<section ng-controller="CashSalesController" id="fullscreen-container" style="width: 100%; height: 100%; overflow-y: scroll; overflow-x: hidden">--}}
    <section ng-controller="CashSalesController" id="fullscreen-container">
        <div class="row" >
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-body">
                        {{ form()->open(['name' => 'cashSales', 'route' => 'cash.sales.store', 'method' => 'POST', 'files' => true]) }}
                        <h2 class="card-title card-title-bg"><b>PERFORM CASH SALES</b>
                            {{--<span id="toggle-fullscreen">
                                <span class="mdi mdi-fullscreen text-blue"></span>
                            </span>--}}
                            <span class="pull-right">{{ auth()->user()->name }} | <small>{{ carbon()->now()->format('F j, Y') }}</small></span>
                        </h2>
                        @include('sales.cash._inc.product')
                        @include('sales.cash._inc.items')
                        <div class="row">
                            @include('sales.cash._inc.payment')
                            @include('sales.cash._inc.submit')
                        </div>
                        {{ form()->close() }}
                    </div>
                </div>
                @include('_inc.cash-customer.add', ['dropdown'=> 'customer-drop-down', 'btn' => 'cus-drop-down-add-btn'])
            </div>
        </div>
        <div ng-controller="HanoverController">
            <!-- performed sales -->
            @include('sales.cash._inc.performed-sales')
            @include('sales.cash.handover.index')
        </div>
    </section>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
    <style>
        .error {
            color: red;
        }

        .card-title-bg {
            background-color: #272c32;
            padding: 20px;
            color: #fff;
        }

        .item-selection-panel {
            background-color: #00897b;
            padding: 10px 20px 10px 20px;
            color: #fff;
        }

        [type="radio"] + label:before, [type="radio"] + label:after {
            margin: 2px !important;
            width: 20px !important;
            height: 20px !important;
        }

        [type="radio"]:not(:checked) + label:before, [type="radio"]:not(:checked) + label:after {
            border: 2px solid #272c31;
        }

        [type="radio"]:checked + label:after, [type="radio"].with-gap:checked + label:before, [type="radio"].with-gap:checked + label:after {
            border: 2px solid #272c31;
        }

        [type="radio"]:checked + label:after, [type="radio"].with-gap:checked + label:after {
            background-color: #272c31;
        }
    </style>
@endsection
@section('script')
    @parent
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script src="{{ asset('js/vendor/barcode.js') }}"></script>
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    @include('sales.cash._inc.script')
    @include('sales.cash.handover.script')
    <script>
        $('#toggle-fullscreen').on('click', function(){
            // if already full screen; exit
            // else go fullscreen
            $container = $('#fullscreen-container');
            if (
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.mozFullScreenElement ||
                document.msFullscreenElement
            ) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            } else {
                element = $container.get(0);
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.mozRequestFullScreen) {
                    element.mozRequestFullScreen();
                } else if (element.webkitRequestFullscreen) {
                    element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                } else if (element.msRequestFullscreen) {
                    element.msRequestFullscreen();
                }
            }
        });

        $cashSalesEl = {
            orderMode: $('.order-mode'),
            customerPanel: $('.customer-panel'),
            paymentMode: $('.payment-mode')
        };

        $cashSalesEl.orderMode.change(function (e) {
            e.preventDefault();
            handleSalesMode($(this).val());
        });

        @if(old('_token'))
            handleSalesMode('{{old('order_mode')}}');
        @else
            var orderModeOnLoad = $cashSalesEl.orderMode.val();
            handleSalesMode(orderModeOnLoad);
        @endif

        function handleSalesMode(orderModeOnLoad) {
            if (orderModeOnLoad === 'Cash') {
                $cashSalesEl.customerPanel.hide();
                $cashSalesEl.paymentMode.removeAttr('disabled');
            } else if (orderModeOnLoad === 'Customer') {
                $cashSalesEl.customerPanel.show();
                $cashSalesEl.paymentMode.attr('disabled', 'disabled');
            }
        }

        var customerDropDown = $('.customer-drop-down');
        customerDropDown.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

    </script>
@endsection