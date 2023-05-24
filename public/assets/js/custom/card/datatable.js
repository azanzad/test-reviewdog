function itemDataTable(param = {}) {
    
    var dataTable =  $('#dataTable').DataTable({               
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[4,'desc']],
        fixedHeader : true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        scrollY: 330,
        scrollX: true,
        // stateSave: true,
        columns: [
            {data: 'DT_RowIndex', name: 'id',"orderable": false},
            { data: 'brand', name: 'brand', orderable: true, searchable: true },
            { data: 'cardholder_name', name: 'cardholder_name', orderable: true, searchable: true },
            { data: 'is_primary', name: 'is_primary', orderable: true, searchable: true },
            { data: 'created_at', name: 'created_at', orderable: true, searchable: true },
            { data: 'action', name: 'action', orderable: false,   searchable: false  },
        ]
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();
});
