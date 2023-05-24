function itemDataTable(param = {}) {
    
    var dataTable =  $('#dataTable').DataTable({               
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[1,'desc']],
        deferRender: true,
        paging: true,
        pageLength: 10,
        // stateSave: true,
        scrollX: true,
        columns: [
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: 'contact_name', name: 'contact_name', orderable: true, searchable: true },
            { data: 'contact_title', name: 'contact_title', orderable: true, searchable: true },
            { data: 'email', name: 'email', orderable: true, searchable: true },           
            { data: 'contact_number', name: 'contact_number', orderable: true, searchable: true  },
        ],
        
    });
    return dataTable;
}

$(document).ready(function () {
    itemDataTable();

    $('body').on('click', '#cancelSubscription_btn', function (e) {
        e.preventDefault();
        
        var subscription_type = $("#subscription_type").val();
        $.confirm({
            title: "Are you sure?",
            content: `<div class="">You want `+ subscription_type +` this subscription?</div>`,
            buttons: {
                sayMyName: {
                    text: 'Yes',
                    btnClass: 'btn  btn-success',
                    action: function() {
                        $('.btnsubmit').prop('disabled', true);
                        $('.loading-spinner').toggleClass('active');
                        var formData = new FormData($("#cancelSubscription_fr")[0]);
                        $.ajax({
                            url: $("#cancelSubscription_fr").attr('action'),
                            data: formData,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            contentType: false,
                            cache: false,
                            processData: false,
                            beforeSend: function () {
                                show_loader();
                            },
                            success: function (data) {
                                hide_loader();  
                                $('.loading-spinner').toggleClass('active');
                                $('.btnsubmit').prop('disabled', false);
                                if (data.status_code == 200) {
                                    displaySuccessMessage(data.message);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1500);
                                }else{
                                    displayErrorMessage(data.message);
                                }
                            },
                            error: function (xhr, err) {
                                $('.loading-spinner').toggleClass('active');
                                hide_loader();
                                isAjaxCall = false;
                                $('.btnsubmit').prop('disabled', false);                      
                                if (typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0) {
                                    if (typeof xhr.responseJSON.errors != "undefined") {
                                        commonFormErrorShow(xhr, err);
                                    } else {
                                        displayErrorMessage(xhr.responseJSON.message);
                                    }
                                } else {
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
        
    });
});
