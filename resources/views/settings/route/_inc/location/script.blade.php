<script>
    var $locationForm = $('#location_form'),
        $location_form_temp = $('#location_form_temp'),
        $addNewBtn = $('#add_new'),
        $clonedForm = null,
        $errors = {},
        $locationDetailCount = 0;
    @if(isset($errors))
        $errors = [{!! json_encode($errors->getMessages() ? $errors->getMessages() : []) !!}][0];
            @endif
            @if(old('_token'))
    var $oldValues = [{!! json_encode(old('location')) !!}][0];
    if ($oldValues) {
        $locationForm.parent().parent().removeClass('hidden');
        $.each($oldValues.name, function () {
            addNewLocationForm($oldValues);
        });
    }
            @endif

            @if(!old('_token') && isset($locations))
    var $locations = [{!! json_encode($locations) !!}][0].locations;
    if ($locations) {
        $locationForm.parent().parent().removeClass('hidden');
        $.each($locations.name, function () {
            addNewLocationForm($locations, true);
        });
    }

    @endif
    function addNewLocationForm(values, edit) {
        $locationDetailCount++;
        $clonedForm = $location_form_temp.clone();
        $clonedForm.html($clonedForm.html().replace(/LD/g, $locationDetailCount));
        if (values) {
            var $name = values.name;
            var $notes = values.notes;
            var $ids = values.id;
            var count = 1;
            if (edit) {
                count = $locationDetailCount - 1;
            } else {
                count = $locationDetailCount;
            }

            if ($name) {
                $clonedForm.find('#location_name' + $locationDetailCount).attr('value', $name[count])
            }
            if ($notes) {
                $clonedForm.find('#location_notes' + $locationDetailCount).text($notes[count])
            }
            if ($ids) {
                $clonedForm.find('#location_id' + $locationDetailCount).attr('value', $ids[count])
            }
        }
        if ($errors) {
            $.each($errors, function (key, value) {
                var elemName = key.split('.');
                var elemForError = '#' + elemName[0] + '_' + elemName[1] + elemName[2];
                $clonedForm.find(elemForError).parent().addClass('has-danger');
            })
        }
        $locationForm.append($clonedForm.html());
        if ($locationDetailCount > 1) {
            $locationForm.find('.removeBtn').removeClass('hidden');
        }
    }

    $addNewBtn.click(function () {
        addNewLocationForm();
    });

    function removeForm(elem) {
        $locationDetailCount--;
        if ($locationDetailCount <= 1) {
            $locationForm.find('.removeBtn').addClass('hidden');
        }
        $(elem).parent().parent().parent().remove();
    }

    $('#add_new_location').click(function () {
        $locationForm.parent().parent().removeClass('hidden');
        if (!$locationDetailCount) {
            addNewLocationForm();
        }
    });
    $('#cancel-btn').click(function () {
        $locationForm.parent().parent().addClass('hidden');
        $locationDetailCount = $locationDetailCount - 1;
        $locationForm.parent().parent().find('#location_form').find('div').remove();

        $addNewBtn.removeClass('hidden');
        $('.location-edit-btn').removeClass('hidden');
    });

    var locations = @json($route->locations);
    $('.location-edit-btn').click(function () {
        var locationId = $(this).data('id');
        var location = _.find(locations, function (val) {
            if (val.id === locationId) return val;
        });

        $('html, body').animate({
            scrollTop: $(".page-wrapper").offset().top
        }, 1000);

        var values = [];
        values['name'] = [];
        values['notes'] = [];
        values['id'] = [];
        values['name'][0] = location.name;
        values['notes'][0] = location.notes;
        values['id'][0] = location.id;
        var route = $locationForm.parent().parent().parent().attr('action');
        $locationForm.parent().parent().parent().attr('action', route + '?location=' + location.id);
        $locationForm.parent().parent().removeClass('hidden');
        addNewLocationForm(values, true);
        $addNewBtn.addClass('hidden');
        $('.location-edit-btn').addClass('hidden');
    });

    $('.location-delete-btn').click(function () {
        var id = $(this).data('id');
        console.log(id);
        var deleteUrl = '{{ route('setting.route.location.delete', ['route' => $route,  'location'=>'ID']) }}';
        deleteUrl = deleteUrl.replace('ID', id);
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DB2828',
            confirmButtonText: 'Yes, Delete!'
        }).then(function (isConfirm) {
            if (isConfirm.value) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {'_token': '{{ csrf_token() }}'},
                    success: function (result) {
                        swal(
                            'Deleted!',
                            'Location deleted successfully!',
                            'success'
                        );
                        setTimeout(location.reload(), 300);
                    }
                });
            }
        });
    });
</script>