@extends('layouts.master')
@section('title', 'Create Transfer')
@section('breadcrumbs')
    {!! breadcrumbRender($breadcrumb, 'Create Transfer') !!}
@endsection
@section('content')
    <div class="row" ng-controller="TransferController">
        <div class="col-12">
            <div class="card card-outline-inverse">
                <div class="card-header">
                    <h3 class="text-white">{{ $type }} Transfer Details</h3>
                </div>
                <div class="card-body">
                    {{ form()->open([ 'route' => 'finance.transfer.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @include('finance.transfer._inc.form')
                        <hr>
                        {!! form()->bsSubmit('Submit') !!}
                        {!! form()->bsCancel('Cancel', 'dashboard') !!}
                    {{ form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/vendor/form.css') }}">
@endsection
@section('script')
    <script src="{{ asset('js/vendor/object-helper.js') }}"></script>
    <script src="{{ asset('js/vendor/form.js') }}"></script>
    <script>
        app.controller('TransferController', ['$scope', '$http', function ($scope, $http) {
            $scope.today = '{{ carbon()->toDateTimeString() }}';

            $scope.cihPanel = $('.cih-panel');

            $scope.type = '{{ $type }}';

            $scope.dropdowns = {
                sender: $('.sender-drop-down'),
                receiver: $('.receiver-drop-down'),
                handedOverTo: $('.handed-order-to-drop-down'),
                depositedTo: $('.deposited-to-drop-down')
            };

            $scope.formElement = {
                transferMode: $('.transfer-mode'),
                byHandPanel: $('.by-hand-panel'),
                depositedToBankPanel: $('.deposited-bank-panel'),
                transferAmount: $('.transfer-amount'),
                chkCheque: $('.chk-col-cyan')
            };

            $scope.dropdowns.sender.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });

            $scope.dropdowns.receiver.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });

            $scope.dropdowns.handedOverTo.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });

            $scope.dropdowns.depositedTo.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false
            });

            if($scope.type == 'Cheque'){
                $scope.formElement.transferAmount.attr('readonly', true);
                $scope.cihPanel.show();
            }else{
                $scope.cihPanel.hide();
            }

            $scope.formElement.transferMode.change(function (e) {
                e.preventDefault();
                handleTransferMode($(this).val());
            });

            @if(old('_token'))
                handleTransferMode('{{old('transfer_mode')}}');
            @else
                var transferModeOnLoad = $scope.formElement.transferMode.val();
                handleTransferMode(transferModeOnLoad);
            @endif

            function handleTransferMode(transferModeOnLoad) {
                if (transferModeOnLoad === 'ByHand') {
                    $scope.formElement.byHandPanel.show();
                    $scope.formElement.depositedToBankPanel.hide();
                } else if (transferModeOnLoad === 'DepositedToBank') {
                    $scope.formElement.byHandPanel.hide();
                    $scope.formElement.depositedToBankPanel.show();
                }
            }

            $scope.formElement.chkCheque.change(function (e) {
                e.preventDefault();
                if($(this).is(":checked")) {
                    var addedTransAmount = $scope.formElement.transferAmount.val();
                    var addedChequeAmount = $(this).data('value');
                    var addedAmount = Number(addedTransAmount) + Number(addedChequeAmount);
                    $scope.formElement.transferAmount.val(addedAmount)
                }else{
                    var deductedTransAmount = $scope.formElement.transferAmount.val();
                    var deductedChequeAmount = $(this).data('value');
                    var deductedAmount = Number(deductedTransAmount) - Number(deductedChequeAmount);
                    $scope.formElement.transferAmount.val(deductedAmount)
                }
            });


        }]);
    </script>
@endsection
