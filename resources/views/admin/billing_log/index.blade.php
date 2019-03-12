@extends('layout.main')

@section('title', 'billing Log - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
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
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('billinglog.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.billinglog')}}</a>
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
                                        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">Дата создания:</span></div>
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
                                <div class="input-field col l3 m4 s6">
                                    <select multiple name="type[]" id="billing_log-type-select">
                                      <option value="" selected disabled>{{trans('main.misc.any')}}</option>
                                      <option value="daily_subscription_managemant_results">Daily subscription managemant results</option>
                                      <option value="daily_service_deactivation_results">Daily service deactivation results</option>
                                      <option value="cartu_transaction_update">Cartu transaction update</option>
                                    </select>
                                    <label>Type</label>
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

<!-- Manage Administrator -->
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

    var date_picker_1 = {
        'date_start':null,
        'date_end':null,
    };

    $('label.input-label-1').addClass('active');

	(function(window,$){
        $('.dropdown-button').dropdown();
	    var lang_ind = null;
        var region_value = 'any';
        var type_value = 'any';
        var search_input = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'billinglog',
                //type: 'POST',
                data: function (d) {
                    d.region = region_value;
                    d.type = type_value;
                    d.search_input = search_input;
                    d.date = date_picker_1;
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
                {"name":"id","data":"id","title":"{{trans('main.users.table.header.id')}}","orderable":true,"searchable":true, 'visible':false},
                {"name":"created_at","data":"created_at","title":"{{trans('main.billing_log.table.header.created_at')}}","orderable":true,"searchable":true},
                //{"name":"text", "data": 'text', "title":"{{trans('main.billing_log.table.header.text')}}","orderable":false,"searchable":false},
    			{"name":"type", "data": 'type', "title":"{{trans('main.billing_log.table.header.type')}}","orderable":true,"searchable":true},
    			{"name":"description","data":"description","title":"{{trans('main.billing_log.table.header.description')}}","orderable":false,"searchable":false},
                {"name":"status","data":"status","title":"{{trans('main.billing_log.table.header.status')}}","orderable":true,"searchable":true},
                {"name":"id","data":"show_log","title":"","orderable":false,"searchable":false},
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

                                url = "{{route('billinglog.delete')}}";
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
                { className: "selectable", "targets": [1, 2, 3] }
            ],
      		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      		'iDisplayLength': 25,
            "order": [[ 0, "desc" ]]

		});

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

        /* Formatting function for row details - modify as you need */
        function format_expandable_row ( d ) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr class="">'+
                    '<td>'+d.text+'</td>'+
                '</tr>'+
            '</table>';
        }

         // Add event listener for opening and closing details
        $('#dataTableBuilder tbody').on('click', '.show_log-btn', function () {
            var tr = $(this).closest('tr');
            var row = window.LaravelDataTables["dataTableBuilder"].row( tr );
     
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format_expandable_row(row.data()) ).show();
                tr.addClass('shown');
            }
        } );


        $(document).on('change', '#billing_log-region-select',function(e) {
            region_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#billing_log-type-select',function(e) {
            type_value = $(this).val();
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
