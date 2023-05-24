function itemDataTable(param = {}) {

    var isVisibleColumnsCustomer = isCustomerDisplay() == true ? true : false;
    var dataTable = $('#dataTable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        fixedHeader: true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollY: 330,
        scrollX: true,
        // stateSave: true,
        order: [[3,'desc']],
        columns: [
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: 'getCustomer.name', name: 'getCustomer.name', orderable: false, searchable: false,visible: isVisibleColumnsCustomer },
            { data: 'amazon_order_id', name: 'amazon_order_id', orderable: true, searchable: true },
            { data: 'order_date', name: 'order_date', orderable: true, searchable: true },
            { data: 'order_status', name: 'order_status', orderable: true, searchable: true },
            { data: 'request_details', name: 'request_details', orderable: false, searchable: false },
        ],
        columnDefs: [
            {
                "targets": 4,
                "className": "text-center"
            }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                title: 'Requests'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                title: 'Requests'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Requests'
            }
        ]
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();

    exportBtnClick();
});
function isCustomerDisplay() {     
    if(login_user_role == company_role && login_customer_type != individual_brand) return true;
    if( login_customer_type== individual_brand) return false;
    return true;
} 
/**select customer to get data of this customer */
$('body').on('change', '#select_customer', function () {
   var userid = $(this).val();
    var param = {};
    param.order_ids = $('#amazon_order_id').val();
    param.order_date_range = $('#order_date_range').val();
    param.requested_date_range = $('#requested_date_range').val();
    param.order_status = JSON.stringify($('#order_status').select2("data"));
    param.request_status = $('#request_status').find(":selected").val();
    param.order_date_range === '' ? delete param.order_date_range : '';
    param.requested_date_range === '' ? delete param.requested_date_range : '';
    param.order_ids === '' ? delete param.order_ids : '';
    param.order_status === '[]' ? delete param.order_status : '';
    param.request_status === 'Request Status' ? delete param.request_status : '';
	param.userid = userid;
    itemDataTable(param);
   
    $('#dataTable').DataTable().destroy();
    itemDataTable(param);
});