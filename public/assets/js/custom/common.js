
toastr.options = {
            'closeButton': true,
            'debug': false,
            'newestOnTop': true,
            'progressBar': true,
            'positionClass': 'toast-top-right',
            'preventDuplicates': false,
            'showDuration': '1000',
            'hideDuration': '1000',
            'timeOut': '5000',
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut',
        }
function displaySuccessMessage(message) {
   
    toastr.success(message);
}

function displayErrorMessage(message) {
     toastr.error(message);   
}

function commonFormErrorShow(obj, err)
{

    if (obj.status == 422) {
       var message = "";
        var errorJson = JSON.parse(obj.responseText);
        $.each(errorJson.errors, function (key, value)
        {
            message += value + "<br>";
        });

         toastr.error(message);
    } else if (obj.status == 400) {
         toastr.error(obj.responseText);
    	

    } else {
        toastr.error(formatErrorMessage(obj, err));
    	

    }
}
/**delete record */
var isUserDelete = false;
function userDelete(userId, elem) {
    
    $.confirm({
            title: "Delete Confirmation!",
            content: `<div>Are you sure you want to delete this record?</div>`,
            buttons: {
                sayMyName: {
                    text: 'Yes',
                    btnClass: 'btn  btn-success',
                    action: function() {
                    $.ajax({
                        type: "DELETE",
                        url: $(elem).attr('data-url'),                   
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            show_loader();
                            //$('.loading-spinner').toggleClass('active');
                        },
                        success: function (response) {
                        // $('.loading-spinner').toggleClass('active');
                            hide_loader(); 
                            isUserDelete = false;
                            displaySuccessMessage(response.message);
                            var login_role = "{{ auth()->user()->role }}";
                            var admin_role = "{{ config('params.admin_role') }}";
                            if ($(elem).attr('data-type') == 'customer' && login_role == admin_role) {
                                let previousPage = $('#customerDataTable').DataTable().page.info().page;
                                $('#customerDataTable').DataTable().ajax.reload();
                                $('#customerDataTable').DataTable().page(previousPage).draw(false);
                            } else {
                                let previousPage = $('#dataTable').DataTable().page.info().page;
                                $('#dataTable').DataTable().ajax.reload();
                                $('#dataTable').DataTable().page(previousPage).draw(false);
                            }
                        },
                        error: function (xhr, err) {
                        //  $('.loading-spinner').toggleClass('active');
                        hide_loader(); 
                            isUserDelete = false;
                            if(typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0)
                            {
                                if (typeof xhr.responseJSON.errors != "undefined") {
                                    commonFormErrorShow(xhr, err);
                                }else{
                                    displayErrorMessage(xhr.responseJSON.message);
                                }
                            }
                            else
                            {
                                displayErrorMessage(xhr.responseJSON.errors);
                            }
                        }
                    });

                    }
                },
                No:  {
                    
                    text: 'No',
                    btnClass: 'btn btn-default',
                    
                }

            }
        });
    
}
var ischange = false;
function changeStatus(uuid,status, elem) {
    var type = $(elem).attr('data-type');
    let title = `<div>Are you sure you want to `+ $(elem).attr('data-status')+` this record?</div>`;
    if(type){
         title = `<div>Are you sure you want to set `+ $(elem).attr('data-status')+` this `+ type+`?</div>`;
    }
    
    $.confirm({
            title: "Confirmation!",
            content: title,
            buttons: {
                sayMyName: {
                    text: 'Yes',
                    btnClass: 'btn  btn-success',
                    action: function() {
                    $.ajax({
                    url: $(elem).attr('data-url'),
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {uuid: uuid, status: status},
                    beforeSend: function () {
                        show_loader();
                       // $('.loading-spinner').toggleClass('active');
                    },
                    success: function (response) {
                        //$('.loading-spinner').toggleClass('active');
                         hide_loader(); 
                        ischange = false;
                        if(response.status_code == 200){
                            displaySuccessMessage(response.message);
                            let previousPage = $('#dataTable').DataTable().page.info().page;
                            $('#dataTable').DataTable().ajax.reload();
                            $('#dataTable').DataTable().page(previousPage).draw(false);
                            if($(elem).attr('data-type') == 'customer'){
                                let previousPage = $('#customerDataTable').DataTable().page.info().page;
                                $('#customerDataTable').DataTable().ajax.reload();
                                $('#customerDataTable').DataTable().page(previousPage).draw(false);
                            }
                        }else{
                            displayErrorMessage(response.message);
                        }
                        
                    },
                    error: function (xhr, err) {
                         hide_loader(); 
                       // $('.loading-spinner').toggleClass('active');
                        isUserDelete = false;
                        if(typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0)
                        {
                            if (typeof xhr.responseJSON.errors != "undefined") {
                                commonFormErrorShow(xhr, err);
                            }else{
                                displayErrorMessage(xhr.responseJSON.message);
                            }
                        }
                        else
                        {
                            displayErrorMessage(xhr.responseJSON.errors);
                        }
                    }
                });

                    }
                },
                No:  {
                    
                    text: 'No',
                    btnClass: 'btn btn-default',
                    
                }

            }
        });
    
}
/***loader js */
function show_loader()
{
	 $("#overlay").show();
}
function hide_loader()
{
	 $("#overlay").hide();
}
/***only number allow */
var invalidChars = [
    "-",
    "+",
    "e",
]; 
$("body").on("keydown", ".numbersOnly", function (e) {
    if (invalidChars.includes(e.key)) {
    e.preventDefault();
    }
});
$(".numberOrDecimalOnly").on("input", function(evt) {
    var self = $(this);
   self.val(self.val().replace(/[^0-9\.]/g, ''));
   if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });
$(".numbersOnly").keypress(function(event) {
    
  // Backspace, tab, enter, end, home, left, right
  // We don't support the del key in Opera because del == . == 46.
  var controlKeys = [8, 9, 13, 35, 36, 37, 39];
  // IE doesn't support indexOf
  var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
  // Some browsers just don't raise events for control keys. Easy.
  // e.g. Safari backspace.
  if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
      (49 <= event.which && event.which <= 57) || // Always 1 through 9
      (48 == event.which && $(this).attr("value")) || // No 0 first digit
      isControlKey) { // Opera assigns values for control keys.
    return;
  } else {
    event.preventDefault();
  }
});

function contactDetailsLoad() {
    // Initialization of mobile number flag and plugin
    $(".contact_number").intlTelInput({
        initialCountry: "us",
    });

    $('.contact_number').each(function (index, element) {
        // Set country based on previous saved data into the database 
        $(element).intlTelInput("setCountry", selectedcountrycodes[index] != null ? selectedcountrycodes[index] : 'us');
        // Set hidden input field with country iso2 code value
        $($('.country_code')[index]).val($(element).intlTelInput("getSelectedCountryData").iso2);
        //Added change event of country for set country code into hidden
        $(element).on("countrychange", function () {
            $($('.country_code')[index]).val($(element).intlTelInput("getSelectedCountryData").iso2);
        });
    });
}

function contactDetailsAddAfter() {
    // Initialization of mobile number flag and plugin of last added
    $(".contact_number").last().intlTelInput({
        initialCountry: "us",
    });

    // Set hidden input field with country iso2 code value of last added
    $('.country_code').last().val($('.contact_number').last().intlTelInput("getSelectedCountryData").iso2);
    //Added change event of country for set country code into hidden of last added
    $('.contact_number').last().on("countrychange", function () {
        $('.country_code').last().val($('.contact_number').last().intlTelInput("getSelectedCountryData").iso2);
    });
}

function convertUTCToLocal(){
    $( ".dateConvert" ).each(function() {
        $(this).text(moment.utc($(this).text()).local().format('DD/MM/YYYY hh:mm:ss A'));
    });
}

function exportBtnClick() {
    $('.export_data').on('click', function () {
        var btnClass = $(this).attr('id')
            ? '.buttons-' + $(this).attr('id')
            : null;
        if (btnClass) $('#dataTable').DataTable().button(btnClass).trigger();
    });
}

function exportData(e, dt, button, config) {
    var self = this;
    dt.one('preXhr', function (e, s, data) {
        data.length = self.page.info().recordsTotal;
        dt.one('preDraw', function (e, settings) {
            if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config)
            } else if(button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config)
            }
        });
    });
    dt.ajax.reload();
}

function getButtonState(){
    localStorage.setItem("buttonState", $('#dataTable').DataTable().page.info().page);
}

function setButtonState(){
    var buttonState = localStorage.getItem("buttonState");
    if (buttonState !== null) {
        $('#dataTable').DataTable().page(parseInt(buttonState)).draw(false);
    }
}

function deleteButtonState(){
    localStorage.removeItem('buttonState');
}