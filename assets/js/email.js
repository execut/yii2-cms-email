$(function() {
    // Set eventhandlers
    $(document)
        .on('click', '.select-on-check-all', toggleCheckboxes)
        .on('click', '#gridview-container .kv-row-select input:enabled', toggleSelectAll)
        .on('click', '#batch-read', doBatchRead)
        .on('change', ':radio[name="actionType"]', reloadGridView);
});

function toggleCheckboxes(e) {
    // Check / uncheck all checkboxes
    $('#gridview-container .kv-row-select input:enabled').prop('checked', ($(this).is(':checked')) ? true : false);

    toggleReadBtn();
}

function toggleSelectAll(e) {
    // If one checkbox is not checked, the "select-all" checkbox should also be no longer checked
    if (!$(this).is(':checked'))
        $('.select-on-check-all').prop('checked', false);

    toggleReadBtn();
}

function toggleReadBtn() {

    // If at least one checkbox is checked the read button has to be shown
    if ($('#gridview-container .kv-row-select input:checked').length || $('.select-on-check-all:checked').length) {
        $('#batch-read').show();
    } else {
        $('#batch-read').hide();
    }
}

function doBatchRead(event) {
    event.preventDefault();

    var ids = [];

    $('#gridview-container').find("input[name='selection[]']:checked").each(function () {
        ids.push($(this).parent().closest('tr').data('key'));
    });

    bootbox.confirm($('#bootbox-batch-read-msg').text(), function (confirmed) {
        if (confirmed) {

            var request = $.post('email/batch-read', {ids: ids});

            request.done(function(response) {
                if (response.status == 1)
                {
                    // Hide read button
                    $('#batch-read').hide();

                    if (parseInt(response.unread) == 0) {
                        $('.nav-item-unread-mails .unread-emails').addClass('hidden');
                    } else {
                        $('.nav-item-unread-mails .unread-emails').removeClass('hidden').html(response.unread);
                    }

                    // Reload the grid
                    $.pjax.reload({container:'#grid-pjax'});
                } else {
                    // @todo Do something
                }
            });
        }
    });
}

function reloadGridView(event) {
    var actionType = $('.btn-group .btn.active :radio').val();

    $.pjax.reload({container:'#grid-pjax', url:'?actionType='+actionType});
}