@extends('admin.layout.main')

@section('title', 'Admin - Home')

@section('content')
<head>
</head>
<main class="mn-inner">
    <!-- <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l6 m6 s12">
                                    <div class=" bred-c-holder-1" >
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('charts.index')}}" style="font-size:25px;">{{trans('dashboard.sidebar.list.charts')}}</a>
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
    </div> -->
    <div class="middle-content">
        <div class="row no-m-t no-m-b" style="padding-top: 1rem;">
            <div class="col s12 m12 l4">
                <div class="card stats-card custom-color-3 colored-card-1 waves-effect waves-light" id="users_inline_wrapper">
                    <div class="card-content">
                        <div class="card-options">
                            <ul>
                                <li><a href="{{route('users.index', 'type=all')}}" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom" style="line-height: 22px;"><i class="material-icons">list</i> {{trans('main.misc.show_all')}}</a></li>
                                {{-- <li><a href="javascript:void(0)"><i class="material-icons">more_vert</i></a></li> --}}
                            </ul>
                        </div>
                        <span class="card-title">{{ trans('main.charts.users') }}</span>
                        <span class="stats-counter"><small style="margin-right: 10px; margin-left: 0;">{{trans('main.misc.in_total')}}</small><span class="counter" id="users_count"></span></span>
                    </div>
                    <div class="small-card-inline-chart">
                        <center>
                            <div id="active_users_inline">
                                
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l4">
                <div class="card stats-card custom-color-4 colored-card-1 waves-effect waves-light" id="orders_inline_wrapper">
                    <div class="card-content">
                        <div class="card-options">
                            <ul>
                                <li><a href="{{route('orders.index')}}" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom" style="line-height: 22px;"><i class="material-icons">list</i> {{trans('main.misc.show_all')}}</a></li>
                                {{-- <li><a href="javascript:void(0)"><i class="material-icons">more_vert</i></a></li> --}}
                            </ul>
                        </div>
                        <span class="card-title">{{ trans('main.breadcrumbs.orders') }}</span>
                        <span class="stats-counter"><small style="margin-right: 10px; margin-left: 0;">{{trans('main.misc.in_total')}}</small><span class="counter" id="orders_count"></span></span>
                    </div>
                    <div class="small-card-inline-chart">
                        <center>
                            <div id="orders_inline">
                                
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col s12 m6 l4">
                <div class="card stats-card custom-color-5 colored-card-1 waves-effect waves-light" id="stbs_inline_wrapper">
                    <div class="card-content">
                        <div class="card-options">
                            <ul>
                                <li><a href="{{route('orders_products.index', 'type=stbs')}}" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom" style="line-height: 22px;"><i class="material-icons">list</i> {{trans('main.misc.show_all')}}</a></li>
                                {{-- <li><a href="javascript:void(0)"><i class="material-icons">more_vert</i></a></li> --}}
                            </ul>
                        </div>
                        <span class="card-title">{{ trans('main.charts.stbs') }}</span>
                        <span class="stats-counter"><small style="margin-right: 10px; margin-left: 0;">{{trans('main.misc.in_total')}}</small><span class="counter" id="stbs_count"></span></span>
                    </div>
                    <div class="small-card-inline-chart">
                        <center>
                            <div id="stbs_inline">
                                
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="col s12 m12 l4">
                <div class="card charts-card" id="users_chart_wrapper">
                    <div class="card-content">
                        <div class="row" style="margin-top: 0;"> 
                            <h6>{{ trans('main.charts.users') }}</h6>
                        </div>
                        <div class="row" style="min-height: 80px; margin-bottom: 0;">
                            <div class="col l12 m12 s12" style="padding-left: 0;">
                                <div class="switch m-b-md tooltipped" data-position="top" data-delay="50" data-tooltip="Display format" style="position:relative; margin-top: 1.5rem;">
                                    <label>
                                        <input type="checkbox" name="display_format_users" id="display_format_users">
                                        <span class="lever"></span>
                                        {{ trans('main.charts.by_region') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <center>
                                <div id="all_users">
                                    
                                </div>
                            </center>
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <a href="{{route('users.index', 'type=all')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> {{ trans('main.misc.show_all') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col s12 m12 l8">
                <div class="card charts-card" id="new_users_chart_wrapper">
                    <div class="card-content">
                        <div class="row" style="margin-top: 0;"> 
                            <h6>{{ trans('main.charts.new_users') }}</h6>
                        </div>
                        <div class="row" style="min-height: 80px; margin-bottom: 0;">
                            <div class="stats-counter col l3 m2 s12 right right-align"><small style="margin-right: 10px; margin-left: 0;">{{trans('main.misc.in_total')}}</small><span class="counter" id="new_users_count"></span></div>
                            <div class="left" style="padding-left: 0; padding-right: 2rem;">
                                <div class="switch m-b-md tooltipped" data-position="top" data-delay="50" data-tooltip="Display format" style="position:relative; margin-top: 1.5rem;">
                                    <label>
                                        <input type="checkbox" name="display_format_new_users" id="display_format_new_users">
                                        <span class="lever"></span>
                                        {{ trans('main.charts.by_region') }}
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="new_users_range-select" name="new_users_range">
                                    <option data-range="1" value="full">{{trans('main.misc.all')}}</option>
                                    <option data-range="5" value="year">5 {{trans('main.misc.years')}}</option>
                                    <option data-range="2" value="year">2 {{trans('main.misc.years')}}</option>
                                    <option data-range="1" value="year">1 {{trans('main.misc.year')}}</option>
                                    <option data-range="6" value="month">6 {{trans('main.misc.months')}}</option>
                                    <option data-range="3" value="month">3 {{trans('main.misc.months')}}</option>
                                    <option data-range="1" value="month" selected>1 {{trans('main.misc.month')}}</option>
                                    <option data-range="1" value="week">1 {{trans('main.misc.week')}}</option>
                                    <option data-range="1" value="day">1 {{trans('main.misc.day')}}</option>
                                </select>
                                <label>{{ trans('main.charts.date_range') }}</label>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="new_users_display_type-select" name="new_users_display_type">
                                    <option value="areaspline">{{ trans('main.charts.chart_display_format_types.area') }}</option>
                                    <option value="spline">{{ trans('main.charts.chart_display_format_types.line') }}</option>
                                    <option value="bar" selected>{{ trans('main.charts.chart_display_format_types.bar') }}</option>
                                    <option value="column">{{ trans('main.charts.chart_display_format_types.column') }}</option>
                                </select>
                                <label>{{ trans('main.charts.chart_display_format') }}</label>
                            </div>
                            <div class="input-field col l2 m2 s12">
                                <select multiple name="new_users_activity[]" id="new_users_activity-select">
                                  <option value="any" disabled selected>{{trans('main.users.table.filter.status.default')}}</option>
                                  <option value="0">{{ trans('main.charts.Inactive') }}</option>
                                  <option value="1">{{ trans('main.charts.Active mobile services, no active STB services') }}</option>
                                  <option value="2">{{ trans('main.charts.Active STB services, no active mobile services') }}</option>
                                  <option value="3">{{ trans('main.charts.Active STB services as well as mobile services') }}</option>
                                  <option value="4">{{ trans('main.charts.Active STB services with multiroom, no active mobile services') }}</option>
                                  <option value="5">{{ trans('main.charts.Active STB services with multiroom as well as mobile services') }}</option>
                                      </select>
                                <label>{{ trans('main.misc.activity') }}</label>
                            </div>

                        </div>
                        <div class="row">
                            {{-- <span class="card-title">{{trans('main.misc.sales')}}</span>   --}}  
                            <div>
                                <center>
                                    <div id="new_users_chart">
                                        
                                    </div>
                                </center>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <a href="{{route('users.index', 'type=all')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> {{ trans('main.misc.show_all') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l4">
                <div class="card charts-card" id="stbs_chart_wrapper">
                    <div class="card-content">
                        <div class="row" style="margin-top: 0;"> 
                            <h6>{{ trans('main.charts.stbs') }}</h6>
                        </div>
                        <div class="row" style="min-height: 80px; margin-bottom: 0;">
                            <div class="col l12 m12 s12" style="padding-left: 0;">
                                <div class="switch m-b-md tooltipped" data-position="top" data-delay="50" data-tooltip="Display format" style="position:relative; margin-top: 1.5rem;">
                                    <label>
                                        <input type="checkbox" name="display_format_stbs" id="display_format_stbs">
                                        <span class="lever"></span>
                                        {{ trans('main.charts.by_region') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <center>
                                <div id="all_stbs">
                                    
                                </div>
                            </center>
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <a href="{{route('orders_products.index', 'type=stbs')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> {{ trans('main.misc.show_all') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l8">
                <div class="card charts-card" id="orders_product_chart_wrapper">
                    <div class="card-content">
                        <div class="row" style="margin-top: 0;"> 
                            <h6>{{ trans('main.charts.services') }}</h6>
                        </div>
                        <div class="row" style="min-height: 80px; margin-bottom: 0;">
                            <div class="left" style="padding-left: 0; padding-right: 2rem;">
                                <div class="switch m-b-md tooltipped" data-position="top" data-delay="50" data-tooltip="Display format" style="position:relative; margin-top: 0.75rem;">
                                    <label>
                                        <input type="checkbox" name="display_format_orders_products" id="display_format_orders_products">
                                        <span class="lever"></span>
                                        {{ trans('main.charts.by_region') }}
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col l2 m3 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="op_display_type-select" name="op_display_type">
                                    <option value="column" selected>{{ trans('main.charts.chart_display_format_types.column') }}</option>
                                    <option value="bar">{{ trans('main.charts.chart_display_format_types.bar') }}</option>
                                </select>
                                <label>{{ trans('main.charts.chart_display_format') }}</label>
                            </div>
                        </div>
                        <div class="row">
                            <div>
                                <center>
                                    <div id="orders_products">
                                        
                                    </div>
                                </center>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <a href="{{route('orders_products.index', 'type=services')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> {{ trans('main.misc.show_all') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card charts-card" id="orders_chart_wrapper">
                    <div class="card-content">
                        <div class="row" style="margin-top: 0;"> 
                            <h6>{{ trans('main.charts.orders') }}</h6>
                        </div>
                        <div class="row" style="min-height: 80px; margin-bottom: 0;">
                            <div class="stats-counter col l5 m3 s12 right right-align" id="filter_orders_count">
                                
                            </div>
                            <div class="left" style="padding-left: 0; padding-right: 2rem;">
                                <div class="switch m-b-md tooltipped" data-position="top" data-delay="50" data-tooltip="Display format" style="position:relative; margin-top: 1.5rem;">
                                    <label>
                                        <input type="checkbox" name="display_format_orders" id="display_format_orders">
                                        <span class="lever"></span>
                                        {{ trans('main.charts.by_region') }}
                                    </label>
                                </div>
                            </div>
                            <div class="input-field col l1 m2 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="sales_range-select" name="sales_range">
                                    <option data-range="1" value="full">{{trans('main.misc.all')}}</option>
                                    <option data-range="5" value="year">5 {{trans('main.misc.years')}}</option>
                                    <option data-range="2" value="year">2 {{trans('main.misc.years')}}</option>
                                    <option data-range="1" value="year">1 {{trans('main.misc.year')}}</option>
                                    <option data-range="6" value="month">6 {{trans('main.misc.months')}}</option>
                                    <option data-range="3" value="month">3 {{trans('main.misc.months')}}</option>
                                    <option data-range="1" value="month" selected>1 {{trans('main.misc.month')}}</option>
                                    <option data-range="1" value="week">1 {{trans('main.misc.week')}}</option>
                                    <option data-range="1" value="day">1 {{trans('main.misc.day')}}</option>
                                </select>
                                <label>{{ trans('main.charts.date_range') }}</label>
                            </div>
                            <div class="input-field col l1 m2 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="display_type-select" name="display_type">
                                    <option value="areaspline" selected>{{ trans('main.charts.chart_display_format_types.area') }}</option>
                                    <option value="spline">{{ trans('main.charts.chart_display_format_types.line') }}</option>
                                    <option value="bar">{{ trans('main.charts.chart_display_format_types.bar') }}</option>
                                    <option value="column">{{ trans('main.charts.chart_display_format_types.column') }}</option>
                                </select>
                                <label>{{ trans('main.charts.chart_display_format') }}</label>
                            </div>
                            <div class="input-field col l1 m2 s12">
                                <select class="" tabindex="-1" style="width: 50%" id="orders_by_revenue-select" name="count_type">
                                    <option value="0">amount</option>
                                    <option value="1">revenue</option>
                                </select>
                                <label>Count by</label>
                            </div>
                        </div>
                        <div class="row">
                            {{-- <span class="card-title">{{trans('main.misc.sales')}}</span>   --}}  
                            <div>
                                <center>
                                    <div id="orders_chart">
                                        
                                    </div>
                                </center>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <a href="{{route('orders.index')}}" target="_blank" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom right" style="line-height: 22px;"><i class="material-icons">list</i> {{ trans('main.misc.show_all') }}</a>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="inner-sidebar">
        <div class="scrollable-1">
            <span class="inner-sidebar-title">{{trans('main.misc.active_users')}}</span>
            <div class="message-list"></div>
            <!-- <span class="inner-sidebar-title">Events</span>
            <span class="info-item">Envato meeting<span class="new badge">12</span></span>
            <div class="inner-sidebar-divider"></div>
            <span class="info-item">Google I/O</span>
            <div class="inner-sidebar-divider"></div>
            <span class="info-item disabled">No more events scheduled</span>
            <div class="inner-sidebar-divider"></div> -->
            <span class="inner-sidebar-title">{{trans('dashboard.sidebar.list.orders.transactions')}} <i class="material-icons">compare_arrows</i></span>
            <div class="transactions-list"></div>
        </div>
    </div>
    
    {{-- <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light red menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add delivery" style="font-size: 30px;">playlist_add</i></a>
    </div> --}}
</main>



<!-- Manage delivery -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>
<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/charts.js') }}"></script>

<script type="text/javascript">

    var current_processed_area = '';

    var orders_render = {'name': 'orders_by_region', 'options': orders_by_region_options, 'multiple': 0, 'type': 'areaspline', 'number':1, 'range':'month', 'by_revenue': 0, 'by_region': 0, 'container': '#orders_chart_wrapper .counter'};
    var orders_all_render = {'name': 'orders', 'options': orders_options, 'multiple': 0, 'type': 'areaspline', 'number':1, 'range':'month', 'by_revenue': 0, 'container': '#orders_chart_wrapper .counter'};
    var all_users_render = {'name': 'all_users', 'options': all_users_options, 'multiple': 0, 'type': 'pie', 'number':1, 'range':'month', 'by_region': 0, 'container': '#users_chart_wrapper .counter'};
    var orders_products_by_region = {'name': 'orders_products_by_region', 'options': orders_products_by_region, 'multiple': 1, 'type': 'column', 'number':1, 'range':'month', 'by_region': 0, 'container': '#orders_products_chart_wrapper .counter'};
    var new_users_render = {'name': 'new_users', 'options': new_users_options, 'multiple': 0, 'type': 'bar', 'number':1, 'range':'month', 'only_active': 0, 'by_region': 0, 'container': '#new_users_chart_wrapper .counter'};
    var all_stbs_render = {'name': 'all_stbs', 'options': all_stbs_options, 'multiple': 0, 'type': 'pie', 'number':1, 'range':'month', 'by_region': 0, 'container': '#stbs_chart_wrapper .counter'};

    var active_users_cumulative = {'name': 'active_users_cumulative', 'options': inline_chart_options, 'multiple': 0, 'type': 'column', 'number':1, 'range':'month', 'by_region': 0, 'container': '#users_inline_wrapper .counter'};

    var orders_cumulative = {'name': 'orders_cumulative', 'options': inline_chart_options, 'multiple': 0, 'type': 'column', 'number':1, 'range':'month', 'by_region': 0, 'container': '#orders_inline_wrapper .counter'};

    var stbs_cumulative = {'name': 'stbs_cumulative', 'options': inline_chart_options, 'multiple': 0, 'type': 'column', 'number':1, 'range':'month', 'by_region': 0, 'container': '#stbs_inline_wrapper .counter'};


    var curr_orders_arr = orders_render;
    var curr_users_arr = all_users_render;
    var curr_orders_products_arr = orders_products_by_region;
    var curr_new_users_arr = new_users_render;
    var curr_all_stbs_arr = all_stbs_render;

    var curr_active_users_cumulative = active_users_cumulative;
    var curr_orders_cumulative = orders_cumulative;
    var curr_stbs_cumulative = stbs_cumulative;

    var load_orders = function() {
        load_chart("{{ route('charts.show') }}", curr_orders_arr);
    }

    var load_users = function() {
        load_chart("{{ route('charts.show') }}", curr_users_arr);
    }

    var load_orders_products = function() {
        load_chart("{{ route('charts.show') }}", curr_orders_products_arr);
    }

    var load_new_users = function() {
        load_chart("{{ route('charts.show') }}", curr_new_users_arr);
    }

    var load_stbs = function() {
        load_chart("{{ route('charts.show') }}", curr_all_stbs_arr);
    }

    var load_active_users_cumulative = function() {
        load_chart("{{ route('charts.show') }}", curr_active_users_cumulative);
    }

    var load_orders_cumulative = function() {
        load_chart("{{ route('charts.show') }}", curr_orders_cumulative);
    }

    var load_stbs_cumulative = function() {
        load_chart("{{ route('charts.show') }}", curr_stbs_cumulative);
    }

    $(document).ready(function(e) {

        // $(".scrollable-1").mCustomScrollbar({
        //     scrollInertia: 100,
        //     //mouseWheelPixels: 30,
        //    // autoDraggerLength:false,
        // });
        // 

        load_orders();
        load_users();
        load_orders_products();
        load_new_users();
        load_stbs();
        load_active_users_cumulative();
        load_orders_cumulative();
        load_stbs_cumulative();

        var admins_url = "{{ route('home.get_active_admins_list') }}",
            transactions_url = "{{ route('home.get_transactions_list') }}";

        manage_load(admins_url, $('.message-list'), {'activity': 1}, 100);
        manage_load(transactions_url, $('.transactions-list'), {'cartu': true, 'paypal': true}, 100);

        setInterval(function(e) {
            manage_load(admins_url, $('.message-list'), {'activity': 1}, 100);
        }, 60000);

        setInterval(function(e) {
            manage_load(transactions_url, $('.transactions-list'), {'cartu': true, 'paypal': true}, 100);
            load_orders();
            load_users();
            load_orders_products();
            load_new_users();
            load_stbs();
        }, 36000000);


    });

    $(document).ajaxStop(function() {
        setTimeout(function(){ 
            $(current_processed_area.slice(0,-1)).each(function () {
                $(this).prop('Counter',0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                        $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                    }
                });
            });

            current_processed_area = '';
        }, 0);
    });

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

    $('label.input-label-1').addClass('active');

    // $(document).on('change', '#order_chart_range-select', function (e) {
    //     var range = $(this).val();
    //     var range_val = $(this).find(":selected").data('range');
    //     flot1(url_orders_chart, $('#flotchart1'), {number:range_val, range:range});
    // });

    $(document).on('change', '#sales_range-select', function (e) {
        var range = $(this).val();
        var range_val = $(this).find(":selected").data('range');
        curr_orders_arr['number'] = range_val;
        curr_orders_arr['range'] = range;
        load_orders();
        e.preventDefault();
    });

    $(document).on('change', '#new_users_range-select', function (e) {
        var range = $(this).val();
        var range_val = $(this).find(":selected").data('range');
        curr_new_users_arr['number'] = range_val;
        curr_new_users_arr['range'] = range;
        load_new_users();
        e.preventDefault();
    });

    

    $(document).on('change', '#display_type-select', function (e) {
        var type = $(this).val();
        curr_orders_arr['type'] = type;
        load_orders();
        e.preventDefault();
    });

    $(document).on('change', '#new_users_display_type-select', function (e) {
        var type = $(this).val();
        curr_new_users_arr['type'] = type;
        load_new_users();
        e.preventDefault();
    });

    $(document).on('change', '#op_display_type-select', function(e) {
        var type = $(this).val();
        orders_products_by_region['type'] = type;
        load_orders_products();
        e.preventDefault();
    });

    $(document).on('change', '#orders_by_revenue-select', function(e) {
        e.preventDefault();
        var val = $(this).val();
        curr_orders_arr['by_revenue'] = val;
        load_orders();
    });

    $(document).on('change', '#new_users_activity-select', function(e) {
        e.preventDefault();
        var val = $(this).val();
        curr_new_users_arr['only_active'] = val;
        load_new_users();
    });

    

    $(document).on('change', '#display_format_orders', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
           // curr_orders_arr['name'] = 'orders_by_region';
           // curr_orders_arr['options'] = orders_by_region_options;
            //$('#orders_by_revenue-select').prop('disabled', false).material_select();
            curr_orders_arr['multiple'] = 1;
            curr_orders_arr['by_region'] = 1;
            load_orders();
        } else {
           // curr_orders_arr['name'] = 'orders';
           // curr_orders_arr['options'] = orders_options;
            //$('#orders_by_revenue-select').prop('disabled', true).material_select();
            curr_orders_arr['multiple'] = 0;
            curr_orders_arr['by_region'] = 0;
            load_orders();
        }
    });

    $(document).on('change', '#display_format_new_users', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
           // curr_orders_arr['name'] = 'orders_by_region';
           // curr_orders_arr['options'] = orders_by_region_options;
            curr_new_users_arr['multiple'] = 1;
            curr_new_users_arr['by_region'] = 1;
            load_new_users();
        } else {
           // curr_orders_arr['name'] = 'orders';
           // curr_orders_arr['options'] = orders_options;
            curr_new_users_arr['multiple'] = 0;
            curr_new_users_arr['by_region'] = 0;
            load_new_users();
        }
    });


    $(document).on('change', '#display_format_users', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            curr_users_arr['name'] = 'users_by_region';
           // curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_users();
        } else {
            curr_users_arr['name'] = 'all_users';
            //curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_users();
        }
    });

    $(document).on('change', '#display_format_orders_products', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            orders_products_by_region['by_region'] = 1;
           // curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_orders_products();
        } else {
            orders_products_by_region['by_region'] = 0;
            //curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_orders_products();
        }
    });

    $(document).on('change', '#display_format_stbs', function(e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            curr_all_stbs_arr['name'] = 'stbs_by_region';
           // curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_stbs();
        } else {
            curr_all_stbs_arr['name'] = 'all_stbs';
            //curr_users_arr['options'] = all_users_render;
            //curr_users_arr['multiple'] = 0;
            load_stbs();
        }
    });

    

    

    

</script>
@stop
