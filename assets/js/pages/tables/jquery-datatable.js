$(function () {
    $('.js-basic-example').DataTable({
        responsive: true
    });

    //Exportable table

    $('.js-exportable').DataTable({
        dom: 'lBfrtip',
        lengthMenu: [[10, 25, 50,100,150, -1], [10, 25, 50,100,150, "All"]],
        responsive: true,
        buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
        ],

        initComplete: function () {
            this.api().columns([0,4]).every( function () {
                var column = this;
                var select = $('<select style="width:100%" class="form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            });
            this.api().column([1]).every(function(colIdx){
                var column = this;
                var input = $( '<input style="width:100%" class="form-control" type="text" />')
                    .appendTo($(column.footer()).empty())
                    .on( 'keyup change', function () {
                        column.search( $(this).val(), true, false ).draw();
                } );
                
            });
            this.api().column([2]).every(function(colIdx){
                var column = this;
                var input = $( '<input style="width:100%" class="form-control" type="text" />')
                    .appendTo($(column.footer()).empty())
                    .on( 'keyup change', function () {
                        column.search( $(this).val(), true, false ).draw();
                } );
                
            });
            this.api().column([3]).every(function(colIdx){
                var column = this;
                var input = $( '<input style="width:100%" class="form-control datepicker" type="text" />')
                    .appendTo($(column.footer()).empty())
                    .on( 'keyup change', function () {
                        column.search( $(this).val(), true, false ).draw();
                } );
                
            });
        }
    });

    $('.js-exportable tfoot tr').appendTo('.js-exportable thead');

    
});