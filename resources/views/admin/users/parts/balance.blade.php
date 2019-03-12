<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div class="">
    <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l10 m10 s12">
                                    <div class=" bred-c-holder-1" >
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users.index', 'type=all')}}">{{trans('main.breadcrumbs.users')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users-show', $user->id)}}">{{ $user->code }}</a> <span><i class="material-icons">keyboard_arrow_right</i></span>
                                        <a href="{{route('users-show-balance', $user->id)}}" style="font-size:25px;">{{trans('main.breadcrumbs.balance')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m1 l1 tab-holder-wrapper-1 hide-on-med-and-down" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-m-t no-m-b">
    	<div class="col l12 m12 s12 side-holder-next">
            <div class="card invoices-card">
                <div class="card-content">
                    <div class="statistics-parameters-container row" style="padding:1.5rem; margin: 0;">
                        
                        <div class="wrapper-custom-1 col l6 m12 s12" style="display: block; box-shadow: none;">
                            <div class="row">
                                <div class="col l12 m12 s12"><h6 style="margin-top: 1rem;">Управление балансом дилера <b>{{ $user->code }}</b></h6></div>
                            </div>
                            <div class="row">
                                <div class="input-field col l6 m6 s12 left">
                                    <input type="number" class="validate" id="balance_number_input" name="balance_number_input">
                                    <label for="balance_number_input" class="input-label-1">Сумма</label>
                                </div>
                                <div class="input-field col l6 m6 s12">
                                    <select class="" tabindex="-1" style="width: 50%" id="users-status-select">
                                      <option value="1">Зачислить</option>
                                      <option value="2">Снять</option>
                                    </select>
                                    <label>Тип операции</label>
                                </div>
                                <div class="input-field col l12 m12 s12">
                                    <textarea id="reason" class="materialize-textarea valid" length="512" style="height: 21.2px;" name="reason"></textarea>
                                    <label for="comment" class="active">Причина</label>
                                    <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
                                </div>
                                <div class="col l12 m12 s12">
                                    <button class="btn btn-flat right waves-effect waves-green">Выполнить</button>
                                </div>
                            </div>
                        </div>
                        <div class="stat_item custom-badge-1 deep-purple white-text lighten-3 right waves-effect waves-teal" style="display: inline-block;">Баланс дилера: <b style="font-size: 20px; margin-left: .5rem;" class="dealer_balance_block"> {{ $currency->sign . number_format((float)$user->score, 2, '.', '') }} </b></div>
                        
                    </div>
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
                            <div class="input-field col l2 m3 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="statistic-type-select" name="order_status">
                                    <option value="0">Любой</option>
                                    <option value="1">{{ trans('main.users.manage.statistics.in') }}</option>
                                    <option value="2">{{ trans('main.users.manage.statistics.out') }}</option>
                                    <option value="3">{{ trans('main.users.manage.statistics.deposit') }}</option>
                                </select>
                                <label>status</label>
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
                    @include('users.statistics.balance')
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('admin_assets/js/unit_converter.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
	var global_width;
    var type_value = 0;
    var date_start = null;
    var date_end = null;
    var search_input = null;
    var date_start_parameters = null;
    var date_end_parameters = null;
	$(document).ready(function() {
		
        $('.collapsible').collapsible();
        var balance_datatable = $('#balance-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('dealerstats.balance')}}",
                //type: 'POST',
                data: function (d) {
                    d.user_id = "{{$user->id}}";
                    d.type = type_value;
                    d.date_start = date_start;
                    d.date_end = date_end;
                    d.search_input = search_input;
                },
            },
            responsive: true,
            language: {
                searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
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
                {"name":"id","data":"id","title":"{{trans('main.misc.id')}}","orderable":true,"searchable":true},
                {"name":"status","data":"status","title":"{{trans('main.dealer_os_stats.table.header.status')}}","orderable":true,"searchable":false},
                {"name":"date","data":"date","title":"{{trans('main.dealer_os_stats.table.header.date')}}","orderable":true,"searchable":true},
                {"name":"user_id","data":"user","title":"{{trans('main.dealer_os_stats.table.header.user')}}","orderable":false,"searchable":true},
                {"name":"summ","data":"summ","title":"{{trans('main.dealer_os_stats.table.header.summ')}}","orderable":false,"searchable":false},
                /*{"name":"action","data":"action","orderable":false,"searchable":false},*/
            ],
            "dom":"<'row'<'col l12 m12 s12'lBr>>" +
                "<'row'<'col l12 m12 s12'ip>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            buttons: ["csv",
                {
                    extend: 'excel',
                    //text: 'excel',
                    exportOptions: {
                        columns: [ 0, 2, 3, 4, 5]
                    }
                },
                "print","reset","reload",
            ],
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
            "order": [[ 0, "desc" ]],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
        });

        function initDataTableCtrl(container) {
            $('.multi-select', container).material_select();
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
            balance_datatable.draw();
            return;
        });

		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

        balance_datatable.on( 'processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.card-content');
            var tables_wrapper = $(this).closest('.dataTables_wrapper');
            if (processing) {
                tables_wrapper.addClass('processing-table');
                if (wrapper.find('.preloader-full').length == 0) {
                    //$(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                }

                if (wrapper.find('.ocean').length == 0) { 
                    $(wave_animation).hide().prependTo(tables_wrapper).fadeIn(200);
                }
                
            } else {
               tables_wrapper.removeClass('processing-table');
               wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );

	    $('select').material_select();

	    var datepicker = $('.datepicker').pickadate({
		    selectMonths: true,
		    selectYears: 100,
		    format: 'yyyy-mm-dd',
		});

		$('label.input-label-1').addClass('active');

		$(document).on('change', '#statistic-type-select',function(e) {
            type_value = $(this).val();
            delay(function(){
              balance_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_start',function(e) {
            date_start = $(this).val();
            load_parameters_data();
            delay(function(){
              balance_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_end',function(e) {
            date_end = $(this).val();
            load_parameters_data();
            delay(function(){
              balance_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        // var load_parameters_data = function() {
        // 	var url = "{{ route('dealerstats.load.parameters') }}";
        //     var container = $('.statistics-parameters-container');
        //     data = {'date_start': date_start, 'date_end': date_end, 'user_id': "{{ $user->id }}"};
        //     manage_load(url, container, data, 0);
        // }

        // load_parameters_data();

        var toggleFocus = function(e){
            if( e.type == 'focusin' )
                $(this).addClass('open-search');
            else 
                $(this).removeClass('open-search');
        }

        $(document).on('focus blur', '.expand-search', toggleFocus);

        $(document).on('click', '.show-order-btn',function(e) {
            $('.show-order-btn').unbind('click');
            e.preventDefault();
            var url = "{{ route('orders-show', 0) }}";
            var container = $('.mn-inner');
            show_order($(this), container, url);
        });

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();
    });
</script>