$(document).ready(function () {
    if ($('.customer_type').val() == parent_company) {
         //remove required star class
        $('.removeRequired').removeClass('required');
    }
});
/**select 2 */
$('.select2').wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select Parent Company',
        dropdownParent: $('.select2').parent()
      });

/**parent company hide show */
$('body').on('change', '.customer_type', function () {
    if ($(this).val() == parent_company) {
        $('.parent_company').show();
        $('.individual_brand').hide();
        //remove required star class
        $('.removeRequired').removeClass('required');
    } else {
        $('.parent_company').hide();
        $('.individual_brand').show();
        //add required star class
        $('.removeRequired').addClass('required');
    }
});
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