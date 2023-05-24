function itemDataTable(param = {}) {
    
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
        order: [[7, "desc"]],
        fixedHeader: true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        // stateSave: true,
        scrollX: true,
        scrollY: 330,
        columns: [
            { data: "DT_RowIndex", name: "id", orderable: false },
            { data: "plan_type", name: "plan_type", orderable: true, searchable: true },
            { data: "name", name: "name", orderable: true, searchable: true },
            {
                data: "amount",
                name: "amount",
                orderable: true,
                searchable: true,
            },
            {
                data: "interval",
                name: "interval",
                orderable: true,
                searchable: true,
            },
            {
                data: "company_count",
                name: "company_count",
                orderable: false,
                searchable: false,
            },
            {
                data: "status",
                name: "status",
                orderable: false,
                searchable: false,
            },
            {
                data: "created_at",
                name: "created_at",
                orderable: true,
                searchable: true,
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        buttons: [
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                "action": exportData,
                title: 'Subscription Plans'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                "action": exportData,
                title: 'Subscription Plans'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                },
                "action": exportData,
                orientation: 'landscape',
                title: 'Subscription Plans'
            }
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
