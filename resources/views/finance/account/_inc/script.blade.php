<script>
    var createAccountForm = $('#chartOfAccFormCreate');
    var accTypeDropDown = $('.acc-type-drop-down');
    var parentAccDropDown = $('.parent-acc-drop-down');
    var groupDropDown = $('.group-drop-down');
    var editRoute = '{{ route('finance.account.edit', ['account' => 'ACCOUNT']) }}';
    var showRoute = '{{ route('finance.account.show', ['account' => 'ACCOUNT']) }}';
    var updateRoute = '{{ route('finance.account.update', ['account' => 'ACCOUNT']) }}';
    var editable = false;
    var GroupSearchRoute = '{{ route('setting.account.group.search.by.type', ['type' => 'TYPE']) }}';

    accTypeDropDown.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false,
        onChange: function (value) {
            var url = GroupSearchRoute.replace('TYPE', value);
            groupDropDown.dropdown('clear');
            groupDropDown.dropdown('setting', {
                forceSelection: false,
                saveRemoteData: false,
                apiSettings: {
                    url: url + '/{query}',
                    cash: false,
                },
            });
        }
    });

    parentAccDropDown.dropdown('setting', {
        forceSelection: false,
        saveRemoteData: false
    });


    createAccountForm.modal({
        autofocus: false,
        closable: false
    });

    function createAccount() {
        editable = false;
        createAccountForm.modal('show');
    }

    @if($errors->has('name') || $errors->has('account_type_id'))
    createAccountForm.modal('show');

    @endif

    function closeModel() {
        createAccountForm.find('.header').text('Create an Account');
        createAccountForm.modal('hide');
    }

    function editAccount(elem) {
        editable = true;
        createAccountForm.find('.header').text('Edit Account');
        var id = $(elem).data('id');
        $.get(editRoute.replace('ACCOUNT', id), function (response) {
            populate($('form'), response.account)
        })
    }

    var old = @json(old());
    if (old.hasOwnProperty('_token')) {
        groupDropDown.dropdown('set text', old.group_name);
        groupDropDown.dropdown('set value', old.group_id);
    }

    function populate(frm, data) {
        $.each(data, function (key, value) {
            var ctrl = $('[name=' + key + ']', frm);
            $('#account_id').val(data.id);
            switch (ctrl.prop("type")) {
                case "radio":
                case "checkbox":
                    ctrl.each(function (index, elem) {
                        console.error($(elem).attr('value'), value);
                        if ($(elem).attr('value') === value) $(elem).prop("checked", value);
                    });
                    break;
                default:
                    if (ctrl.prop('name') === 'account_type_id') {
                        accTypeDropDown.dropdown('set text', data.type ? data.type.name : '');
                        accTypeDropDown.dropdown('set value', data.type ? data.type.id : '');
                    }
                    if (ctrl.prop('name') === 'parent_account_id') {
                        if (data.parent) {
                            parentAccDropDown.dropdown('set text', data.parent.name);
                            parentAccDropDown.dropdown('set value', data.parent.id);
                        }
                    }
                    if (ctrl.prop('name') === 'group_id') {
                        if (data.group) {
                            groupDropDown.dropdown('set text', data.group.name);
                            groupDropDown.dropdown('set value', data.group.id);
                        }
                    }
                    ctrl.val(value);
            }
        });


        createAccountForm.modal('show');
    }

    $('.submitBtn').click(function (e) {
        if (editable) {
            e.preventDefault();
            var data = {};
            getValues(data);
            $.ajaxSetup({
                headers:
                    {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $.ajax({
                method: "patch",
                data: data,
                url: updateRoute.replace('ACCOUNT', $('#account_id').val())
            }).done(function () {
                window.location.reload();
            }).fail(function (response) {
                if (response.hasOwnProperty('responseJSON') && response.responseJSON.hasOwnProperty('errors')) {
                    var errors = response.responseJSON.errors;
                    $.each(errors, function (index, value) {
                        $('[name=' + index + ']').parent().addClass('has-danger').find('.form-control-feedback').text(value);
                    })
                }
            })
        }
    });

    function getValues(data) {
        $.each($('#chartOfAccFormCreate').find('input'), function (key, value) {
            if ($(value).prop('name') === 'closing_bl_carried') {
                data['closing_bl_carried'] = $('#chartOfAccFormCreate').find('input:checked').val();
            } else {
                data[$(value).prop('name')] = $(value).val();
            }
        });
        data['notes'] = $('[name="notes"]').val();
    }

    app.controller('AccountController', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
        $scope.loadGroups = function () {
            $scope.groupListUrl = '{{ route('finance.account.group.list') }}';
            $http.get($scope.groupListUrl).then(function (response) {
                $scope.groups = response.data;
            });
        };
        $scope.loadGroups();

        $scope.editRowAccount = function(id) {
            $.get(editRoute.replace('ACCOUNT', id), function (response) {
                populate($('form'), response.account)
            })
        };

        $scope.getShowUrl = function (id) {
            return showRoute.replace('ACCOUNT', id);
        }
    }]).directive('accountLoop', function () {
        return function (scope, element, attrs) {
            if (scope.$last) {
                $('.account-preloader').addClass('hidden');
            }
        }
    });
</script>
