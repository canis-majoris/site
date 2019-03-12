@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css">
<script src="{{ URL::asset('plugins/js/masonry.pkgd.min.js') }}"></script>

<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<!-- or -->
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js"></script>
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
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('delivery.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.delivery')}}</a>
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
                                <div class="input-field col l2 m3 s6" style="padding-left: 0;">
                                    <select class="" tabindex="-1" style="width: 50%" id="delivery-status-select">
                                      <option value="0">{{trans('main.delivery.table.filter.visibility.op_1')}}</option>
                                      <option value="1">{{trans('main.delivery.table.filter.visibility.op_2')}}</option>
                                      <option value="any" selected>{{trans('main.delivery.table.filter.visibility.default')}}</option>
                                    </select>
                                    <label style="left:0;">{{trans('main.delivery.table.filter.visibility.header')}}</label>
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
        <a class="btn-floating btn-large waves-effect waves-light custom-main-color-1 menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add delivery" style="font-size: 30px;">playlist_add</i></a>
    </div>
</main>

<!-- Manage delivery -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>

<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
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

    //$('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });

    $('label.input-label-1').addClass('active');
	(function(window,$){
        $('.dropdown-button').dropdown();
		var lang_ind = null;
        var status_value = 'any';
        var search_input = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'delivery',
                //type: 'POST',
                data: function (d) {
                    d.status = status_value;
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
				{"name":"id","data":"id","title":"{{trans('main.delivery.table.header.id')}}","orderable":true,"searchable":true, "visible":false},
                {"name":"hide","data":"status","title":"{{trans('main.delivery.table.header.hidden')}}","orderable":true,"searchable":false},
                {"name":"name","data":"name","title":"{{trans('main.delivery.table.header.name')}}","orderable":true,"searchable":true},
				{"name":"img", "data": 'img', "title":"{{trans('main.delivery.table.header.img')}}","orderable":false,"searchable":false},
                {"name":"price","data":"price","title":"{{trans('main.delivery.table.header.price')}}","orderable":true,"searchable":true},
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
                        columns: [ 0, 1, 2, 3]
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

                                url = "{{route('delivery-delete')}}";
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
                { className: "selectable", "targets": [0, 2, 3] }
            ],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 10,
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
        });

        function initDataTableCtrl(container) {
            
            $('.materialboxed').materialbox();
        }

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

        /*url_au = "{{ route('delivery-add') }}";
        form_au = $('#add_delivery_form');
        add_delivery(url_au, form_au);*/

	    $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('delivery-changestatus') }}";
            //change_user_status($(this), url);
            changestatus($(this), url);
        });

        $(document).on('change', '#delivery-status-select',function(e) {
            status_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('click', '.show-delivery-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            //show_delivery($(this), container, url);
        });

        $(document).off('click', '#add_btn').on('click', '#add_btn',function(e) {
            e.preventDefault();
            var url = "{{ route('delivery-edit') }}";
            var data = {};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
        });

        $(document).off('click', '.delivery-edit-btn').on('click', '.delivery-edit-btn', function(e) {
            var url = "{{route('delivery-edit')}}";
            var data = {id: $(this).data('id')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data, false);
            $('#manage_delivery_form').find('.input-field').find('label').addClass('active');
        });

        $(document).off('click', '#manage_save-btn').on('click', '#manage_save-btn', function (e) {
            $('#manage_delivery_form').submit();

            delay(function(){
                window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );

            $(this).unbind('click');
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
