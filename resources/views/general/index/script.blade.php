<script>
    // routes from parent blade
    var routes = @json($routes);
    // Main Controller
    app.controller('IndexController', ['$scope', '$http', function ($scope, $http) {
        $scope.selectedItem = null;
        $scope.loading = true;
        $scope.datas = [];
        //Get All Data From Given Route
        $http.get(routes.index + '?ajax=true').then(function (data) {
            $scope.loading = false;
            $scope.datas = data.data.datas;
            var key = $scope.getKeyByValue($scope.datas, 1);
            $scope.selectItem($scope.datas[key]);
        });

        //get Model Image
        $scope.getImage = function (data) {
            if (data) {
                return routes.image.replace('MODEL', data.id)
            }
            return '#';
        };

        //select item function
        var $form = $('#form');
        $scope.selectItem = function (item) {
            $scope.removeError();
            if ($scope.selectedItem && item.id === $scope.selectedItem.id) return item;
            $form.hide();
            $scope.getItem(item);
            $scope.loading = true;
            $scope.selectedItem = null;
        };

        //get item for select
        $scope.getItem = function (item) {
            $http.get(routes.show.replace('MODEL', item.id) + '?ajax=true').then(function (response) {
                $form.fadeIn(1000);
                $scope.selectedItem = response.data.item;
                $scope.getLogs();
                $scope.getComments();
                $scope.loading = false;
            });
        };


        //for get All logs
        $scope.logs = [];
        $scope.getLogs = function () {
            $http.get(routes.logs.replace('ID', $scope.selectedItem.id)).then(function (response) {
                $scope.logs = response.data;
            })
        };

        //get all comments
        $scope.comments = [];
        $scope.getComments = function () {
            $http.get(routes.comments.replace('ID', $scope.selectedItem.id)).then(function (response) {
                $scope.comments = response.data;
            })
        };
        //comment form submit
        var commentForm = $('#commentForm');
        $scope.submitForm = function submitForm(e) {
            $scope.modelName = '{{ $modelName }}';
            $scope.modelId = $scope.selectedItem.id;
            e.preventDefault();
            var data = {
                comment: $scope.commentText,
                model: $scope.modelName,
                model_id: $scope.modelId,
                _token: "{{ csrf_token() }}"
            };
            var saveRoute = "{{ route('comment.create', ['model' => 'MODEL']) }}";
            $.ajax({
                method: "POST",
                url: saveRoute.replace('MODEL', $scope.modelId) + '?model=true',
                data: data
            }).done(function () {
                swal({
                    title: "Success",
                    text: "Comment created successfully!",
                    type: "success"
                }).then(function (result) {
                    if (result.value) {
                        $scope.getComments();
                    }
                })
            }).catch(function (error) {
                if (error) {
                    $scope.error = true;
                    $scope.setError();
                }
            });
        };
        //Add error message
        $scope.setError = function () {
            commentForm.find('.form-control-feedback').text('The comment field is required.');
            commentForm.find('.comment-text').addClass('has-danger');
        };
        //remove error message
        $scope.removeError = function () {
            commentForm.find('.form-control-feedback').text('');
            commentForm.find('.comment-text').removeClass('has-danger');
        };

        // Get Count Of Objects
        $scope.getCount = function (object) {
            return Object.keys(object).length
        };
        //To Capitalize First Letter
        $scope.capitalize = function (text) {
            return (!!text) ? text.charAt(0).toUpperCase() + text.substr(1).toLowerCase() : '';
        };

        //Object find
        $scope.getKeyByValue = function (object, value) {
            return Object.keys(object).find(function (key) {
                if (object[key].id === value) {
                    return object[key];
                }
            });
        };

        //Edit Comment
        $scope.editClick = function (elem) {
            var id = $(elem).data('id');
            var commentHolder = $('.comment[data-id="' + id + '"]');
            var commentItemHolder = commentHolder.find('.comment-block');
            var commentItem = commentItemHolder.find('.comment-item');
            commentItem.hide();
            commentItemHolder.find('.comments').remove();
            var commentUpdatePanel = $('.comment-update-panel .comments').clone();
            commentUpdatePanel.find('form').attr('data-id', id);
            commentUpdatePanel.find('input[name="comment_id"]').val(commentHolder.data('id'));
            commentUpdatePanel.find('textarea').val(commentItemHolder.attr('data-value'));
            commentUpdatePanel.find('.updateClick').attr('data-id', id);
            commentUpdatePanel.find('.updateClick').bind('click', $scope.updateForm);
            commentUpdatePanel.find('.editCancel').attr('data-id', id);
            commentUpdatePanel.find('.editCancel').bind('click', $scope.editCancel);
            $('#comments').find('.comments form').parent().remove();
            commentItem.show();
            commentItemHolder.append(commentUpdatePanel);
        };

        //Update Comment
        $scope.updateForm = function (elem) {
            var id = $(elem.target).data('id');
            var form = $('form[data-id="' + id + '"]');
            var commentId = form.find('input[name="comment_id"]').val();
            var commentValue = form.find('textarea').val();
            if (!commentValue) {
                form.find('textarea').addClass('error');
                form.find('.help-block-custom').text('The comment field is required.');
                return false;
            }
            $.ajax({
                method: "POST",
                url: "{{ route('comment.update') }}" + '?ajax=true',
                data: {commentId: commentId, commentValue: commentValue, _token: "{{ csrf_token() }}"}
            }).done(function () {
                var comment = $('.comment[data-id="' + id + '"]');
                comment.find('.content').attr('data-value', commentValue);
                comment.find('.comment-item').first().text(commentValue);
                form.parent().fadeOut();
                swal({
                    title: "Success",
                    text: "Comment updated successfully!",
                    type: "success"
                }).then(function () {
                    $scope.getComments();
                    $scope.editCancel(elem)
                });
            });
        };


        /** edit cancel button */
        $scope.editCancel = function (elem) {
            var id = $(elem.target).data('id');
            var form = $('form[data-id="' + id + '"]');
            form.parent().fadeOut();
            form.find('.help-block-custom').text('');
        };

        /** delete button action */
        $scope.deleteClick = function (elem) {
            var commentId = $(elem).data('id');
            swal({
                title: "Are you sure?",
                text: "You won't be able to revert this action!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DB2828",
                confirmButtonText: "Yes, Delete!",
                closeOnConfirm: false
            }).then(function (isConfirm) {
                if (isConfirm.value) {
                    $.ajax({
                        url: "{{ route('comment.delete') }}" + '?ajax=true',
                        type: 'POST',
                        data: {commentId: commentId, _token: "{{ csrf_token() }}"}
                    }).done(function () {
                        swal({
                            title: "Success",
                            text: "Comment deleted successfully!",
                            type: "success"
                        }).then(function () {
                            $scope.getComments();
                        });
                    });
                }
            });
        };
    }]);

    //scrollable div
    $('#scrollDiv').slimScroll({
        position: 'left',
        height: '500px',
        railVisible: true
    });
</script>