function itemDataTable(param = {}) {
    
    var dataTable =  $('#dataTable').DataTable({     
        destroy: true,          
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[11,'desc']],
        fixedHeader : true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollX: true,
        scrollY: 330,
        columns: [
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: 'name', name: 'name', orderable: true, searchable: true },
            { data: 'customer_type', name: 'customer_type', orderable: true, searchable: true  },
            { data: 'is_trial', name: 'is_trial', orderable: false, searchable: false  },
            { data: 'getPlan.name', name: 'getPlan.name', orderable: true, searchable: true  },
            { data: 'over_sales_amount', name: 'over_sales_amount', orderable: true, searchable: true },
            { data: 'status', name: 'status', orderable: false, searchable: false  },
            { data: 'next_billing_date', name: 'next_billing_date', orderable: true, searchable: true  },
            { data: 'brands', name: 'brands', orderable: false, searchable: false  },
            { data: 'stores', name: 'stores', orderable: false, searchable: false  },
            { data: 'emails', name: 'emails', orderable: false, searchable: false },
            { data: 'requests', name: 'requests', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false,   searchable: false  },
        ],
        columnDefs: [
            {
                "targets": [5,6,7,8,9,10],
                "className": "text-center"
            }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                "action": exportData,
                title: 'Customers'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                "action": exportData,
                title: 'Customers'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Customers',
                pageSize : 'LEGAL',
            },
        ],
        "initComplete": function(settings, json){
            setButtonState();
            deleteButtonState();
        }
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();

    exportBtnClick();
});

//company customers list
$('body').on('click', '.openaddmodal', function () {
    var id = $(this).data('id');
    var encyptid    = $(this).data('encyptid');
    $("#companyid").val(id);
    $('#basicModal').modal({backdrop: 'static', keyboard: false}); 
    $('.modal-title').text('Customers Of '+$(this).data('cname'));
    $(".customer_create_url").attr('href', customer_create_route+'?id='+encyptid);
    if($(this).data('cus_count')== 0){
        $("#export_records").prop('disabled', true);
    }else{
        $("#export_records").prop('disabled', false);
    }
    $('#customerDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: customer_route,
            data: {
                companyid:id,
            }, 
        },
        order: [[9,'desc']],
        fixedHeader : true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        stateSave: false,
        scrollX: true,
        scrollY: 300,
        columns: [
            {data: 'check', name:'check', orderable: false, searchable: false },
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: 'status', name: 'status', orderable: false, searchable: false  },
            { data: 'stores', name: 'stores', orderable: false, searchable: false  },
            { data: 'emails', name: 'emails', orderable: false, searchable: false  },
            { data: 'email_cadence', name: 'email_cadence', orderable: false, searchable: false  },
            { data: "email", name: "email", orderable: true, searchable: true },
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
                "targets": [1,3,4,5,6,7,8],
                "className": "text-center"
            },
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                "action": exportData,
                title: 'Customers Of '+$(this).data('cname')
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                "action": exportData,
                title: 'Customers Of '+$(this).data('cname')
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Customers Of '+$(this).data('cname'),
                pageSize : 'LEGAL'
            },
        ]
    });
    $('.export_data').off('click');
    $('.export_data').on('click', function () {
        var btnClass = $(this).attr('id')
            ? '.buttons-' + $(this).attr('id')
            : null;
        if (btnClass) $('#customerDataTable').DataTable().button(btnClass).trigger();
    });

    setTimeout(function () {
        setCustomerIds();
        $('.all_user_checkbox').on('change',  function () {            
            setCustomerIds(); 
        });
    }, 1000);
});

$('#basicModal').on('hidden.bs.modal', function () {
  $('#customerDataTable').DataTable().destroy();
  exportBtnClick();
})
//export excel
function setCustomerIds(){
    var customer_ids = [];        
    $(".user_checkbox:checked").each(function() {
        customer_ids.push($(this).val());
    });
    $("#customer_ids").val(customer_ids);
}
$('#customerDataTable').on('change', 'tbody input.user_checkbox', function () {
     setCustomerIds();
});  
//all_user_checkbox
$('#customerDataTable').on('change', 'thead input.all_user_checkbox', function () {
   
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