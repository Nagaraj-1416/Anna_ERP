<script src="{{ asset('js/vendor/form.js') }}"></script>
<script src="{{ asset('js/vendor/object-helper.js') }}"></script>
<script>
    app.controller('GrnController', function ($scope, $timeout, $http) {
        $scope.today = '{{ carbon()->toDateString() }}';

        $scope.billAmount = null;

        $scope.ownVehiclePanel = $('.own-vehicle-panel');
        $scope.hiredVehiclePanel = $('.hired-vehicle-panel');

        $scope.dropdowns = {
            vehicle: $('.vehicle-drop-down'),
            driver: $('.driver-drop-down'),
            helper: $('.helper-drop-down'),
            packingType: $('.packing-type-drop-down')
        };

        $scope.formElement = {
            transferBy: $('.transfer-by'),
            itemCheck: $('.item-chk-col-cyan'),
            billAmountInput: $('.bill-amount'),
            itemIssueQty: $('.item-issue-qty')
        };

        $scope.dropdowns.packingType.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.dropdowns.vehicle.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.dropdowns.driver.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.dropdowns.helper.dropdown('setting', {
            forceSelection: false,
            saveRemoteData: false
        });

        $scope.formElement.transferBy.change(function (e) {
            e.preventDefault();
            handleTransferBy($(this).val());
        });

        @if(old('_token'))
            handleTransferBy('{{old('transfer_by')}}');
        @else
            var transferByOnLoad = $scope.formElement.transferBy.val();
            handleTransferBy(transferByOnLoad);
        @endif

        function handleTransferBy(transferByOnLoad) {
            if (transferByOnLoad === 'OwnVehicle') {
                $scope.ownVehiclePanel.show();
                $scope.hiredVehiclePanel.hide();
            } else if (transferByOnLoad === 'HiredVehicle') {
                $scope.ownVehiclePanel.hide();
                $scope.hiredVehiclePanel.show();
            }else{
                $scope.ownVehiclePanel.hide();
                $scope.hiredVehiclePanel.hide();
            }
        }

        $scope.formElement.itemCheck.change(function (e) {
            e.preventDefault();
            if($(this).is(":checked")) {
                var availableBillAmount = $scope.formElement.billAmountInput.val();
                var checkedItemAmount = $(this).data('value');
                var addedAmount = Number(availableBillAmount) + Number(checkedItemAmount);
                $scope.formElement.billAmountInput.val(addedAmount);

                var parent = $(this).parents(".item-row");
                parent.find(".item-issue-qty").removeAttr("readonly");

            }else{
                var deductedTBillAmount = $scope.formElement.billAmountInput.val();
                var unCheckedItemAmount = $(this).data('value');
                var deductedAmount = Number(deductedTBillAmount) - Number(unCheckedItemAmount);
                $scope.formElement.billAmountInput.val(deductedAmount);

                var parent = $(this).parents(".item-row");
                parent.find(".item-issue-qty").val(null);
                parent.find(".item-issue-qty").attr("readonly", "readonly");

                var requestedQty = parent.find(".item-requested-qty").val();
                var purchaseRate = parent.find(".item-issue-qty").data('value');
                parent.find(".item-amount").val(requestedQty*purchaseRate);

            }
        });

        $scope.formElement.itemIssueQty.keyup(function (e) {
            e.preventDefault();
            var parent = $(this).parents(".item-row");
            var requestedQty = parent.find(".item-requested-qty").val();
            var issuingQty = $(this).val();
            var purchaseRate = $(this).data('value');
            if(issuingQty !== '' && purchaseRate !== ''){
                parent.find(".item-amount").val(issuingQty*purchaseRate);
            }else{
                parent.find(".item-amount").val(requestedQty*purchaseRate);
            }
        });

    });
</script>