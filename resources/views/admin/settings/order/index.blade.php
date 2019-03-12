@extends('layout.main')

@section('title', 'Admin - Order Parameters')

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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('order_settings.index')}}" style="font-size:25px;">{{trans('dashboard.sidebar.list.settings.order_parameters')}}</a>
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
            <div class="">
                <div class="col s12 m6 l8 grid-item">
                    <div class="card invoices-card">
                        <div class="card-content">
                            <form method="POST" id="order_settings-form" class="form-inline" role="form" action="">
                                <div class="load-filter-container row deep-purple lighten-5">
                                    <div class="input-field col l2 m3 s6" style="padding-left: 0;">
                                        <select multiple name="region[]" id="settings-region-select">
                                            <option value="" disabled selected>{{trans('main.users.table.filter.region.default')}}</option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->id}}" >{{$region->name}}</option>
                                            @endforeach
                                        </select>
                                        <label style="left:0;">{{trans('main.users.table.filter.region.header')}}</label>
                                    </div>
                                    <div class="input-field col l4 m3 s12" style="float: right">
                                        <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input_settings" name="search_input_settings">
                                        <div class="clear"></div>
                                        <!-- <label for="date_end" class="input-label-1">date end</label> -->
                                    </div>
                                </div>
                            </form>

                            @include('settings.show')
                        </div>
                    </div>
                </div>
                <div class="col s12 m6 l4 grid-item">
                    <div class="card invoices-card custom-main-color-1">
                        <div class="card-content">
                            <div class="card-options">
                                <input type="text" class="expand-search" placeholder="Search" autocomplete="off">
                            </div>
                            <form method="POST" id="search-form" class="form-inline" role="form" action="">
                                <!--<input type="submit" value="test"> -->
                            </form>
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light red menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add promo" style="font-size: 30px;">playlist_add</i></a>
    </div> -->
</main>

<!-- Manage promo -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>

<script src="{{ URL::asset('admin_assets/plugins/masonry/masonry.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/miscellaneous-masonry.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
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

    $grid = $('.grid');
    $grid.masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true,
        gutter: 0
    });

    // $grid.on( 'layoutComplete', function( event, items ) {
      
    // });

    // $(document).off('click', '#profile_holder_tab, .collapsible-header').on('click', '#profile_holder_tab, .collapsible-header', function(e) {
    //     $grid.masonry()
    // });

    $('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });
    var picker = datepicker.pickadate('picker');


	(function(window,$){
        $('.dropdown-button').dropdown();
        var category = null;
        var search_input = null;
        var region_value = null;
        var settings_datatable = $("#settings-datatable").DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: 'settings',
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.category = 'orders';
                    d.region = region_value;
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
            columns: [
                {"name":"id","data":"id","title":"{{trans('main.misc.id')}}","orderable":true,"searchable":false},
                {"name":"status","data":"status","title":"{{trans('main.settings.table.header.status')}}","orderable":true,"searchable":true},
                {"name":"name","data":"name","title":"{{trans('main.settings.table.header.name')}}","orderable":true,"searchable":true},
                {"name":"value","data":"value","title":"{{trans('main.settings.table.header.value')}}","orderable":true,"searchable":true},
                {"name":"description","data":"description","title":"{{trans('main.settings.table.header.description')}}","orderable":true,"searchable":true},
                {"name":"region","data":"region","title":"{{trans('main.settings.table.header.region')}}","orderable":true,"searchable":true},
                {"name":"options","data":"options","title":"{{trans('main.settings.table.header.options')}}","orderable":true,"searchable":true},
                {"name":"img", "data": 'img', "title":"{{trans('main.settings.table.header.img')}}","orderable":true,"searchable":true},
                {"name":"action","data":"action","title":"","orderable":false,"searchable":false},
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [0, 1, 2] }
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBrtip',
            buttons: ["print","reset","reload",
                {
                    text: '<i class="material-icons dp48" style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }}"+'</span>',
                    className: 'custom-toolbar-btn-1 add_btn-settings',
                    action: function ( e, dt, node, config ) {
                        //add_product('stb');
                        var url = "{{ route('settings.create') }}";
                        var data = {'category': 'orders'};
                        var container = $('#manage_modal > .container-2');
                        manage_load_modal(url, container, data, false);
                    }
                },
                {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                    action: function ( e, dt, node, config ) {
                        var rows = dt.rows( { selected: true } );
                        var dataArr = rows.data().toArray();
                        var rowCount = rows.count();
                        dataArr = $.map(dataArr, function(n,i){
                           return [ n.id ];
                        });

                        swal({
                            title: "{{trans('main.misc.delete_title')}}",
                            //text: "You will not be able to recover these discounts!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "{{trans('main.misc.delete')}}",
                            cancelButtonText: "{{trans('main.misc.cancel')}}",
                        }).then(function () {
                            url = "{{route('settings.destroy')}}";
                            delete_items(url, dataArr, rowCount, [settings_datatable]);
                        });
                    }
                },
            ],
            drawCallback: function(settings){
                
                 var api = this.api();  
                 // Initialize custom control
                 //initDataTableCtrl(api.table().container());
            },
            //'responsive': true,
        });

        settings_datatable.on( 'processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.card-content');
            if (processing) {
                $(this).closest('.dataTables_wrapper').addClass('svg-blur-1');
                if (wrapper.find('.preloader-full').length == 0) {
                    $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                }
                
            } else {
                $(this).closest('.dataTables_wrapper').removeClass('svg-blur-1');
                wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );


		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'order_settings',
                //type: 'POST',
                data: function (d) {
                    //d.activated = activity_value;
                    // d.status = status;
                    // d.type = type;
                    // d.date = date_picker_1;
                    // d.search_input = search_input;
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                },
            },
			"language": {
	            searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
                //sProcessing: "{{trans('main.filter.processing')}}",
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
				{"name":"id","data":"id","title":"{{trans('main.misc.id')}}","orderable":true,"searchable":false},
                {"name":"name","data":"name","title":"{{trans('main.order_statuses.table.header.name')}}","orderable":true,"searchable":true},
                {"name":"text", "data": 'text', "title":"{{trans('main.order_statuses.table.header.text')}}","orderable":true,"searchable":true},
                {"name":"action","data":"action","title":"","orderable":false,"searchable":false},
			],
			"dom":"<'row'<'col l12 m12 s12'lBrf>>" +
				"<'row'<'col l12 m12 s12'ip>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons":[
                {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                    action: function ( e, dt, node, config ) {
                        var rows = dt.rows( { selected: true } );
                        var dataArr = rows.data().toArray();
                        var rowCount = rows.count();
                        dataArr = $.map(dataArr, function(n,i){
                           return [ n.id ];
                        });

                        swal({
                            title: "{{trans('main.misc.delete_title')}}",
                            //text: "You will not be able to recover these discounts!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "{{trans('main.misc.delete')}}",
                            cancelButtonText: "{{trans('main.misc.cancel')}}",
                        }).then(function () {
                            url = "{{route('promos-delete')}}";
                            delete_items(url, dataArr, rowCount, [window.LaravelDataTables["dataTableBuilder"]]);
                        });
                    }
                },
                {
                text: '<i class="material-icons dp48 " style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        var url = "{{route('order_settings.create')}}";
                        manage_product(url);
                    }
                }
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [0, 1, 2] }
            ],
            "order": [[ 0, "desc" ]],
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
            if ($(this).attr('id') == 'search_input_settings') {
                settings_datatable.draw()
            } else window.LaravelDataTables["dataTableBuilder"].draw();
            
            return;
        });



        $('.dataTable').addClass('responsive-table');
		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

        window.LaravelDataTables["dataTableBuilder"].on( 'processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.card-content');
            if (processing) {
                $(this).closest('.dataTables_wrapper').addClass('svg-blur-1');
                if (wrapper.find('.preloader-full').length == 0) {
                    $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                }
                
            } else {
                $(this).closest('.dataTables_wrapper').removeClass('svg-blur-1');
                wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );


        settings_datatable.on( 'processing.dt', function ( e, settings, processing ) {
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

        $(document).off('click', '#save-cancell-btn').on('click', '#save-cancell-btn', function (e) {
            $('#manage_modal').modal('close');
        });

	    $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('settings.changestatus') }}";
            changestatus($(this), url);
        });

        $(document).off('click', '.settings-edit-btn').on('click', '.settings-edit-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('settings.edit') }}";
            var data = {'id': $(this).data('id'), 'category': 'orders'};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        $(document).off('click', '.edit-order_status-btn').on('click', '.edit-order_status-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('order_status.edit') }}";
            var data = {'id': $(this).data('id')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        /*$(document).off('click', '.add_btn-settings').on('click', '.add_btn-settings',function(e) {
            e.preventDefault();
            var url = "{{ route('settings.edit') }}";
            var data = {'category': 'orders'};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });*/

        $(document).on('change', '#status-select-filter',function(e) {
            status = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#type-select-filter',function(e) {
            type = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '.datepicker',function(e) {
            id = $(this).attr('id');
            date_picker_1[id] = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#settings-region-select',function(e) {
            region_value = $(this).val();
            delay(function(){
              settings_datatable.draw();
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

        $(document).off('click', '#manage_save-btn').on('click', '#manage_save-btn', function (e) {
            $('#manage_settings_form').submit();

            delay(function(){
                settings_datatable.draw();
            }, 200 );
        });

        $(document).on('click', '#promo_save-cancel-btn', function (e) {
            $('#manage_modal').modal('close');
        });

        $(document).off('click', '.track-promo-btn').on('click', '.track-promo-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('promos.track', "id_plc") }}";
            var data = {'id': $(this).data('id')};
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
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
