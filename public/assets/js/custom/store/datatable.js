function itemDataTable(param = {}) {
    var isVisibleColumnsComppany = isParentCompanyDisplay() == true ? true : false;
    var isVisibleColumnsCustomer = isCustomerDisplay() == true ? true : false;
    
    var dataTable = $("#dataTable").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[6, "desc"]],
        fixedHeader: true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollY: 330,
        scrollX: true,
        // stateSave: true,
        columns: [
            { data: "DT_RowIndex", name: "id", orderable: false },
            { data: "store_name", name: "store_name", orderable: true, searchable: true },            
            { data: "getCustomer.getCompany.name", name: "getCustomer.getCompany.name", orderable: true, searchable: true,visible: isVisibleColumnsComppany},
            { data: "getCompany.name", name: "getCompany.name", orderable: false, searchable: false,visible: isVisibleColumnsCustomer },
            { data: "store_type", name: "store_type", orderable: true, searchable: true },            
            { data: "status", name: "status",   orderable: false, searchable: false,},
            { data: "created_at", name: "created_at",   orderable: true, searchable: true,},
            { data: "action", name: "action",   orderable: false, searchable: false,},
        ],
        columnDefs: [
            {
                "targets": 7,
                "className": "text-center"
            }
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                "action": exportData,
                title: 'Stores'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                "action": exportData,
                title: 'Stores'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Stores'
            }
        ]
    });
    return dataTable;
}
$(document).ready(function () {    
    itemDataTable();

    exportBtnClick();
});
function isParentCompanyDisplay() {     
    if(login_user_role == company_role) return false;
    if(login_customer_type == individual_brand) return false;
    return true;
}
function isCustomerDisplay() {     
    if(login_user_role == company_role && login_customer_type != individual_brand) return true;
    if( login_customer_type== individual_brand) return false;
    return true;
} 