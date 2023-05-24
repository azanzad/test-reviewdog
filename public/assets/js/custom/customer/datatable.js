function itemDataTable(param = {}) {
    
    var dataTable =  $('#dataTable').DataTable({         
        destroy: true,      
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[7,'desc']],
        fixedHeader : true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollY: 330,
        scrollX: true,
        // stateSave: true,
        columns: [
           {data: 'check', name:'check', orderable: false, searchable: false },
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "email", name: "email", orderable: true, searchable: true },
            { data: 'status', name: 'status', orderable: false, searchable: false  },
            { data: 'stores', name: 'stores', orderable: false, searchable: false },
            { data: 'requests', name: 'requests', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false,   searchable: false  },
        ],
        columnDefs: [
            {
                // For Checkboxes 
                targets: 0,
                orderable: false,
                searchable: false,
                responsivePriority: 3,
                checkboxes: true,
                render: function (data, type, full, meta) {
                    return data;
                // return '<input type="checkbox" value="" class="dt-checkboxes user_checkbox form-check-input" value="">';
                },
                checkboxes: {
                    selectAllRender: '<input type="checkbox" class="selectAll all_user_checkbox form-check-input">'
                }
            },
            {
                "targets": [4,5,6],
                "className": "text-center"
            },
        ],
    });

    return dataTable;
}

$(document).ready(function () {
    itemDataTable();
    setTimeout(function () {
         setCustomerIds();
         //all_user_checkbox

        $('.all_user_checkbox').on('change',  function () {            
            setCustomerIds(); 
        });
    }, 1000);
  
});
function setCustomerIds(){
    var customer_ids = [];        
    $(".user_checkbox:checked").each(function() {
        customer_ids.push($(this).val());
    });
    $("#customer_ids").val(customer_ids);
    console.log(customer_ids)
}
//single user_checkbox

$('#dataTable').on('change', 'tbody input.user_checkbox', function () {
    setCustomerIds();
     
});  

//export excel form submit


$('#formCustomerExport').bind('submit', function (e) {
    $('.btnexport').prop('disabled', true);
    $('.loading-spinner').toggleClass('active');
    setTimeout(function () {
        $('.loading-spinner').toggleClass('active');
        // Reactivate the button if the form was not submitted
        $('.btnexport').prop('disabled', false);
    }, 1000);
    
});
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
});
/**send bulk store link to customer email */
$('body').on('click', '.send_bulk_store_link', function (e) {
    var customer_ids = $("#customer_ids").val();    
    var url = $(this).attr('data-url');
    console.log(customer_ids);
    if( $('.user_checkbox').is(':checked')){
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
        
    }else{
        toastr.error('Please select at least one customer!');
    }
    
});