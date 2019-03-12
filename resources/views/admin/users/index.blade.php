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
                                <div class="col l6 m6 s12 hide-on-med-and-down">
                                    <div class=" bred-c-holder-1" >
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users.index', 'type=all')}}" style="font-size:25px;">{{trans('main.breadcrumbs.users')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            <ul class="tabs z-depth-1 right">
                                                <li class="tab"><a data-type="all" class="type-selector-1 user-tabs-1 @if($type == 'all') active @endif">{{trans('dashboard.sidebar.list.users.standard_users')}}</a></li>
                                                <li class="tab"><a data-type="cash_payers" class="type-selector-1 user-tabs-1 @if($type == 'cash_payers') active @endif">{{trans('dashboard.sidebar.list.users.cash_paying_users')}}</a></li>
                                            <div class="indicator" style="right: 403px; left: 201.5px;"></div></ul>
                                        </div>
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
                {{-- <div class="row" style="margin: 0;">
                    
                    <div class="col l6 m6 s12" style="padding: 0;">
                        <div class="row" style="margin-bottom: 0;">
                            <div class="">
                                <form method="POST" id="search-form" class="form-inline" role="form" action="">
                                    <div class="row" style="padding:0 !important; margin: 0;">
                                        <div class="input-field control-input col l4 m6 s12">
                                            <input type="text" class="form-control" name="title" id="title">
                                            <label class="glyphicon glyphicon-search inside-input-right">ძიება სახელით</label>
                                        </div>
                                        <div class="input-field control-input col l4 m6 s12">
                                            <input type="text" class="form-control" name="subtitle" id="subtitle">
                                            <label class="glyphicon glyphicon-search inside-input-right">ძიება მოკლე აღწერით</label>
                                        </div>
                                        <div class="input-field control-input col l4 m6 s12">
                                            <input type="text" class="form-control" name="description" id="description">
                                            <label class="glyphicon glyphicon-search inside-input-right">ძიება აღწერით</label>
                                        </div>
                                        <div class="right">
                                            <ul class="tabs z-depth-1 right" style="width: 100%;">
                                                <li class="tab"><a data-type="all" class="type-selector-1 @if($type == 'all') active @endif">all</a></li>
                                                <li class="tab"><a data-type="cash_payers" class="type-selector-1 @if($type == 'cash_payers') active @endif">cash payers</a></li>
                                            </ul>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <!--<input type="submit" value="test"> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div> --}}
                <div class="card invoices-card">
                    <div class="card-content">
                        <form method="POST" id="search-form" class="form-inline" role="form" action="">
                            <div class="load-filter-container row deep-purple lighten-5">
                                {!! $filter !!}
                            </div>
                            <div class="clear"></div>
                        </form>
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add User -->
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light custom-main-color-1 menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add User" style="font-size: 30px;">playlist_add</i></a>
    </div>
</main>

<!-- Manage Language -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $('body').addClass('search-app');

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

    $('label.input-label-1').addClass('active');

	(function(window,$){
        $('.dropdown-button').dropdown();
		var lang_ind = null;
        var activity_value = 2;
        var status_value = 2;
        var only_dealers = 0;
        var type = "{{$type}}";
        var custom1 = 'any';
        var date_picker_1 = {
            'date_start':null,
            'date_end':null,
        }
        var active_service_type_id = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'users',
                //type: 'POST',
                data: function (d) {
                    d.activated = activity_value;
                    d.status = status_value;
                    d.only_dealers = only_dealers;
                    d.type = type;
                    d.custom1 = custom1;
                    d.active_service_type_id = active_service_type_id;
                    d.date = date_picker_1;
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
				{"name":"id","data":"id","title":"{{trans('main.users.table.header.id')}}","orderable":true,"searchable":true, "visible":false},
				{"name":"activated", "data": 'status', "title":"{{trans('main.users.table.header.status')}}","orderable":true,"searchable":true},
				{"name":"code", "data": 'code', "title":"{{trans('main.users.table.header.code')}}","orderable":true,"searchable":true},
				{"name":"name","data":"name","title":"{{trans('main.users.table.header.name')}}","orderable":true,"searchable":true},
				{"name":"city","data":"city","title":"{{trans('main.users.table.header.city')}}","orderable":true,"searchable":true},
				{"name":"email","data":"email","title":"{{trans('main.users.table.header.email')}}","orderable":true,"searchable":true},
				{"name":"phone","data":"phone","title":"{{trans('main.users.table.header.phone')}}","orderable":true,"searchable":true},
				{"name":"active_service","data":"activity","title":"{{trans('main.users.table.header.activity')}}","orderable":true,"searchable":false},
                {"name":"dealer_id","data":"dealer","title":"{{trans('main.users.table.header.dealer')}}","orderable":true,"searchable":false},
                {"name":"statistics","data":"statistics","title":"{{trans('main.users.table.header.statistics')}}","orderable":false,"searchable":false},
                /*{"name":"action","data":"action","title":"","orderable":false,"searchable":false},*/
				{"name":"remove","data":"remove","title":"","orderable":false,"searchable":false},
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

                                url = "{{route('users-delete')}}";
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
                { className: "selectable", "targets": [3,4,5,6,7] }
            ],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },

		});

        function initDataTableCtrl(container) {
            $('.tooltipped').tooltip({delay: 50});
        }

        $(document).off('input', "input.expand-search") // Unbind previous default bindings
        .on("input", "input.expand-search", function(e) { // Bind our desired behavior
            // If the length is 3 or more characters, or the user pressed ENTER, search

            if(this.value.length >= 1 || e.keyCode == 13) {
                // Call the API search function
                window.LaravelDataTables["dataTableBuilder"].search(this.value).draw();
            }
            // Ensure we clear the search if they backspace far enough
            if(this.value == "") {
                window.LaravelDataTables["dataTableBuilder"].search("").draw();
            }
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

        var load_filter = function(type){
            activity_value = 2;
            status_value = 2;
            only_dealers = 0;
            var url = "{{route('users.load_filter')}}";
            var curr_url = "{{route('users.index')}}";
            op_load_filter(url, type, $('.load-filter-container'), window.LaravelDataTables["dataTableBuilder"]);
            window.history.pushState("", "Title", curr_url + '?type=' + type);
        }

        $(document).ready(function(e) {
            var type = "{{$type}}";
            load_filter(type);
        });

        $(document).on('click', '.user-tabs-1',function(e) {
            e.preventDefault();
            type = $(this).data('type');
            load_filter(type);
        });

	    $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('user-changestatus') }}";
            changestatus($(this), url);
        });

        $(document).on('change', '#users-activity-select',function(e) {
            activity_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#users-status-select',function(e) {
            status_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#users-custom1-select',function(e) {
            custom1 = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#show-dealers-only',function(e) {
            if ($(this).is(':checked')) {
                only_dealers = 1;
            } else only_dealers = 0;
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#users-service-select',function(e) {
            active_service_type_id = $(this).val();
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

        $(document).on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            show_user($(this), container, url);
        });

        $(document).on('click', '.show-user-statistics-btn',function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var data = {'id': id};
            var url = "{{ route('users-show-statistics', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).on('click', '#add_btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-update') }}";
            var data = {};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        $(document).on('click', '.show-user-balance-btn',function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var data = {'id': id};
            var url = "{{ route('users-show-balance', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).on('click', '#cash_payer_user-save-btn', function (e) {
            var url = "{{route('users.add_cash_payer')}}";
            data = {'user_id': $('.cash_payer_users-select').val()};
            add_cash_payer(url, data);
            $('.cash_payer_users-select').find('option:selected').prop('disabled', true);
            $('.cash_payer_users-select').select2("val", "default");
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            $(this).prop('disabled', true).unbind('click');
        });

        $(document).on('click', '.remove_from_cash_payers-user-btn', function (e) {
            var url = "{{route('users.add_cash_payer')}}";
            data = {'user_id': $(this).data('id')};
            add_cash_payer(url, data);
            $('.cash_payer_users-select').find("option[value='"+$(this).data('id')+"']").prop('disabled', false);
            $('.cash_payer_users-select').select2("val", $(this).data('id'));
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
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
