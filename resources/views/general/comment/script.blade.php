<script>
    /** append editable comment to textarea and enable */
    var editClick = function () {
        $('.clickEdit').on('click', function () {
            var id = $(this).data('id');

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
            commentUpdatePanel.find('.editCancel').attr('data-id', id);
            $('#comments').find('.comments form').parent().remove();
            commentItem.show();
            commentItemHolder.append(commentUpdatePanel);
        });
    };
    editClick();

    /** update appended comment */
    var appendedComment = $('.comment');
    appendedComment.on('click', '.updateClick', function () {
        var id = $(this).data('id');
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
            }, function () {
                location.reload();
            });
        });
    });

    /** edit cancel button */
    var editCancel = function () {
        appendedComment.on('click', '.editCancel', function () {
            var id = $(this).data('id');
            var form = $('form[data-id="' + id + '"]');
            form.parent().fadeOut();
            form.find('.help-block-custom').text('');
        });
    };
    editCancel();

    /** delete button action */
    var deleteClick = function () {
        $('.clickDelete').on('click', function () {
            var commentId = $(this).data('id');
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
                            location.reload();
                        });
                    });
                }
            });
        });
    };
    deleteClick();

</script>