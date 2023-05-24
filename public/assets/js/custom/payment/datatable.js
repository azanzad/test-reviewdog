function itemDataTable(param = {}) {
    var isVisibleColumnsCustomer = isCustomerDisplay() == true ? true : false;
    var dataTable = $("#dataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        beforeSend: function () {
            show_loader();
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
            { data: "company_name", name: "company_name", orderable: false, searchable: false,visible: isVisibleColumnsComppany },
            { data: "name", name: "name", orderable: true, searchable: true },
            { data: "stripe_price",name: "stripe_price",orderable: true,searchable: true,},
            {
                data: "stripe_status",
                name: "stripe_status",
                orderable: true,
                searchable: true,
            },
            {
                data: "trial_ends_at",
                name: "trial_ends_at",
                orderable: true,
                searchable: true,
            },
            
            {
                data: "created_at",
                name: "created_at",
                orderable: true,
                searchable: true,
            },
        ]
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();
});
function isCustomerDisplay() {     
    if(login_user_role == company_role) return false;
    if(login_user_role == customer_role) return false;
    return true;
}