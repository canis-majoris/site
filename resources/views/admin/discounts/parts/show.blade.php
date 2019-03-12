<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div class="">
    <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l6 m6 s12">
                                    <div class=" bred-c-holder-1" >
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> 
                                        <span><i class="material-icons">keyboard_arrow_right</i></span>
                                        <a href="{{route('discounts.index')}}">{{trans('main.breadcrumbs.discounts')}}</a>
                                        <span><i class="material-icons">keyboard_arrow_right</i></span>
                                        <a href="{{route('discounts.track', $discount->id)}}" style="font-size:25px;">{{ $discount->code }}</a>
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
    <div class="row no-m-t no-m-b">
    	<div class="col l12 m12 s12 side-holder-next">
            <div class="card invoices-card">
                <div class="card-content">
                    <div class="logs-parameters-container">
                        <div class="row" style="padding:1.5rem 10px; border-radius: 2px; margin: 0;">
                            <div class="col l3 m5 s12">
                                <div><b>Статус: </b>@if($discount->status == 1 && $discount->date_end <= date('Y-m-d'))<span class="green-text" style="text-transform: uppercase;"><b>активен</b></span>@else <span class="red-text" style="text-transform: uppercase;"><b>не активен</b></span> @endif</div>
                                <div><b>Тип: </b>
                                    @if($discount->type == 1)
                                        <span>Скидка на общую сумму</span>
                                    @elseif($discount->type == 2 || $discount->type == 3)
                                        @if($discount->type == 2)
                                            @php($disc_t_1 = 'Скидка на пакеты')
                                        @else 
                                            @php($disc_t_1 = 'Скидка на товары')
                                        @endif

                                        <span>{{ $disc_t_1 }} ({{ $discount->type_discount == 1 ? 'На один товар' : 'На все товары' }})</span>
                                    @endif
                                </div>
                                <div><b>Дата генерации: </b>{{ $discount->date_start }}</div>
                                <div><b>Действителен до: </b>{{ $discount->date_end }}</div>
                                <div><b>Количество использований: </b> @if($discount->limit == 0) {{ $discount->log()->count() }} @else {{ $discount->log()->count() . ' из ' . $discount->limit}} @endif</div>
                            </div>
                            <div class="col l9 m7 s12">
                                <div class="table-info-wrapper-reverse">
                                    <b>Скидка: </b>
                                    @if($discount->type == 1)
                                        <div class="chip">{{ $discount->percent.'%' }}</div>
                                    @elseif($discount->type == 2 || $discount->type == 3)
                                        @php($products_percent = json_decode($discount->products_percent, true))
                                        @if(count($products_percent))
                                            @php($products = \App\Models\Product\Product::where('region_id', session('region_id'))->find(array_keys($products_percent)))
                                            @foreach($products as $product)
                                                <div class="chip">{{ $products_percent[$product->id].'% - '.$product->name }}</div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
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
                            <div class="input-field col l2 m3 s12" style="margin-top: 0;">
                                <div class="input-field col s12">
                                    <input id="log_user" type="text" class="validate" name="log_user">
                                    <label for="log_user" class="input-label-1">{{ trans('main.discounts.log.filter.search_user') }}</label>
                                </div>
                            </div>
                            <div class="input-field col l2 m3 s12" style="margin-top: 0;">
                                <div class="input-field col s12">
                                    <input id="log_order" type="text" class="validate" name="log_order">
                                    <label for="log_order" class="input-label-1">{{ trans('main.discounts.log.filter.search_order') }}</label>
                                </div>
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
                    @include('discounts.log.log')
                </div>
            </div>
            {{-- <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">shopping_cart</i> {{ trans('main.misc.transactions') }}</h5>
                        </div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="col s12" style="padding:0 24px;">
                                    <div class="white">
                                    	
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div> --}}
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
    var date_start = null;
    var date_end = null;
    var search_input = null;
    var log_user = null;
    var log_order = null;
	$(document).ready(function() {
		
        $('.collapsible').collapsible();
        var logs_datatable = $('#logs-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('discounts.show.log')}}",
                //type: 'POST',
                data: function (d) {
                    d.discount_id = {{$discount->id}};
                    d.date_start = date_start;
                    d.date_end = date_end;
                    d.search_input = search_input;
                    d.log_user = log_user;
                    d.log_order = log_order;
                },
            },
            "responsive": true,
            language: {
                searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
                sProcessing: "{{trans('main.filter.processing')}}",
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
                {"name":"created_at","data":"created_at","orderable":true,"searchable":true},
                {"name":"order_id","data":"order_id","orderable":true,"searchable":true},
                {"name":"user_id","data":"user_id","orderable":true,"searchable":true},
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
                        columns: [ 0, 2, 3, 4, 5, 6]
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
            logs_datatable.draw();
            return;
        });

		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

        logs_datatable.on( 'processing.dt', function ( e, settings, processing ) {
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

	    $('select').material_select();

	    var datepicker = $('.datepicker').pickadate({
		    selectMonths: true,
		    selectYears: 100,
		    format: 'yyyy-mm-dd',
		});

		/*$('label.input-label-1').addClass('active');*/

		$(document).on('input', '#log_user',function(e) {
            log_user = $(this).val();
            delay(function(){
              logs_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('input', '#log_order',function(e) {
            log_order = $(this).val();
            delay(function(){
              logs_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_start',function(e) {
            date_start = $(this).val();
            delay(function(){
              logs_datatable.draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#date_end',function(e) {
            date_end = $(this).val();
            delay(function(){
              logs_datatable.draw();
            }, 200 );
            e.preventDefault();
        });


        var toggleFocus = function(e){
            if( e.type == 'focusin' )
                $(this).addClass('open-search');
            else 
                $(this).removeClass('open-search');
        }

        $(document).off('click', '.show-user-btn').on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            var data = {'user_id':$(this).data('id')}
            var url = "{{ route('users-show', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).off('click', '.show-order-btn').on('click', '.show-order-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('orders-show', "id_plc") }}";
            var container = $('.mn-inner');
            var data = {'order_id':$(this).data('id')};
            show_page($(this), container, url, data);
        });

        $(document).on('focus blur', '.expand-search', toggleFocus);

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();
    });
</script>