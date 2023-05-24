$('body').on('click', '.send_store_link', function (e) {
    var id = $(this).attr('data-id');
    var url = $(this).attr('data-url');
   $.confirm({
            title: "Confirmation!",
            content: "Are you sure you want to send store integration link?",
            buttons: {
                sayMyName: {
                    text: 'Yes',
                    btnClass: 'btn  btn-success',
                    action: function() {
                    $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {id: id},
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
                            if($(this).attr('data-type') == 'customer'){
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
});
/**send bulk store link to customer email */
$('body').on('click', '.send_bulk_store_link', function (e) {
    var customer_ids = $("#customer_ids").val();    
    var url = $(this).attr('data-url');
    console.log(customer_ids);
   if( $('.user_checkbox').is(':checked') ){
       $.confirm({
            title: "Confirmation!",
            content: "Are you sure you want to send store integration link?",
            buttons: {
                sayMyName: {
                    text: 'Yes',
                    btnClass: 'btn  btn-success',
                    action: function() {
                    $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {ids: customer_ids},
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
                            if($(this).attr('data-type') == 'customer'){
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
    }else{
         toastr.error('Please select at least one customer!');
    }
    
});