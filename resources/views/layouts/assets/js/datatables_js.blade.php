<!-- Datatables -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/autofill/2.3.9/js/dataTables.autoFill.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script>
    function generateDataTable(options){
        const table = options.selector;
        table.DataTable().clear();
        table.DataTable().destroy();
        table.DataTable({
            serverSide: true,
            responsive: true,
            autoWidth: false,
            processing: true,
            searching: options.searching ?? true,
            pageLength: options.pageLength ?? 10,
            order: options.order,
            language: {
                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>'
            },
            ajax: {
                url: options.url,
                data: options.data,
                type: options.type ?? 'GET',
                beforeSend: function() {
                    table.hide();
                },
                complete: function () {
                    table.show();
                    table.DataTable().responsive.recalc();
                }
            },
            columns: options.columns,
            createdRow : function (row, data, index) {
                /** set width 24% last td in row (action-column) */
                $('td:last', row).addClass('action-column');
            },
            columnDefs: options.columnDefs,
            rowCallback: options.rowCallback,
            select: options.select,
            initComplete: options.initComplete,
            order: options.order,
            drawCallback: options.drawCallback,
            rowId: options.rowId
        })
    }
</script>
