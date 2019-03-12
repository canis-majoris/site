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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('discounts.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.discounts')}}</a>
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
                                            <input id="date_start_from" type="date" class="datepicker date_picker-custom-1" value="" name="date_start_from" style="margin: 0;">
                                            <label for="date_start_from" class="input-label-1">от</label>
                                        </div>
                                        <div class="input-field col" style="">
                                            <input id="date_start_to" type="date" class="datepicker date_picker-custom-1" value="" name="date_start_to" style="margin: 0;">
                                            <label for="date_start_to" class="input-label-1">до</label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="datapicker-wrapper-1">
                                        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">{{trans('main.orders_products.table.filter.date.end')}}:</span></div>
                                        <div class="input-field col" style="">
                                            <input id="date_end_from" type="date" class="datepicker date_picker-custom-1" value="" name="date_end_from" style="margin: 0;">
                                            <label for="date_end_from" class="input-label-1">от</label>
                                        </div>
                                        <div class="input-field col" style="">
                                            <input id="date_end_to" type="date" class="datepicker date_picker-custom-1" value="" name="date_end_to" style="margin: 0;">
                                            <label for="date_end_to" class="input-label-1">до</label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="input-field col l2 m2 s12">
                                    <select class="" tabindex="-1" style="width: 50%" id="status-select-filter" name="status">
                                        <option value="0">{{trans('main.misc.any')}}</option>
                                        <option value="1">Активен</option>
                                        <option value="2">Не активен</option>
                                    </select>
                                    <label>{{trans('main.orders_products.table.filter.status')}}</label>
                                </div>
                                <div class="input-field col sl2 m2 s12">
                                    <select class="type_select-filter" name="type" id="type-select-filter">
                                        <option value="0">{{trans('main.users.table.filter.status.default')}}</option>
                                        <option value="1">{{trans('main.discounts.manage.form.all_discount')}}</option>
                                        <option value="2">{{trans('main.discounts.manage.form.packet_discount')}}</option>
                                        <option value="3">{{trans('main.discounts.manage.form.products_discount')}}</option>
                                    </select>
                                    <label>{{trans('main.discounts.manage.form.discount_type')}}</label>
                                </div>
                                <div class="input-field col l2 m3 s12" style="float: right !important;">
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
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light custom-main-color-1 menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add discount" style="font-size: 30px;">playlist_add</i></a>
    </div>
</main>

<!-- Manage Discount -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>


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
        var status = 0;
        var type = 0;
        var payed_status = 0;
        var date_picker_1 = {
            'date_start_from':null,
            'date_start_to':null,
            'date_end_from':null,
            'date_end_to':null
        }
        var search_input = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'discounts',
                //type: 'POST',
                data: function (d) {
                    //d.activated = activity_value;
                    d.status = status;
                    d.type = type;
                    d.date = date_picker_1;
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
				{"name":"id","data":"id","title":"{{trans('main.misc.id')}}","orderable":true,"searchable":false},
                {"name":"status","data":"status","title":"{{trans('main.discounts.table.header.status')}}","orderable":false,"searchable":false},
                {"name":"code", "data": 'code', "title":"{{trans('main.discounts.table.header.code')}}","orderable":true,"searchable":true},
                {"name":"", "data": 'discount_type', "title":"{{trans('main.discounts.table.header.discount_type')}}","orderable":false,"searchable":false},
                {"name":"", "data": 'discount_percents', "title":"{{trans('main.discounts.table.header.discount_percents')}}","orderable":false,"searchable":false, 'width':'30%'},
				{"name":"date_start", "data": 'date_start', "title":"{{trans('main.discounts.table.header.date_start')}}","orderable":true,"searchable":false},
                {"name":"date_end","data":"date_end","title":"{{trans('main.discounts.table.header.date_end')}}","orderable":true,"searchable":false},
                {"name":"user","data":"user","title":"{{trans('main.discounts.table.header.user')}}","orderable":true,"searchable":false},
				{"name":"limit","data":"limit","title":"{{trans('main.discounts.table.header.limit')}}","orderable":true,"searchable":false},
                {"name":"action","data":"action","title":"","orderable":false,"searchable":false},
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

                                url = "{{route('discounts-delete')}}";
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
                { className: "selectable", "targets": [0,2,3,4,5,6,7] }
            ],
            "order": [[ 0, "desc" ]],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
            

		});

        //window.LaravelDataTables["dataTableBuilder"].rows( { selected: true } ).data();

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

       // $('.dataTable').addClass('responsive-table');
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
            url = "{{ route('discounts-changestatus') }}";
            changestatus($(this), url);
        });

        $(document).on('click', '.show-discount-btn',function(e) {
            $('.show-discount-btn').off('click');
            e.preventDefault();
            var url = "{{ route('discounts.edit.show') }}";
            var data = {'id': $(this).data('id')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data);
        });

        $(document).on('click', '#add_btn',function(e) {
            e.preventDefault();
            var url = "{{ route('discounts.edit.show') }}";
            var data = {};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        $(document).on('click', '.assign-to-users-btn',function(e) {
            $('.assign-to-users-btn').off('click');
            e.preventDefault();
            var url = "{{ route('discounts.users.edit') }}";
            var data = {'id': $(this).data('id')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        $(document).off('click', '.track-discount-btn').on('click', '.track-discount-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('discounts.track', "id_plc") }}";
            var data = {'id': $(this).data('id')};
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

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

        $(document).on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            show_user($(this), container, url);
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

        $(document).on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            show_user($(this), container, url);
        });

        $(document).on('click', '#manage_save-btn', function (e) {
            $('#manage_discount_form').submit();

            delay(function(){
                window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );

            $(this).unbind('click');
        });

        $(document).on('click', '#discount_save-cancel-btn', function (e) {
            $('#manage_modal').modal('close');
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
