function itemDataTable(param = {}) {
    
    var dataTable =  $('#dataTable').DataTable({               
        processing: true,
        serverSide: true,
        ajax: {
            url: route,
            data: param,
        },
        order: [[6,'desc']],
        fixedHeader : true,
        deferRender: true,
        paging: true,
        pageLength: 10,
        // stateSave: true,
        scrollX: true,
        columns: [
            { data: '' },
            { data: 'name', name: 'name', orderable: true, searchable: true },
            { data: 'email', name: 'email', orderable: true, searchable: true },
            { data: 'plan_price', name: 'plan_price', orderable: true, searchable: true  },            
            { data: 'is_trial', name: 'is_trial', orderable: true, searchable: false  },
            { data: 'trial_days', name: 'trial_days', orderable: true, searchable: false  },
           { data: 'created_at', name: 'created_at', orderable: true, searchable: true },
        ],
        columnDefs: [
        {
          className: 'control',
          orderable: false,
          targets: 0,
          responsivePriority: 3,
          render: function (data, type, full, meta) {
            return '';
          }
        },
    ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    return dataTable;
}
$(document).ready(function () {
    itemDataTable();
});
