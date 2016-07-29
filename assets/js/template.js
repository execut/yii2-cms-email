$(function() {
    // Set eventhandlers
    $(document)
        .on('change', '#template-action', toggleToField);

    $('#template-action').trigger('change')
});

function toggleToField() {
    if($(this).val() == 'sent') {
        $('.template-email-to').hide();
    }
    else {
        $('.template-email-to').show();
    }
}