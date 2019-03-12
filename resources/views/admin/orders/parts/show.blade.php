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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('orders.index')}}" >{{trans('main.breadcrumbs.orders')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('orders-show', $order->id)}}" style="font-size:25px;">{{$order->code}}</a>
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
        <div class="col l3 m4 s12">
            <div>
                <ul class="collapsible collapsible-flat" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">shopping_cart</i> {{ trans('main.orders.manage.show.order_parameters') }}</h5>
                        </div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="content-block col l12 m12 s12">
                                    <form id="order-parameters-form">
                                        <input type="hidden" name="order_id" value="{{$order->id}}">
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <select class="" style="width: 100%" name="status">
                                                    @foreach($order_statuses as $o_s)
                                                        <option value="{{$o_s->id}}" @if($o_s->id == $order->status()->first()->id) selected @endif>{{$o_s->name}}</option>
                                                    @endforeach
                                                </select>
                                                <label>{{ trans('main.orders.manage.form.order_status') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="date" type="text" class="validate" value="{{$order->date}}" name="date_show" disabled>
                                                <label for="date" class="input-label-1">{{ trans('main.orders.manage.form.date') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="code" type="text" class="validate" value="{{$order->code}}" name="code">
                                                <label for="code" class="input-label-1">{{ trans('main.orders.manage.form.code') }}</label>
                                            </div>
                                            <input type="hidden" name="date" value="{{$order->date}}">
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <h5><span class="card-title">{{ trans('main.users.manage.form.headers.personal_info') }}</span></h5>
                                            </div>
                                            <div class="input-field col s12">
                                                <input value="{{$order->name}}" type="text" class="validate" name="name" id="name">
                                                <label for="name" class="input-label-1">{{ trans('main.users.manage.form.name') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="birth_date" type="date" class="datepicker" value="" name="bith_date">
                                                <label for="birth_date" class="input-label-1">{{ trans('main.users.manage.form.birth_date') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="email" type="email" class="validate" value="{{$order->email}}" name="email">
                                                <label for="email" class="input-label-1">{{ trans('main.users.manage.form.email') }}</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <h5><span class="card-title">{{ trans('main.orders.manage.form.headers.delivery_address') }}</span></h5>
                                            </div>
                                            <div class="input-field col s12">
                                                <input value="{{$order->flat}}" type="text" class="validate" name="flat" id="flat">
                                                <label for="flat" class="input-label-1">{{ trans('main.users.manage.form.address') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="region" type="text" class="validate" value="{{$order->region}}" name="region">
                                                <label for="region" class="input-label-1">{{ trans('main.users.manage.form.region') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="postcode" type="text" class="validate" value="{{$order->postcode}}" name="postcode">
                                                <label for="postcode" class="input-label-1">{{ trans('main.users.manage.form.postcode') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <select class="" style="width: 100%" name="country">
                                                    @foreach($countries as $country)
                                                        <option value="{{$country->id}}" @if($country->countryName == $order->country) selected @endif>{{$country->countryName}}</option>
                                                    @endforeach
                                                </select>
                                                <label>{{ trans('main.users.manage.form.country') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="city" type="text" class="validate" value="{{$order->city}}" name="city">
                                                <label for="city" class="input-label-1">{{ trans('main.users.manage.form.city') }}</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <h5><span class="card-title">{{ trans('main.users.manage.form.headers.contact_info') }}</span></h5>
                                            </div>
                                            <div class="input-field col s12">
                                                <input value="{{$order->phone}}" type="text" class="validate" name="phone" id="phone">
                                                <label for="phone" class="input-label-1">{{ trans('main.users.manage.form.phone') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <input id="mobile" type="text" class="validate" value="{{$order->mobile}}" name="mobile">
                                                <label for="mobile" class="input-label-1">{{ trans('main.users.manage.form.mobile') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <!-- <i class="material-icons prefix">mode_edit</i> -->
                                                <textarea id="user_comment" class="materialize-textarea" name="user_comment">{{$order->user_comment}}</textarea>
                                                <label for="user_comment">{{ trans('main.orders.manage.form.user_comment') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <!-- <i class="material-icons prefix">mode_edit</i> -->
                                                <textarea id="admin_comment" class="materialize-textarea" name="admin_comment">{{$order->admin_comment}}</textarea>
                                                <label for="admin_comment">{{ trans('main.orders.manage.form.admin_comment') }}</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <button class="waves-effect waves-light btn teal pull-right" type="submit" data-reset="{{ trans('main.misc.save') }}" id="save-order-parameters-btn">{{ trans('main.misc.save') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
       	<div class="col l9 m8 s12">
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5>{{ trans('main.misc.order') }} #{{$order->code}}</h5>
                            @if($order->prolong_id)
                                <span>{{ trans('main.orders.manage.service_extension') }} {{$order->orders_products()->first()->generate_service_code($order->prolong_id)}}</span>
                            @endif
                        </div>
                        <div class="collapsible-body table-holder-1">
                            <div class="row">
                                <div class="content-block">
                                    @if(!$order->prolong_id && $order->orders_products()->count() > 0)
                                        <div class="row">
                                            <div class="">
                                                <div class="white">
                                                    @include('orders.products')
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col l12 m12 s12">
                                        <div class="deep-purple lighten-5" style="padding: 15px; border-radius: 2px; margin: .75rem;">
                                            @if($order->ups)
                                                <div><b>{{ trans('main.orders.manage.delivery_type') }}:</b> <span>{{$order->ups}} ({{$order->ups_price}} {{$currency->sign}})</span></div>
                                            @else
                                                <div><b>{{ trans('main.orders.manage.delivery_type') }}:</b> <span>{{ trans('main.orders.manage.standard_service') }}</span></div>
                                            @endif

                                            <div><b>{{ trans('main.orders.manage.payment_type') }}:</b> 
                                                <span>
                                                    @if($order->pay_type==0) {{ trans('main.orders.manage.by_card') }} 
                                                    @elseif($order->pay_type==1) {{ trans('main.orders.manage.part_from_score') }} 
                                                    @elseif($order->pay_type==2) {{ trans('main.orders.manage.from_score') }} 
                                                    @elseif($order->pay_type==3) {{ trans('main.orders.manage.by_cash') }} 
                                                    @elseif($order->pay_type==10) {{ trans('main.orders.manage.by_paypal') }} 
                                                    @else {{ trans('main.orders.manage.by_card') }} 
                                                    @endif
                                                </span>
                                            </div>

                                            @if($order->promocode)
                                                <div><b>{{ trans('main.orders.manage.promo_used') }}:</b> <b class="green-text">{{$order->promostr}}</b></div>
                                                <div>   
                                                    <b>{{ trans('main.orders.manage.promo_discount_sum') }}:</b> 
                                                    <span class="price">
                                                        @if($order->promostr == 'TRY1M4FREE')
                                                            {{ $order->get_order_price($order->id) }} {{ $currency->sign }}
                                                        @else
                                                            {{ $order->promoskidka }} {{ $currency->sign }}
                                                        @endif  
                                                    </span>
                                                </div>
                                            @endif
                                                <div>
                                                    <b>{{ trans('main.orders.manage.about_payment') }}:</b> 
                                                    <span>
                                                        @if($order->promostr == 'TRY1M4FREE')
                                                            {{ $order->globtotal }} {{ $currency->sign }}
                                                        @else
                                                            {{ $order->get_order_price($order->id) }} {{ $currency->sign }}
                                                        @endif 
                                                    </span>
                                                </div>
                                                @if($order->promostr == 'TRY1M4FREE')
                                                    <span class="">(будет зачисен на счет в личном кабинете TVOYO.TV)</span>
                                                @endif
                                            @if($order->pay_from_score > 0)
                                                <div><b>{{ trans('main.orders.manage.payed_from_score') }}:</b> <span>{{$order->pay_from_score/1}} {{$currency->sign}}</span></div>
                                                <div><b>{{ trans('main.orders.manage.card_payment_data') }}:</b> <span>{{$order->get_order_price($order->id) - $order->pay_from_score}} {{$currency->sign}}</span></div>
                                            @endif
                                        </div>
                                        <div class="clear"></div>
                                        @if($order->user()->count() > 0)
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col l12 m12 s12">
                                                    <a data-id="{{ $order->user()->first()->id }}" class="waves-effect waves-blue btn-flat pull-right show-user-btn"><i class="material-icons" style="margin-right: .5rem;">account_box</i> {{ trans('main.orders.manage.user_details') }}</a>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            @if($order->get_order_price($order->id)-$order->pay_from_score > 0 && $auth_user->hasRole(['superadmin', 'admin']) && $order->pay_type == 0 || $order->pay_type == 1 || $order->pay_type == 10)
                <div>
                    <ul class="collapsible" data-collapsible="expandable">
                        <li class="active">
                            <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                                <h5><i class="material-icons">compare_arrows</i> {{ trans('main.orders.manage.show.bank_interaction') }}</h5>
                            </div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="content-block col l12 m12 s12">
                                       {{--  <div>
                                            {{ $order->pay_type }}
                                            {{ json_encode($paypal_transaction) }}
                                            {{ json_encode($cartu_transaction) }}
                                        </div> --}}
                                        @if($paypal_transaction)
                                            <div class="green accent-1 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem;"><i class="material-icons">check_circle</i>{{ trans('main.orders.manage.transaction_confirmed') }} - <b>{{  $paypal_transaction->created_at }}</b></div>
                                        @elseif($cartu_transaction != null && isset($cartu_transaction->b_trans_id))
                                            
                                            @if($order->transop_date == '0000-00-00 00:00:00')
                                                @php($transop_date=$order->date)
                                            @else
                                                @php($transop_date=$order->transop_date)
                                            @endif
                                            @if($order->promostr == 'TRY1M4FREE')
                                                @php($trans_sum=round($order->globtotal, 2)*100)
                                            @else
                                                @php($trans_sum=round($order->get_order_price($order->id) - $order->pay_from_score, 2)*100)
                                            @endif 
                                            @if(!$order->transop1 && !$order->transop2 && !$order->transop3)
                                                <div class="col l12 m12 s12">
                                                    <div id="trans_op_btn_wrapper">
                                                        <button class="waves-effect waves-light btn teal  m-b-xs" onclick="change_transaction(1, '{{$cartu_transaction->b_trans_id}}', '{{$trans_sum}}', '{{$order->id}}', '{{ $domain }}', '{{ route('orders.changetransaction') }}'); return false;">{{ trans('main.orders.manage.confirm_transaction') }}</button>
                                                        <button class="waves-effect waves-light btn teal  m-b-xs" onclick="change_transaction(2, '{{$cartu_transaction->b_trans_id}}', '{{$trans_sum}}', '{{$order->id}}', '{{ $domain }}', '{{ route('orders.changetransaction') }}'); return false;">{{ trans('main.orders.manage.cancel_transaction') }}</button>
                                                        <button class="waves-effect waves-light btn teal  m-b-xs" onclick="change_transaction(3, '{{$cartu_transaction->b_trans_id}}', '{{$trans_sum}}', '{{$order->id}}', '{{ $domain }}', '{{ route('orders.changetransaction') }}'); return false;">{{ trans('main.orders.manage.return_sum') }}</button>
                                                    </div>
                                                    <div class="green accent-1 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem; display: none;"></div>
                                                </div>
                                            @else
                                                <div class="">
                                                    @if($order->transop1)   
                                                        <div class="green accent-1 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem;"><i class="material-icons">check_circle</i>{{ trans('main.orders.manage.transaction_confirmed') }} - <b>{{$transop_date}}</b></div>
                                                    @elseif($order->transop2)
                                                        <div class="green accent-1 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem;"><i class="material-icons">check_circle</i>{{ trans('main.orders.manage.transaction_canceled') }} - <b>{{$transop_date}}</b></div>
                                                    @elseif($order->transop3)
                                                        <div class="green accent-1 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem;"><i class="material-icons">check_circle</i>{{ trans('main.orders.manage.balance_returned') }} - <b>{{$transop_date}}</b></div>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <div class="orange lighten-5 wrapper-custom-1" style="padding: 15px; border-radius: 2px; margin: .75rem;"><i class="material-icons">warning</i> <b>{{ trans('main.orders.manage.no_transaction_id') }}</b></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif
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
	$(document).ready(function() {
        $('.collapsible').collapsible();
	    $('select').material_select();
	    var datepicker = $('.datepicker').pickadate({
		    selectMonths: true,
		    selectYears: 100,
		    format: 'yyyy-mm-dd',
		});
        
        var birth_date = "{{sprintf('%02d', $order->byear).'-'.sprintf('%02d', $order->bmonth).'-'.sprintf('%02d', $order->bday)}}";
		var picker = datepicker.pickadate('picker');
		picker.set('select', birth_date, { format: 'yyyy-mm-dd' });

		$('label.input-label-1').addClass('active');

		function initMap() {
            var options = {
                types: ['(cities)']
            };
            var input = document.getElementById('city');
            var autocomplete = new google.maps.places.Autocomplete(input, options);

            //    var input2 = document.getElementById('city2');
            //    var autocomplete2 = new google.maps.places.Autocomplete(input2, options);
        }
        initMap();


        var products_datatable = $('#products-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('orders-show-products')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.order_id = {{$order->id}};
                },
            },
            //order: [[0, 'desc']],
            responsive: true,
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"code","data":"code","orderable":true,"searchable":true},
                {"name":"name","data":"name","orderable":true,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":false},
                {"name":"quantity","data":"quantity","orderable":true,"searchable":true},
                {"name":"created_at","data":"created_at","orderable":true,"searchable":true},
            ],
            order: [[ 0, "desc" ]],
            dom: 'Bfrtip',
            buttons:[
                "print","reset","reload"
            ],
            // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            // 'iDisplayLength': 25,
            /*select: {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [2,3,4] }
            ],*/
            drawCallback: function(settings){
                 var api = this.api();

                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
            //'responsive': true,
        });

        products_datatable.on('processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.collapsible-body');
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

        function initDataTableCtrl(container) {
            //$('.multi-select', container).material_select();
            $('.tooltipped').tooltip({delay: 50});
        }

        var url = "{{route('orders-update')}}";
        var form = $('#order-parameters-form');

        save_order(url, $('#order-parameters-form'));

        $(document).off('click', '.show-user-btn').on('click', '.show-user-btn',function(e) {
            e.preventDefault();
            $('.show-user-btn').off('click');
            var data = {'user_id':$(this).data('id')}
            var url = "{{ route('users-show', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });
	});
</script>