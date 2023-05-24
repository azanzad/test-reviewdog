function itemDataTable(param = {}) {
    var isVisibleColumnsCustomer = isCustomerDisplay() == true ? true : false;
   
    var dataTable = $("#dataTable").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        beforeSend: function () {
            show_loader();
        },
        order: [[5, "desc"]],
        fixedHeader: true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollY: 330,
        scrollX: true,
        // stateSave: true,
        columns: [
            { data: "DT_RowIndex", name: "id", orderable: false },
            { data: "getCustomer.name", name: "getCustomer.name", orderable: false, searchable: false,visible: isVisibleColumnsCustomer  },
            { data: "getPlan.name", name: "getPlan.name", orderable: true, searchable: true },
            { data: "amount",name: "amount",orderable: true,searchable: true,},
            {
                data: "transaction_status",
                name: "transaction_status",
                orderable: false,
                searchable: false,
            },
            {
                data: "transaction_date",
                name: "transaction_date",
                orderable: true,
                searchable: true,
            },
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                title: 'Transactions'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                title: 'Transactions'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Transactions'
            }
        ],
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();

    exportBtnClick();
});
function isCustomerDisplay() {     
    if(login_user_role == company_role) return false;
    if(login_user_role == customer_role) return false;
    return true;
}