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
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l6 m6 s12">
                                    <div class=" bred-c-holder-1" >
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.orders')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 hide-on-med-and-down" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                    </div>
                                </div>
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
                        <form method="POST" id="search-form" class="form-inline" role="form" action="">
                            <div class="load-filter-container row deep-purple lighten-5">
                                <div class="wrapper-custom-1 col l4 m5 s12">
                                    <div class="datapicker-wrapper-1">
                                        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">{{trans('main.orders_products.table.filter.date.start')}}:</span></div>
                                        <div class="input-field col" style="">
                                            <input id="date_start" type="date" class="datepicker date_picker-custom-1" value="" name="date_start" style="margin: 0;">
                                            <label for="date_start" class="input-label-1">от</label>
                                        </div>
                                        <div class="input-field col" style="">
                                            <input id="date_end" type="date" class="datepicker date_picker-custom-1" value="" name="date_end" style="margin: 0;">
                                            <label for="date_end" class="input-label-1">до</label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="input-field col l3 m4 s12">
                                    <select multiple name="order_status[]" id="order-status-select">
                                        <option value="any" disabled selected>{{trans('main.misc.any')}}</option>
                                        @foreach($order_statuses as $o_s)
                                            <option value="{{$o_s->id}}">{{$o_s->name}}</option>
                                        @endforeach
                                    </select>
                                    <label>{{trans('main.orders_products.table.filter.status')}}</label>
                                </div>
                                <div class="input-field col l4 m3 s12" style="float: right">
                                    <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input" name="search_input">
                                    <div class="clear"></div>
                                    <!-- <label for="date_end" class="input-label-1">date end</label> -->
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!--<input type="submit" value="test"> -->
                        </form>
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
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
        //var activity_value = 2;
        var status_value = 0;
        var date_start = null;
        var date_end = null;
        var search_input = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'orders',
                //type: 'POST',
                data: function (d) {
                    //d.activated = activity_value;
                    d.status = status_value;
                    d.date_start = date_start;
                    d.date_end = date_end;
                    d.search_input = search_input;
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                },
            },
            "responsive": true,
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
				{"name":"id","data":"id","title":"","orderable":true,"searchable":false, "visible":false},
                {"name":"color","data":"color","title":"","orderable":false,"searchable":false, "width":"1px"},
                {"name":"code", "data": 'code', "title":"{{trans('main.orders.table.header.code')}}","orderable":true,"searchable":true},
				{"name":"date", "data": 'date', "title":"{{trans('main.orders.table.header.date')}}","orderable":true,"searchable":false},
                {"name":"orders_status_id","data":"status","title":"{{trans('main.orders.table.header.status')}}","orderable":true,"searchable":false},
				{"name":"user_id","data":"user","title":"{{trans('main.orders.table.header.user')}}","orderable":true,"searchable":false},
                {"name":"price","data":"price","title":"{{trans('main.orders.table.header.price')}}","orderable":true,"searchable":true},
                {"name":"globtotal","data":"globtotal","title":"globtotal","orderable":true,"searchable":true},
                /*{"name":"tracking","data":"tracking","title":"{{trans('main.orders.table.header.tracking')}}","orderable":true,"searchable":false},
                {"name":"ups","data":"ups","title":"{{trans('main.orders.table.header.ups')}}","orderable":true,"searchable":false},*/
                {"name":"transaction","data":"transaction","title":"{{trans('main.orders.table.header.transaction')}}","orderable":false,"searchable":false},
				{"name":"ups","data":"ups","title":"UPS","orderable":false,"searchable":false},
                {"name":"comment","data":"comment","title":"{{trans('main.orders.table.header.comment')}}","orderable":false,"searchable":false},
                /*{"name":"pay_type","data":"pay_type","title":"pay_type","orderable":false,"searchable":false},
                {"name":"orderid","data":"orderid","title":"orderid","orderable":false,"searchable":false},
                {"name":"transop1","data":"transop1","title":"transop1","orderable":false,"searchable":false},
                {"name":"transop2","data":"transop2","title":"transop2","orderable":false,"searchable":false},
				{"name":"transop3","data":"transop3","title":"transop3","orderable":false,"searchable":false},
                {"name":"action","data":"action","title":"","orderable":false,"searchable":false},*/
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
                        columns: [ 0, 2, 3, 4, 5, 6]
                    }
                },
                "print","reset","reload",
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

                                url = "{{route('orders-delete')}}";
                                return new Promise(function(resolve, reject) {
                                    delete_items(url, dataArr, rowCount, [window.LaravelDataTables["dataTableBuilder"]], function (response) {

                                    });
                                });
                            }
                        }]).then(function() {
                            
                        });
                    }
                },
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [0,1,3,4,6,7,8,9] }
            ],
            "order": [[ 3, "desc" ]],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
            

		});

        //window.LaravelDataTables["dataTableBuilder"].rows( { selected: true } ).data();

        $("input.expand-search").unbind() // Unbind previous default bindings
        .bind("input", function(e) { // Bind our desired behavior
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
                    $(loading_overlay_progress).hide().appendTo(wrapper).fadeIn(200);
                }

                if (wrapper.find('.ocean').length == 0) { 
                    $(wave_animation).hide().appendTo(tables_wrapper).fadeIn(200);
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

        $(document).on('click', '.show-order-btn',function(e) {
            $('.show-order-btn').unbind('click');
            e.preventDefault();
            var url = "{{ route('orders-show', 0) }}";
            var container = $('.mn-inner');
            show_order($(this), container, url);
        });

        $(document).on('change', '#order-status-select',function(e) {
            status_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_start',function(e) {
            date_start = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_end',function(e) {
            date_end = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('focus blur', '.expand-search', toggleFocus);

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

        $(document).off('click', '.show-user-btn').on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            $('.show-user-btn').off('click');
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            show_user($(this), container, url);
        });

        var toggleFocus = function(e){
            if( e.type == 'focusin' )
                $(this).addClass('open-search');
            else 
                $(this).removeClass('open-search');
        }


	})(window,jQuery);
</script>
@stop
