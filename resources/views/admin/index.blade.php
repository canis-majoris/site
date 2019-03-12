@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<main class="mn-inner">
<div class="">
    {{-- <div class="row no-m-t no-m-b">
        <div class="col s12 m12 l4">
            <div class="card stats-card">
                <div class="card-content">
                    <span class="card-title">Reports</span>
                    <span class="stats-counter"><span class="counter">23230</span><small>Last week</small></span>
                    <div class="percent-info green-text">8% <i class="material-icons">trending_up</i></div>
                </div>
                <div class="progress stats-card-progress">
                    <div class="determinate" style="width: 70%"></div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row no-m-t no-m-b">
        <div class="col s12 m12 l4 right" style="padding: 0;">
            <div class="col s12 m6 l12">
                <div class="card stats-card">
                    <div class="card-content">
                        <div class="card-options">
                            <ul>
                                <li><a href="{{route('users.index', 'type=all')}}" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom" style="line-height: 22px;"><i class="material-icons">list</i> {{trans('main.misc.show_all')}}</a></li>
                                {{-- <li><a href="javascript:void(0)"><i class="material-icons">more_vert</i></a></li> --}}
                            </ul>
                        </div>
                        <span class="card-title">{{trans('main.breadcrumbs.users')}}</span>
                        <span class="stats-counter"><small style="margin-right: 10px; margin-left: 0;">{{trans('main.misc.in_total')}}</small><span class="counter" id="users_count"></span></span>
                    </div>
                    <div id="sparkline-line"></div>
                </div>
            </div>
            <div class="col s12 m6 l12">
                <div class="card stats-card">
                    <div class="card-content" style="overflow: visible; height: 128px;">
                        <div class="card-options">
                            <ul>
                                <li class="red-text" style="float: right;"><span class="badge cyan lighten-1">{{trans('main.misc.overall_gross')}}</span></li>
                            </ul>
                            <div class="input-field col l8 m10 s12 right" style="padding: 0;">
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
                                {{-- <label>range</label> --}}
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                        <span class="card-title">{{trans('main.misc.sales')}}</span>
                        <span class="stats-counter">{{ $currency->sign }}<span class="counter" id="order_sales"></span><small></small></span>
                    </div>
                    <div id="sparkline-bar"></div>
                </div>
            </div>
        </div>
        <div class="col s12 m12 l8">
            <div class="card visitors-card">
                <div class="card-content">
                    <div class="card-options">
                        {{-- <ul class="col l4 m2 s12">
                            <li><a href="javascript:void(0)" class="card-refresh"><i class="material-icons">refresh</i></a></li>
                        </ul> --}}
                        <ul>
                            <li class="right"><a href="{{route('orders.index')}}" class="btn-flat btn btn-xs waves-effect waves-teal inline-btn-custom" style="line-height: 22px;"><i class="material-icons">list</i> {{trans('main.misc.show_all')}}</a></li>
                            {{-- <li><a href="javascript:void(0)"><i class="material-icons">more_vert</i></a></li> --}}
                        </ul>
                        <div class="input-field col l8 m10 s12 right" style="padding: 0;">
                            <select class="" tabindex="-1" style="width: 50%" id="order_chart_range-select" name="order_chart_range">
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
                            {{-- <label>{{trans('main.misc.date_range')}}</label> --}}
                        </div>
                    </div>
                    <span class="card-title" style="height: 70px;">{{trans('main.misc.orders')}}<span class="secondary-title">{{trans('main.misc.orders_by_date')}}</span></span>
                    <div id="flotchart1"></div>
                </div>
            </div>
        </div>
        
        {{-- <div class="col s12 m12 l4">
            <div class="card server-card">
                <div class="card-content">
                    <div class="card-options">
                        <ul>
                            <li class="red-text"><span class="badge blue-grey lighten-3">optimal</span></li>
                        </ul>
                    </div>
                    <span class="card-title">Server Load</span>
                    <div class="server-load row">
                        <div class="server-stat col s4">
                            <p>167GB</p>
                            <span>Usage</span>
                        </div>
                        <div class="server-stat col s4">
                            <p>320GB</p>
                            <span>Space</span>
                        </div>
                        <div class="server-stat col s4">
                            <p>57.4%</p>
                            <span>CPU</span>
                        </div>
                    </div>
                    <div class="stats-info">
                        <ul>
                            <li>Google Chrome<div class="percent-info green-text right">32% <i class="material-icons">trending_up</i></div></li>
                            <li>Safari<div class="percent-info red-text right">20% <i class="material-icons">trending_down</i></div></li>
                            <li>Mozilla Firefox<div class="percent-info green-text right">18% <i class="material-icons">trending_up</i></div></li>
                        </ul>
                    </div>
                    <div id="flotchart2"></div>
                </div>
            </div>
        </div> --}}
    </div>
</main>
<script src="{{ URL::asset('admin_assets/plugins/masonry/masonry.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/miscellaneous-masonry.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/waypoints/jquery.waypoints.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/counter-up-master/jquery.counterup.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/chart.js/chart.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/flot/jquery.flot.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/flot/jquery.flot.time.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/flot/jquery.flot.symbol.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/curvedlines/curvedLines.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/peity/jquery.peity.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/dashboard.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    url_orders = "{{route('orders.get.statistics.month_chart')}}";
    url_users = "{{route('users.get.statistics.chart')}}";
    url_orders_chart = "{{route('orders.get.statistics.chart')}}";
    url_sales= "{{route('orders.get.statistics.sales')}}";
    DrawSparkline(url_users, $('#sparkline-line'), {dsds:'sds'});

    

    

    $(document).ready(function (e) {
        flot1(url_orders_chart, $('#flotchart1'), {number:1, range:'month'});
        order_sales(url_sales, $('#order_sales'), {number:1, range:'month'});

        setTimeout(function(){ 
            $('.counter').each(function () {
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
        }, 300);
        
    })

    $(document).on('change', '#order_chart_range-select', function (e) {
        var range = $(this).val();
        var range_val = $(this).find(":selected").data('range');
        flot1(url_orders_chart, $('#flotchart1'), {number:range_val, range:range});
    });

    $(document).on('change', '#sales_range-select', function (e) {
        var range = $(this).val();
        var range_val = $(this).find(":selected").data('range');
        order_sales(url_sales, $('#order_sales'), {number:range_val, range:range});
    })
    
</script>

@stop