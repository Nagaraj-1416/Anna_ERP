<script>
    var  $addLegend = $('.add-legend');
    var $loopCheckbox = $('.loop-checkbox');
    var $clone = $('#clones').find('.code');
    var $emailTemplateForm = $('#email_template_form');
    var $linkTextUpdateModel = $('#link_text_update_model');

    var contentEditable = false;
    var subjectEditable = false;
    var url = $emailTemplateForm.attr('action');
    var _token = '{{ csrf_token() }}';

    var links = [{!! $template->links && count($template->links) ? json_encode($template->links) : '{}' !!}][0];
    var loops = [{!! $template->loops && count($template->loops) ? json_encode($template->loops) : '{}' !!}][0];
    var variables = [{!! $template->variables && count($template->variables) ? json_encode($template->variables) : '{}' !!}][0];

    CKEDITOR.replace( 'subject', {
        skin: 'office2013',
        allowedContent: true,
        height: 60,
        removeButtons: 'About,base64image',
        extraPlugins: 'emailtemplate',
        image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
        image2_disableResizer: true,
        removePlugins: 'save,newpage,preview,templates,toolbar,elementspath',
        resize_enabled: false,
        htmlEncodeOutput: false,
        fillEmptyBlocks: false,
        entities: false
    });

    CKEDITOR.replace( 'content', {
        skin: 'office2013',
        allowedContent: true,
        height: 500,
        removeButtons: 'About,base64image',
        extraPlugins: 'emailtemplate',
        image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
        image2_disableResizer: true,
        removePlugins: 'save,newpage,preview,templates,elementspath',
        resize_enabled: false,
        toolbar: [
            { name: 'styles', items: [ 'Format' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'insert', items: [ 'Table' ] }
        ],
        htmlEncodeOutput: false,
        fillEmptyBlocks: false,
        entities: false
    });

    CKEDITOR.instances['subject'].on('instanceReady', function(evt) {
        var editor = evt.editor;

        editor.dataProcessor.writer.indentationChars = '';
        editor.dataProcessor.writer.lineBreakChars = '';

        editor.on('focus', function(e) {
            subjectEditable = true;
        });

        editor.on('blur', function(evt){
            subjectEditable = false;
        });
    });

    CKEDITOR.instances['content'].on('instanceReady', function(evt) {
        var editor = evt.editor;

        editor.dataProcessor.writer.indentationChars = '';
        editor.dataProcessor.writer.lineBreakChars = '';

        editor.on('focus', function(e) {
            contentEditable = true;
        });

        editor.on('blur', function(evt){
            contentEditable = false;
        });

        editor.on('doubleclick', function (event) {
            if(typeof  (event.data) === 'undefined') return;
            if(typeof  (event.data.element) === 'undefined') return;
            var $element = event.data.element;
            if(!$element.hasClass('link')) return;
            $element = $($element);
            var $submitBtn = $linkTextUpdateModel.find('.ui.positive.button');
            $submitBtn.removeClass('disabled loading');

            var linkId = $element.attr('data-id');
            var link = links[linkId];

            var $linkTextInput = $linkTextUpdateModel.find('input[name="link_text"]');
            $linkTextInput.val(link.text);

            $linkTextUpdateModel.modal({
                onApprove : function() {
                    $submitBtn.addClass('disabled loading');
                    links[linkId].text = $linkTextInput.val();
                    $.ajax({
                        url: url,
                        method: 'patch',
                        data: { '_token' : _token, 'links': links },
                        success: function () {
                            $submitBtn.removeClass('disabled loading');
                            $linkTextUpdateModel.modal('hide');
                        }
                    });
                    return false;
                }
            }).modal('show').modal('refresh');
        });
    });

    $addLegend.click(function () {
        if(!contentEditable && !subjectEditable) return;

        var $this = $(this);
        var type = $this.data('type');
        var id = $this.data('id');
        var name = $this.data('name');
        var $tmpClone = $clone.clone();

        $tmpClone.addClass(type);
        $tmpClone.attr('data-id', id);
        $tmpClone.text(name);

        var $parent = $('<div>');
        $parent.append($tmpClone);

        if(subjectEditable && type === 'variable') {
            CKEDITOR.instances['subject'].insertHtml($parent.html());
        }

        if(contentEditable) {
            CKEDITOR.instances['content'].insertHtml($parent.html());
        }
    });

    $loopCheckbox.checkbox({
        onChange: function () {
            var $this = $(this);
            var $checkBox = $this.parents('.ui.checkbox');
            var checked = $checkBox.checkbox('is checked');
            var loopId = $checkBox.data('loop-id');
            var fieldName = $checkBox.data('field-name');
            loopTransform(loopId, fieldName, checked);
            updateLoops();
        }
    });

    function loopTransform(loopId, fieldName, checked) {
        if(!loops) return;
        loops[loopId].fields[fieldName].enabled = checked;
    }

    function updateLoops() {
        $.ajax({
            url: url,
            method: 'patch',
            data: { '_token' : _token, 'loops': loops }
        });
    }
</script>