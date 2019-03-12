@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css">
<main class="mn-inner">
    <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1 ">
                                <div class="col l6 m6 s12 hide-on-med-and-down">
                                    <div class=" bred-c-holder-1" >
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users.index')}}" style="font-size:25px;">{{trans('dashboard.sidebar.list.orders.items')}}</a>
                                    </div>
                                </div> 
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            <ul class="tabs z-depth-1">
                                                <li class="tab"><a data-type="services" class="type-selector-1 @if($type == 'services') active @endif">{{trans('main.breadcrumbs.services')}}</a></li>
                                                <li class="tab"><a data-type="mobile_services" class="type-selector-1 @if($type == 'mobile_services') active @endif">{{trans('main.breadcrumbs.mobile_services')}}</a></li>
                                                <li class="tab"><a data-type="stbs" class="type-selector-1 @if($type == 'stbs') active @endif">{{trans('main.breadcrumbs.stbs')}}</a></li>
                                                <li class="tab"><a data-type="goods" class="type-selector-1 @if($type == 'goods') active @endif">{{trans('main.breadcrumbs.goods')}}</a></li>
                                            </ul>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="row no-m-t no-m-b">
            <div class="col s12 m12 l12">
                <div class="card invoices-card">
                    <div class="card-content">
                        <div class="filters-container">
                            <form method="POST" id="search-form" class="form-inline" role="form" action="">
                                <div class="load-filter-container row deep-purple lighten-5">
                                    {!! $filter !!}
                                </div>
                            </form>
                        </div>
                        <div class="table-container">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
{{-- <div class="page-footer">
    <div class="footer-grid container">
        <div class="footer-r white">&nbsp;</div>
        <div class="footer-grid-r white">
            <a class="footer-text" href="{{route('users-all')}}">
                <i class="material-icons arrow-r">arrow_forward</i>
                <span class="direction">შემდეგი</span>
            </a>
        </div>
    </div>
</div> --}}


<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>


<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script type="text/javascript">


        /*$('.dropdown-button').dropdown({
          inDuration: 300,
          outDuration: 225,
          constrainWidth: false, // Does not change width of dropdown to that of the activator
          hover: true, // Activate on hover
          gutter: 0, // Spacing from edge
          belowOrigin: false, // Displays dropdown below the button
          alignment: 'left', // Displays dropdown with edge aligned to the left of button
          stopPropagation: false // Stops event propagation
        }
      );*/

    

    $('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });
    var picker = datepicker.pickadate('picker');


    (function(window,$){
        $('.dropdown-button').dropdown();
        var lang_ind = null;
        var status = 0;
        var yearly = 0;
        var w_service = 0;
        var payed_status = 0;
        var date_picker_1 = {
            'date_start_from':null,
            'date_start_to':null,
            'date_end_from':null,
            'date_end_to':null
        }
        var search_input = null;
        var type = "{{$type}}";
        var multiroom_only = 0;
        var exportOptionsColumns = [];

        switch(type) {
            case 'services': exportOptionsColumns = [1, 2]; break;
            case 'mobile_services': exportOptionsColumns = [2, 3]; break;
            case 'stbs': exportOptionsColumns = [4, 5]; break;
            case 'goods': exportOptionsColumns = [6, 7]; break;
        }

        var dataTableOptions = {
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: 'orders_products',
                //type: 'POST',
                data: function (d) {
                    //d.activated = activity_value;
                    d.status = status;
                    d.payed_status = payed_status;
                    d.w_service = w_service;
                    d.date = date_picker_1;
                    d.type = type;
                    d.search_input = search_input;
                    d.yearly = yearly;
                    d.multiroom_only = multiroom_only;
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                },
            },
            autoWidth: false,
            responsive: true,
            "language": {
                searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
                sProcessing: "{{trans('main.filter.processing')}}",
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
                {"name":"color","data":"color","title":"","orderable":false,"searchable":false, "width":"10px"},
                {"name":"id","data":"id","title":"{{trans('main.orders_products.table.header.id')}}","orderable":true,"searchable":false, "visible":false},
                {"name":"code", "data": 'code', "title":"{{trans('main.orders_products.table.header.code')}}","orderable":true,"searchable":true,
                    render: function ( data, type, row ) {
                        if (data) { return '<b>' + data + '</b>'; } else return '';
                    }
                },
                {"name":"name","data":"name","title":"{{trans('main.orders_products.table.header.name')}}","orderable":true,"searchable":true, "visible": true},
                {"name":"mob_account_id","data":"mob_account_id","title":"{{trans('main.orders_products.table.header.mob_account_id')}}","orderable":true,"searchable":true, 
                    render: function ( data, type, row ) {
                        if (data) { return '<b>' + data + '</b>'; } else return '';
                    }
                },
                {"name":"service_password","data":"service_password","title":"{{trans('main.orders_products.table.header.service_password')}}","orderable":true,"searchable":true,
                    render: function ( data, type, row ) {
                        if (data) { return '<b>' + data + '</b>'; } else return '';
                    }
                },
                {"name":"date_start","data":"date_start","title":"{{trans('main.orders_products.table.header.date_start')}}","orderable":true,"searchable":false,
                    render: function ( data, type, row ) {
                        if (data) { return '<span class="date-holder-1">' + data + '</span>'; } else return '';
                    }
                },
                {"name":"date_end","data":"date_end","title":"{{trans('main.orders_products.table.header.date_end')}}","orderable":true,"searchable":false},
                {"name":"user", "data": 'user', "title":"{{trans('main.orders_products.table.header.user')}}","orderable":false,"searchable":false},
                {"name":"user_name","data":"user_name","title":"{{trans('main.orders_products.table.header.user_name')}}","orderable":false,"searchable":false},
                {"name":"mac","data":"mac","title":"{{trans('main.orders_products.table.header.mac')}}","orderable":true,"searchable":true,
                    render: function ( data, type, row ) {
                        if (data) { return '<div  class="deep-purple lighten-5 wrapper-custom-2">' + data + '</div>'; } else return '';
                    }
                },
                {"name":"service","data":"service","title":"{{trans('main.orders_products.table.header.service')}}","orderable":false,"searchable":false},
                {"name":"status","data":"status","title":"{{trans('main.orders_products.table.header.status')}}","orderable":false,"searchable":false},
                {"name":"comment","data":"comment","title":"{{trans('main.orders_products.table.header.comment')}}","orderable":true,"searchable":true,'width':'15%'},
                {"name":"pin_code","data":"pin_code","title":"{{trans('main.orders_products.table.header.pin_code')}}","orderable":false,"searchable":false,'width':'170px'},
                {"name":"order_id","data":"order","title":"{{trans('main.orders_products.table.header.order')}}","orderable":true,"searchable":true},
                {"name":"added_type","data":"added_type","title":"{{trans('main.orders_products.table.header.added_type')}}","orderable":false,"searchable":false},
                // {"name":"user_phone","data":"user_phone","title":"user phone","orderable":false,"searchable":false},
                // {"name":"previous_service","data":"previous_service","title":"previous service","orderable":false,"searchable":false},
            ],
            "dom":"<'row'<'col l12 m12 s12'lBr>>" +
                "<'row'<'col l12 m12 s12'ip>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons":[
                {
                    extend: 'csvHtml5',
                    //text: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'excelHtml5',
                    //text: 'excel',
                    exportOptions: {
                        columns: ':visible',
                    }
                },
                {
                    extend: 'pdfHtml5',
                    //text: 'excel',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                "reset","reload",
                {extend: 'selectAll', text: '<i class="material-icons">playlist_add_check</i> {{trans('main.misc.select_all')}}'},
                {extend: 'selectNone', text: '<i class="material-icons">remove_circle_outline</i> {{trans('main.misc.select_all_cancel')}}'},
                {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                    action: function ( e, dt, node, config ) {
                        var rows = dt.rows( { selected: true } );
                        var dataArr = rows.data().toArray();
                        var rowCount = rows.count();
                        dataArr = $.map(dataArr, function(n,i){
                           return [ n.id ];
                        });

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

                                url = "{{route('orders_products.delete')}}";
                                return new Promise(function(resolve, reject) {
                                    delete_items(url, dataArr, rowCount, [window.LaravelDataTables["dataTableBuilder"]], function (response) {

                                    });
                                });
                            }
                        }]).then(function() {
                            
                        });

                        // swal({
                        //     title: "{{trans('main.misc.delete_title')}}",
                        //     //text: "You will not be able to recover these discounts!",
                        //     type: "warning",
                        //     showCancelButton: true,
                        //     confirmButtonColor: "#DD6B55",
                        //     confirmButtonText: "{{trans('main.misc.delete')}}",
                        //     cancelButtonText: "{{trans('main.misc.cancel')}}",
                        // }).then(function () {
                        //     url = "{{route('orders_products.delete')}}";
                        //     delete_orders_products(url, dataArr, rowCount);
                        // });
                    }
                },
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [0, 1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 15] }
            ],
            "order": [[ 1, "desc" ]],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'iDisplayLength': 25,
            

        };

        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable(dataTableOptions);

        window.onresize = function() {
            window.LaravelDataTables["dataTableBuilder"].columns.adjust().responsive.recalc();
        }  
        //window.LaravelDataTables["dataTableBuilder"].rows( { selected: true } ).data();
        //
        $(document).off('input', "input.expand-search") // Unbind previous default bindings
        .on("input", "input.expand-search", function(e) { // Bind our desired behavior
            // If the length is 3 or more characters, or the user pressed ENTER, search
            if(this.value.length >= 1 || e.keyCode == 13) {
                // Call the API search function
                search_input = this.value;
            }
            // Ensure we clear the search if they backspace far enough
            if(this.value == "") {
                search_input = "";
            }
            window.LaravelDataTables["dataTableBuilder"].draw();
            return;
        });

        //$('.dataTable').addClass('responsive-table');
        $('.dataTables_length select').addClass('browser-default');
        $('.dataTables_filter input[type=search]').addClass('expand-search');
        $('.card-options').fadeOut(0);

        window.LaravelDataTables["dataTableBuilder"].on( 'processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.card-content');
            var tables_wrapper = $(this).closest('.dataTables_wrapper');
            if (processing) {
                tables_wrapper.addClass('processing-table');
                if (wrapper.find('.preloader-full').length == 0) {
                    $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                }

                if (wrapper.find('.ocean').length == 0) { 
                    $(wave_animation).hide().prependTo(tables_wrapper).fadeIn(200);
                }
                
            } else {
               tables_wrapper.removeClass('processing-table');
               wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );

        $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('user-changestatus') }}";
            change_user_status($(this), url);
        });

        $(document).off('click', '.show-order-btn').on('click', '.show-order-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('orders-show', 0) }}";
            var container = $('.mn-inner');
            show_order($(this), container, url);
        });

        $(document).on('change', '#status-select',function(e) {
            status = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#yearly-select',function(e) {
            yearly = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#w_service-select',function(e) {
            w_service = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#payed_status-select',function(e) {
            payed_status = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '.date_picker-custom-1',function(e) {
            id = $(this).attr('id');
            date_picker_1[id] = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#show-multiroom-only',function(e) {
            if ($(this).is(':checked')) {
                multiroom_only = 1;
            } else multiroom_only = 0;
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('focus blur', '.expand-search', toggleFocus);

        

/*        $(document).off('click', '.show-user-btn').on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            show_user($(this), container, url);
        });*/

        $(document).off('click', '.show-user-btn').on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var data = {'user_id':$(this).data('id')}
            var url = "{{ route('users-show', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).off('click', '.save-pin').on('click', '.save-pin',function(e) {
            e.preventDefault();
            var url = "{{ route('orders_products.pin-update') }}";
            var id = $(this).data('id');
            var pin = $(this).closest('.row').find('input.pin_input-inline-1').val();
            data = {'id': id, 'pin': pin};
            update_glob(url, data);
        });

        var toggleFocus = function(e){
            if( e.type == 'focusin' )
                $(this).addClass('open-search');
            else 
                $(this).removeClass('open-search');
        }

        var load_filter = function(type){
            status = 0;
            w_service = 0;
            payed_status = 0;
            search_input = '';
            multiroom_only = 0;
            date_picker_1 = {
                'date_start_from':null,
                'date_start_to':null,
                'date_end_from':null,
                'date_end_to':null
            };
            var url = "{{route('orders_products.load_filter')}}";
            var curr_url = "{{route('orders_products.index')}}";
            op_load_filter(url, type, $('.load-filter-container'), window.LaravelDataTables["dataTableBuilder"], dataTableOptions);
            window.history.pushState("", "Title", curr_url + '?type=' + type);
        }

        $(document).ready(function(e) {
            var type = "{{$type}}";
            load_filter(type);
        });

        $(document).on('click', '.type-selector-1',function(e) {
            e.preventDefault();
            type = $(this).data('type');
            switch(type) {
                case 'services': exportOptionsColumns = [1, 2]; break;
                case 'mobile_services': exportOptionsColumns = [2, 3]; break;
                case 'stbs': exportOptionsColumns = [4, 5]; break;
                case 'goods': exportOptionsColumns = [6, 7]; break;
            }
            delay(function(){
                load_filter(type);
            }, 100 );
        });

        $(".countdown-wrapper-1").countdown($(this).data('countdown'), function(event) {
            $(this).append(
              ' (' + event.strftime('%D days %H:%M:%S') + ')'
            );
          });

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

    })(window,jQuery);
</script>
@stop
