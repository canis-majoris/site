<div>
	<table id="cartu_transactions-datatable" class="table no-footer responsive-table custom-datatable-1">
    </table>
</div>

<script type="text/javascript">
    var cartu_status_value = 'any';
    var cartu_provider = 'cartu';
    var cartu_custom1 = 'any';
    var cartu_date_picker_1 = {
        'date_start':null,
        'date_end':null,
    };

    var cartu_active_service_type_id = null;
    var search_input = '';

    $(document).ready(function(e) {

        cartu_transactions_dataTable = $("#cartu_transactions-datatable").DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('transactions.provider.cartu')}}",
                //type: 'POST',
                data: function (d) {
                    d.status = cartu_status_value;
                    d.provider = cartu_provider;
                    d.search_input = search_input;
                    d.date = cartu_date_picker_1;
                },
            },
            "responsive": true,
            "language": {
                searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
                //sProcessing: loading_overlay_block_2,
                sInfo: "{{trans('main.filter.show_total')}}",
                sSearch: '',
                sLengthMenu: "{{trans('main.filter.record_count_show')}}" + ' _MENU_',
                sLength: 'dataTables_length',
                oPaginate: {
                    sFirst: '<i class="material-icons">chevron_left</i>',
                    sPrevious: '<i class="material-icons">chevron_left</i>',
                    sNext: '<i class="material-icons">chevron_right</i>',
                    sLast: '<i class="material-icons">chevron_right</i>'
                }
            },
            "columns":[
                {"name":"id","data":"id","title":"{{trans('main.transactions.table.header.id')}}","orderable":true,"searchable":true, "visible":false},
                {"name":"merchant_id", "data": 'merchant_id', "title":"{{trans('main.transactions.table.header.merchant_id')}}","orderable":true,"searchable":true, "visible":false},
                {"name":"date","data":"date","title":"{{trans('main.transactions.table.header.date')}}","orderable":true,"searchable":true},
                {"name":"m_amt", "data": 'm_amt', "title":"{{trans('main.transactions.table.header.m_amt')}}","orderable":true,"searchable":true},
                //{"name":"m_currency","data":"m_currency","title":"{{trans('main.transactions.table.header.m_currency')}}","orderable":true,"searchable":true},
                //{"name":"m_desc","data":"m_desc","title":"{{trans('main.transactions.table.header.m_desc')}}","orderable":true,"searchable":true},
                {"name":"m_ip","data":"m_ip","title":"{{trans('main.transactions.table.header.m_ip')}}","orderable":true,"searchable":true},
                {"name":"result","data":"result","title":"{{trans('main.transactions.table.header.result')}}","orderable":true,"searchable":true},
                
            ],
            "dom":"<'row'<'col l12 m12 s12'lBr>>" +
                "<'row'<'col l12 m12 s12'ip>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons":["csv",
                {
                    extend: 'excel',
                    //text: 'excel',
                    exportOptions: {
                        columns: [ 0, 2, 3, 4]
                    }
                },
                "print","reset","reload",
                // {extend: 'selectAll', text: '<i class="material-icons">playlist_add_check</i> {{trans('main.misc.select_all')}}'},
                // {extend: 'selectNone', text: '<i class="material-icons">remove_circle_outline</i> {{trans('main.misc.select_all_cancel')}}'},
                // {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                //     action: function ( e, dt, node, config ) {
                //         var rows = dt.rows( { selected: true } );
                //         var dataArr = rows.data().toArray();
                //         var rowCount = rows.count();
                //         dataArr = $.map(dataArr, function(n,i){
                //            return [ n.id ];
                //         });

                //         swal({
                //             title: "{{trans('main.misc.delete_title')}}",
                //             //text: "You will not be able to recover these discounts!",
                //             type: "warning",
                //             showCancelButton: true,
                //             confirmButtonColor: "#DD6B55",
                //             confirmButtonText: "{{trans('main.misc.delete')}}",
                //             cancelButtonText: "{{trans('main.misc.cancel')}}",
                //         }).then(function () {
                //         });
                //     }
                // },
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [3,4,5] }
            ],
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'iDisplayLength': 25,

        });
    });

    $(document).off('input', "input.expand-search") // Unbind previous default bindings
    .on("input", "input.expand-search", function(e) { // Bind our desired behavior
        // If the length is 3 or more characters, or the user pressed ENTER, search

        if(this.value.length >= 1 || e.keyCode == 13) {
            // Call the API search function
            search_input = this.value;
            cartu_transactions_dataTable.draw();
        }
        // Ensure we clear the search if they backspace far enough
        if(this.value == "") {
            search_input = "";
            cartu_transactions_dataTable.draw();
        }
        return;
    });

    $(document).on('change', '.date_picker-custom-1',function(e) {
        id = $(this).attr('id');
        cartu_date_picker_1[id] = $(this).val();
        delay(function(){
            cartu_transactions_dataTable.draw();
        }, 200 );
        e.preventDefault();
    });

    $(document).on('change', '#transactions-status-select',function(e) {
        cartu_status_value = $(this).val();
        delay(function(){
            cartu_transactions_dataTable.draw();
        }, 200 );
        e.preventDefault();
    });

    var delay = (function(){
      var timer = 50;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();
</script>