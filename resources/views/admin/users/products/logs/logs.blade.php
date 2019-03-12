<table id="logs-datatable" class="display datatable-example">
    <thead>
        <tr>
            <th></th>
            <th>{{trans('main.logs.table.header.date')}}</th>
            <th>{{trans('main.logs.table.header.status')}}</th>
            <th>{{trans('main.logs.table.header.comment')}}</th>
            <th>{{trans('main.logs.table.header.author')}}</th>
            <th></th>
        </tr>
    </thead>
</table>
<script type="text/javascript">
    var logs_datatable = $('#logs-datatable').DataTable({
        "serverSide":true,
        "processing":true,
        "ajax": {
            url: "{{route('product-show-logs')}}",
            
            //type: 'POST',
            data: function (d) {
                //d.activated = '1';
                //d.title = $('input[name=title]').val();
                //d.subtitle = $('input[name=subtitle]').val();
                //d.description = $('input[name=description]').val();
                //d.tags = $('#tag_multiselect').val();
                d.product_id = {{$product->id}};
            },
        },
        //order: [[0, 'desc']],
        language: {
            searchPlaceholder: "{{trans('main.filter.search_bar')}}",
            sZeroRecords: "{{trans('main.filter.empty')}}",
            /*sProcessing: "{{trans('main.filter.processing')}}",*/
            processing: '<div class="preloader-overlay" style="">' +
                            '<div class="preloader-full" style="display: block;">' +
                                '<div class="progress">' +
                                    '<div class="indeterminate"></div>' + 
                                '</div>' +
                            '</div>' +
                        '</div>',
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
        "order": [[ 1, "desc" ]],
        "columns": [
            {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
            /*{"name":"color","data":"color","orderable":false,"searchable":false},*/
            {"name":"created_at","data":"created_at","orderable":true,"searchable":true},
            {"name":"status","data":"status","orderable":true,"searchable":true},
            {"name":"comment","data":"comment","orderable":true,"searchable":true,'width':'40%'},
            {"name":"owner","data":"owner","orderable":true,"searchable":true},
            {"name":"action","data":"action","orderable":false,"searchable":false},
        ],
        drawCallback: function(settings){
             var api = this.api();           
             // Initialize custom control
             initDataTableCtrl(api.table().container());
        }, 
        'responsive': true,
    });

    function initDataTableCtrl(container) {
        $('.multi-select', container).material_select();
    }

    $('.dataTables_length select').addClass('browser-default');

    $(document).on('processing.dt', '#logs-datatable', function ( e, settings, processing ) {
       // console.log(this)
        var wrapper = $(this).closest('.collapsible-body');
        var tables_wrapper = $(this).closest('.dataTables_wrapper');
        if (processing) {
            tables_wrapper.addClass('svg-blur-1');
            // if (wrapper.find('.preloader-full').length == 0) {
            //     $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
            // }
            
        } else {
            tables_wrapper.removeClass('svg-blur-1');
           // wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
        }
    } );

    $(document).on('click', '.log-delete-btn', function(e) {
        var _this = $(this);
        var _datatables_obj = {logs_datatable};

        swal.queue([{
            title: "{{trans('main.misc.delete_title')}}",
            //text: "You will not be able to recover these discounts!",
            type: "warning",
            showLoaderOnConfirm: true,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "{{trans('main.misc.delete')}}",
            cancelButtonText: "{{trans('main.misc.cancel')}}",
            confirmButtonClass: 'btn red',
            cancelButtonClass: 'btn btn-flat',
            buttonsStyling: false,
            preConfirm: () => {

                var url = "{{route('orders_products.logs.delete')}}";
                return new Promise(function(resolve, reject) {
                   // product_changestatus(_this, url, _datatables_obj);
                    delete_items(url, [_this.data('id')], null, _datatables_obj, function () {});

                    //swal("Deleted", "Log has been deleted.", "success");
                });
            }
        }]).then(function() {
            
        });

        e.preventDefault();
    });
</script>