/**is_trial hide show */
$('body').on('click', '.is_trial', function () {
    if ($(this).prop('checked') == true) {
        $('.trial_days').show();
    } else {
        $('.trial_days').val('');
        $('.trial_days').hide();
    }
});
/**** ADD Remove contacts *****/

//Once add button is clicked
$('body').on('click', '.add_button_comp', function () {
    $.ajax({
        url: append_contact_route,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (data) {
            $('.contact_div').append(data);
            contactDetailsAddAfter();
        },
    });


});

//Once remove button is clicked
$('body').on('click', '.remove_button_comp', function (e) {
    e.preventDefault();
    $(this).parent().parent().parent('div').remove();

});

$(document).ready(function () {
    contactDetailsLoad();
});