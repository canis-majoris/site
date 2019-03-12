<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<div class="">
    <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l6 m6 s10 hide-on-med-and-down">
                                    <div class=" bred-c-holder-1" >
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users.index', 'type=all')}}" >{{trans('main.breadcrumbs.users')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('users-show', $user->id)}}" style="font-size:25px;">{{$user->code}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            <ul class="tabs z-depth-1 profile-tabs">
                                                <li class="tab"><a href="#products_holder" class="active">{{ trans('main.misc.products') }}</a></li>
                                                <li class="tab" id="profile_holder_tab"><a href="#profile_holder" class="">{{ trans('main.misc.profile') }}</a></li>
                                            </ul>
                                        </div>
                                        <div class="clear"></div>
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
    <div class="row no-m-t no-m-b">
        <div class="side-holder-next closed-1" id="products_holder">
            @if($user->check_orders()->count() > 0)
            <div class="col s12 m4 l3 left-fixed-small-1 side-slidabl-wrapper closed-1" style="bottom: -15px;">
                <div class="side-slidable-left-1-top">
                    <button class="btn btn-floating side-slidable-toggle-btn tooltipped" data-position="right" data-delay="50" data-tooltip="Orders"><i class="material-icons">keyboard_arrow_left</i></button>
                </div>
                <div class="mailbox-list mCustomScrollbar scrollable-1 white z-depth-1">
                    <ul class="collapsible" data-collapsible="expandable">
                        <li class="active">
                            <div class="collapsible-header active" style="padding: 0; margin:0 10px 0 15px;">
                                <h5><i class="material-icons">shopping_cart</i> {{ trans('main.users.containers.header.orders') }}</h5>
                            </div>
                            <div class="collapsible-body">
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col s12">
                                        <div class="white">
                                            @include('users.orders')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
            <div class="col s12 m12 l12 right side-content-wrapper-1">
            {{-- @if($user->check_orders()->count() > 0)
                <div>
                    <ul class="collapsible" data-collapsible="expandable">
                        <li class="active">
                            <div class="collapsible-header" style="padding: 0.5rem 1rem;">
                                <h5><i class="material-icons">shopping_cart</i> {{ trans('main.users.containers.header.orders') }}</h5>
                            </div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col s12">
                                        <div class="white">
                                            @include('users.orders')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @endif --}}
            {{-- STBS --}}
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">donut_large</i> {{ trans('main.users.containers.header.stbs') }}</h5>
                        </div>
                        <div class="collapsible-body table-holder-narrow">
                            <div class="row" style="margin-bottom: 0;">
                                <div class="">
                                    <div class="white">
                                        @include('users.products.stbs')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            {{-- SERVICES --}}
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">donut_large</i> {{ trans('main.users.containers.header.services') }}</h5>
                        </div>
                        <div class="collapsible-body table-holder-narrow">
                            <div class="row" style="margin-bottom: 0;">
                                <div class="">
                                    <div class="white">
                                        @include('users.products.services')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            {{-- MOBILE SERVICES --}}
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">donut_large</i> {{ trans('main.users.containers.header.mobile_services') }}</h5>
                        </div>
                        <div class="collapsible-body table-holder-narrow">
                            <div class="row" style="margin-bottom: 0;">
                                <div class="">
                                    <div class="white">
                                        @include('users.products.mobile_services')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            {{-- GOODS --}}
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">donut_large</i> {{ trans('main.users.containers.header.goods') }}</h5>
                        </div>
                        <div class="collapsible-body table-holder-narrow">
                            <div class="row" style="margin-bottom: 0;">
                                <div class="">
                                    <div class="white">
                                        @include('users.products.goods')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            </div>
        </div>
        <div class="col l12 m12 s12 user_parameters-holder side-holder" data-collapse-s="100%" data-collapse-m="33%" data-collapse-l="25%" id="profile_holder">
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">account_box</i> {{ trans('main.users.containers.header.profile') }}</h5>
                        </div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="col l12 m12 s12">
                                    @if($user->is_diller == 1)
                                    <div class="col l12 m12 s12">
                                        <button class="btn-flat show-user-statistics-btn custom-button-flat-1 waves-effect waves-teal pull-right grey-text text-darken-1" data-id="{{ $user->id }}" style="margin-bottom: 10px;"><i class="material-icons" style="font-size:20px;">insert_chart</i> {{ trans('main.misc.show_statistics') }}</button>
                                        <button class="btn-flat show-user-balance-btn custom-button-flat-1 waves-effect waves-teal pull-right grey-text text-darken-1" data-id="{{ $user->id }}" style="margin-bottom: 10px;"><i class="material-icons" style="font-size:20px;">account_balance_wallet</i> {{ trans('main.misc.show_balance') }}</button>
                                        <div class="clear"></div>
                                    </div>
                                    @endif
                                </div>
                                <div class="content-block col l12 m12 s12">
                                    <form id="user-parameters-form">
                                        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                                        <div class="grid">
                                            <div class="col l4 m6 s12 grid-item">
                                                <div class="row grey lighten-4">
                                                    <div class="col s12">
                                                        <h5><span class="card-title">{{ trans('main.users.manage.form.headers.personal_info') }}</span></h5>
                                                    </div>
                                                    <div class="clear"> </div>
                                                    <div class="input-field row">
                                                        <div class="col s12">{{ trans('main.users.manage.form.gender.header') }}</div>
                                                        <div class="col l4 m6 s12">
                                                            <input name="gender" type="radio" id="is_male" value="male" @if($user->gender == 'male') checked @endif>
                                                            <label for="is_male">{{ trans('main.users.manage.form.gender.male') }}</label>
                                                        </div>
                                                        <div class="col l4 m6 s12">
                                                            <input name="gender" type="radio" id="is_female" value="female" @if($user->gender == 'female') checked @endif>
                                                            <label for="is_female">{{ trans('main.users.manage.form.gender.female') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input type="text" class="validate" name="name" id="name" value="{{$user->name}}">
                                                        <label for="name" class="input-label-1">{{ trans('main.users.manage.form.name') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="birth_date" type="date" class="datepicker" value="" name="bith_date">
                                                        <label for="birth_date" class="input-label-1">{{ trans('main.users.manage.form.birth_date') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="email" type="email" class="validate" value="{{$user->email}}" name="email">
                                                        <label for="email" class="input-label-1">{{ trans('main.users.manage.form.email') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col l4 m6 s12 grid-item">
                                                <div class="row grey lighten-4">
                                                    <div class="col s12">
                                                        <h5><span class="card-title">Account</span></h5>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="code" type="text" class="validate" value="{{$user->code}}" name="code" disabled>
                                                        <label for="code" class="input-label-1">{{ trans('main.users.manage.form.code') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="created_at" type="text" class="validate" value="{{$user->created_at}}" name="created_at">
                                                        <label for="created_at" class="input-label-1">{{ trans('main.users.manage.form.reg_date') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="username" type="text" class="validate" value="{{$user->username}}" name="username">
                                                        <label for="username" class="input-label-1">{{ trans('main.users.manage.form.username') }}</label>
                                                    </div>
                                                    {{-- <div class="input-field col s12">
                                                        <input id="active_service" type="text" class="validate" value="{{$user->active_service}}" name="active_service">
                                                        <label for="active_service" class="input-label-1">active service</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                            <div class="col l4 m6 s12 grid-item">
                                                <div class="row grey lighten-4">
                                                    <div class="col s12">
                                                        <h5><span class="card-title">{{ trans('main.users.manage.form.headers.address') }}</span></h5>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input type="text" class="validate" name="flat" id="flat" value="{{$user->flat}}">
                                                        <label for="flat" class="input-label-1">{{ trans('main.users.manage.form.address') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="region" type="text" class="validate" name="region" value="{{$user->region}}">
                                                        <label for="region" class="input-label-1">{{ trans('main.users.manage.form.region') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="postcode" type="text" class="validate" name="postcode" value="{{$user->postcode}}">
                                                        <label for="postcode" class="input-label-1">{{ trans('main.users.manage.form.postcode') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <select class="" style="width: 100%" name="country">
                                                            <option value="">-</option>
                                                            @foreach($countries as $country)
                                                                <option value="{{$country->id}}" @if($country->countryName == $user->country) selected @endif>{{$country->countryName}}</option>
                                                            @endforeach
                                                        </select>
                                                        <label>{{ trans('main.users.manage.form.country') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="city" type="text" class="validate" name="city" value="{{$user->city}}">
                                                        <label for="city" class="input-label-1">{{ trans('main.users.manage.form.city') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col l4 m6 s12 grid-item">
                                                <div class="row grey lighten-4">
                                                    <div class="col s12">
                                                        <h5><span class="card-title">{{ trans('main.users.manage.form.headers.contact_info') }}</span></h5>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input type="text" class="validate" name="phone" id="phone" value="{{$user->phone}}">
                                                        <label for="phone" class="input-label-1">{{ trans('main.users.manage.form.phone') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="mobile" type="text" class="validate" name="mobile" value="{{$user->mobile}}">
                                                        <label for="mobile" class="input-label-1">{{ trans('main.users.manage.form.mobile') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="password" type="password" class="validate" name="password" value="">
                                                        <label for="password" class="input-label-1">{{ trans('main.users.manage.form.password') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="password_confirmation" type="password" class="validate" name="password_confirmation" value="">
                                                        <label for="password_confirmation" class="input-label-1">{{ trans('main.users.manage.form.repeat_password') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <span class="input-wrapper-span-1">
                                                            <input type="checkbox" id="activated" name="activated" @if($user->activated==1)checked="checked"@endif>
                                                            <label for="activated" style="left:0;">{{ trans('main.users.manage.form.activated') }}</label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col l4 m6 s12 grid-item">
                                                <div class="row grey lighten-4">
                                                    <div class="col s12">
                                                        <h5><span class="card-title">{{ trans('main.users.manage.form.headers.dealer_settings') }}</span></h5>
                                                    </div>
                                                    <div class="input-field col s12 row">
                                                        <span class="input-wrapper-span-1">
                                                            <input type="checkbox" id="is_diller" name="is_diller" @if($user->is_diller==1)checked="checked"@endif>
                                                            <label for="is_diller" style="left:0;">{{ trans('main.users.manage.form.is_dealer') }}</label>
                                                        </span>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="percent" type="text" class="validate" name="percent" value="@if($user->dealer){{$user->dealer->percent}}@endif">
                                                        <label for="percent" class="input-label-1">{{ trans('main.users.manage.form.percent') }}</label>
                                                    </div>
                                                    <div class="input-field col s12">
                                                        <input id="percent_first" type="text" class="validate" name="percent_first" value="@if($user->dealer){{$user->dealer->percent_first}}@endif"> 
                                                        <label for="percent_first" class="input-label-1">{{ trans('main.users.manage.form.percent_first') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col s12">
                                                <button class="waves-effect waves-light btn teal pull-right" type="submit" data-reset="{{ trans('main.buttons.save') }}" id="save-user-parameters-btn">{{ trans('main.buttons.save') }}</button>
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
    </div>
</div>

<!-- Manage Administrator -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>

<div id="comment_modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>comment</h4>
        <form id="comment_form">
            <input type="hidden" name="op_id" value="" id="op_id">
            <div class="input-field col s12">
                <textarea id="comment_text" class="materialize-textarea" name="comment_text"></textarea>
                <label for="comment_text" style="left: 0;">admin comment</label>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" id="save_comment-btn" data-reset="save" >save</button>
    </div>
</div>
<!-- <script src="{{ URL::asset('admin_assets/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script> -->
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script src="{{ URL::asset('admin_assets/js/unit_converter.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/masonry/masonry.pkgd.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/miscellaneous-masonry.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    var global_width;
    var orders_filtered = null;
	$(document).ready(function() {
        $('.collapsible').collapsible();
        $('ul.tabs').tabs({'swipeable':false});
        $('.tooltipped').tooltip({delay: 50});
        $(".scrollable-1").mCustomScrollbar();

        $grid = $('.grid');
        $grid.masonry({
            // options
            itemSelector: '.grid-item',
            columnWidth: '.grid-item',
            percentPosition: true,
            gutter: 0
        });

        $grid.on( 'layoutComplete', function( event, items ) {
          
        });

        $(document).off('click', '#profile_holder_tab, .collapsible-header').on('click', '#profile_holder_tab, .collapsible-header', function(e) {
            $grid.masonry()
        });

        /*$(window).trigger('resize');

        $grid.masonry()*/


       //$('ul.profile-tabs .tab').first().click();

        window.LaravelDataTables = window.LaravelDataTables || {};
        var stbs_datatable = $('#stbs-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('users-show-product-stbs')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.user_id = {{$user->id}};
                    d.orders_filtered = orders_filtered;
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"name","data":"color","orderable":false,"searchable":true},
                {"name":"mac","data":"name","orderable":false,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":true},
                {"name":"code","data":"service","orderable":false,"searchable":true},
                {"name":"control","data":"control","orderable":false,"searchable":false},
                {"name":"comment","data":"comment","orderable":false,"searchable":false},
                {"name":"action","data":"action","orderable":false,"searchable":false},
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBfrtip',
            buttons: ["print","reset","reload",
                {
                    text: '<i class="material-icons dp48" style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }} {{ trans('main.orders_products.manage.stb') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        add_product('stb');
                    }
                },
            ],
            drawCallback: function(settings){
                
                 var api = this.api();  
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
            'responsive': true,
        });

        var services_datatable = $('#services-datatable').DataTable( {
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('users-show-product-services')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.user_id = {{$user->id}};
                    d.orders_filtered = orders_filtered;
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"name","data":"color","orderable":false,"searchable":true},
                {"name":"code","data":"name","orderable":false,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":true},
                {"name":"package","data":"package","orderable":false,"searchable":false},
                {"name":"order_id","data":"control","orderable":false,"searchable":true},
                {"name":"comment","data":"comment","orderable":false,"searchable":false},
                {"name":"action","data":"action","orderable":false,"searchable":false},
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBfrtip',
            buttons: ["print","reset","reload",
                {
                    text: '<i class="material-icons dp48 " style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }} {{ trans('main.orders_products.manage.service') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        add_product('service');
                    }
                },
                
            ],
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            }, 
            'responsive': true,
        });

        var mobile_services_datatable = $('#mobile_services-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('users-show-product-mobile_services')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.user_id = {{$user->id}};
                    d.orders_filtered = orders_filtered;
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"color","data":"color","orderable":false,"searchable":false},
                {"name":"code","data":"name","orderable":false,"searchable":true},
                {"name":"mob_account_id","data":"account","orderable":true,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":true},
                {"name":"service_password","data":"control","orderable":false,"searchable":true},
                {"name":"name","data":"comment","orderable":false,"searchable":true},
                {"name":"action","data":"action","orderable":false,"searchable":false},
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBfrtip',
            buttons: ["print","reset","reload",
                {
                    text: '<i class="material-icons dp48 " style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }} {{ trans('main.orders_products.manage.mobile_service') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        add_product('mobile_service');
                    }
                },
            ],
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            }, 
            'responsive': true,
        });

        var goods_datatable = $('#goods-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('users-show-product-goods')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.user_id = {{$user->id}};
                    d.orders_filtered = orders_filtered;
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"name","data":"name","orderable":false,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":true},
                {"name":"comment","data":"comment","orderable":false,"searchable":false},
                {"name":"action","data":"action","orderable":false,"searchable":false},
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBfrtip',
            buttons: ["print","reset","reload",
                {
                    text: '<i class="material-icons dp48 " style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }} {{ trans('main.orders_products.manage.goods') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        add_product('goods');
                    }
                },
            ],
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
            'responsive': true,
        });

        var orders_datatable = $('#orders-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('users-show-orders')}}",
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.user_id = {{$user->id}};
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
                {"name":"id","data":"id","orderable":true,"searchable":false,'visible':false},
                {"name":"name","data":"color","orderable":false,"searchable":true},
                {"name":"code","data":"code","orderable":true,"searchable":true},
                {"name":"price","data":"price","orderable":true,"searchable":true},
                {"name":"created_at","data":"created_at","orderable":true,"searchable":true},
                {"name":"action","data":"action","orderable":false,"searchable":false},
            ],
            order: [[ 0, "desc" ]],
            dom: 'lBfrtip',
            buttons:[
                {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                    action: function ( e, dt, node, config ) {
                        var rows = dt.rows( { selected: true } );
                        var dataArr = rows.data().toArray();
                        var rowCount = rows.count();
                        dataArr = $.map(dataArr, function(n,i){
                           return [ n.id ];
                        });

                        var datatables_all = $.extend( true, {}, datatables_obj);
                        datatables_all['orders'] = dt;
                        swal({
                            title: "{{trans('main.misc.delete_title')}}",
                            //text: "You will not be able to recover these discounts!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "{{trans('main.misc.delete')}}",
                            cancelButtonText: "{{trans('main.misc.cancel')}}",
                        }).then(function () {
                            url = "{{route('orders-delete')}}";
                            orders_filtered = [];
                            delete_items(url, dataArr, null, datatables_all);
                        });
                    }
                },
            ],
            select: {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [2,3,4] }
            ],
            drawCallback: function(settings){
                 var api = this.api();

                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
            //'responsive': true,
        });

        orders_datatable.on( 'select deselect', function ( e, dt, type, indexes ) {
            var rows = dt.rows( { selected: true } );
            var dataArr = rows.data().toArray();
            var rowCount = rows.count();
            dataArr = $.map(dataArr, function(n,i){
               return [ n.id ];
            });

            orders_filtered = dataArr;

            console.log(datatables_obj);

            delay(function(){
              $.each(datatables_obj, function( index, value ) {
                    value.draw();
                });
            }, 200 );
        } );

        function initDataTableCtrl(container) {
            $('.multi-select', container).material_select();
            $('.tooltipped').tooltip({delay: 50});
        }

        $('.dataTables_length select').addClass('browser-default');

	    $('select').material_select();

	    var datepicker = $('.datepicker').pickadate({
		    selectMonths: true,
		    selectYears: 100,
		    format: 'yyyy-mm-dd',
		});

		var picker = datepicker.pickadate('picker');
        "@if($user->byear&&$user->bmonth&&$user->bday)"
            picker.set('select', "{{$user->byear.'-'.$user->bmonth.'-'.$user->bday}}", { format: 'yyyy-mm-dd' });
        "@endif"

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

        $(document).on('processing.dt', '#stbs-datatable, #services-datatable, #mobile_services-datatable, #goods-datatable, #orders-datatable', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.collapsible-body');
            var tables_wrapper = $(this).closest('.dataTables_wrapper');
            if (processing) {
                tables_wrapper.addClass('svg-blur-1');
                // if (wrapper.find('.preloader-full').length == 0) {
                //     $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                // }
                
            } else {
                tables_wrapper.removeClass('svg-blur-1');
               // wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );

        $(window).on("orientationchange resize load", function (e) {
            global_width = $(this).width();

           /* $('.side-holder').each(function(index) {
                collapse = {'s': $(this).data('collapse-s'), 'm': $(this).data('collapse-m'), 'l': $(this).data('collapse-l')};
                
                var width;
                if (global_width >= 1500) {
                    width = collapse.l - Length.toPx($(this), '3rem');
                } else if(global_width >= 903) {
                    width = collapse.m - Length.toPx($(this), '3rem');
                } else {
                    width = collapse.s - Length.toPx($(this), '3rem');
                }

                console.log(width);

                if($(this).width() > 80){
                    $(this).width(width);
                }
            });*/
            
        });

        var url = "{{route('users-update')}}";
        var form = $('#user-parameters-form');
        save_user(url, $('#user-parameters-form'));

        var datatables_obj = {'stbs': stbs_datatable, 'services': services_datatable, 'mobile_services': mobile_services_datatable, 'goods':goods_datatable};

        var add_product = function (type) {
            var url = "{{route('users-add-product')}}";
            var container = $('#manage_modal > .container-2');
            var data = {'type': type, 'user_id': $('#user_id').val()};
            manage_load_modal(url, container, data);
        }

        $(document).on('click', '#save_comment-btn', function (e) {
            var url = "{{route('comment-save')}}";
            save_comment(url, $('form#comment_form'), $(this));
        });

        $(document).on('click', '.comment_modal-trigger', function (e) {
            var op_id = $(this).data('id');
            var comment = $(this).data('comment');
            $('#comment_modal').modal({
              dismissible: true, // Modal can be dismissed by clicking outside of the modal
              opacity: .5, // Opacity of modal background
              inDuration: 300, // Transition in duration
              outDuration: 200, // Transition out duration
              startingTop: '4%', // Starting top style attribute
              endingTop: '10%', // Ending top style attribute
              ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
                 $('#op_id').val(op_id);
                 $('#comment_text').val(comment).focus();
              },
              complete: function() { } // Callback for Modal close
            });
            $('#comment_modal').modal('open');
        });

        $(document).off('click', '.products-edit-btn').on('click', '.products-edit-btn', function(e) {
            var url = "{{route('orders_products.edit.show')}}";
            var data = {'id': $(this).data('id'), 'type': $(this).data('type')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data);
            //$('#add_product_form').find('.input-field').find('label').addClass('active');
        });

        $(document).off('change', '.change-status').on('change', '.change-status', function (e) {
            var url = "{{route('ordersproducts-change-status')}}";
            var status = $(this).is(':checked');
            var op_id = $(this).data('id');
            var type = $(this).data('type');
            var redraw_tables = [];
            if (type == 'stbs' || type == 'services') {
                redraw_tables = [datatables_obj.stbs, datatables_obj.services];
            } else {
                redraw_tables = [datatables_obj[type]];
            }
            var _datatables_obj = redraw_tables;
            ordersproducts_change_status(url, status, op_id, type, _datatables_obj, $(this));
        });

        $(document).off('click', '.products-delete-btn').on('click', '.products-delete-btn', function(e) {
            var _this = $(this);
            var type = _this.data('type');
            var redraw_tables = [];
            if (type == 'stbs' || type == 'services') {
                redraw_tables = [datatables_obj.stbs, datatables_obj.services];
            } else {
                redraw_tables = [datatables_obj[type]];
            }
            var _datatables_obj = redraw_tables;

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

                    url = "{{route('orders_products.delete')}}";
                    return new Promise(function(resolve, reject) {
                        delete_items(url, [$(_this).data('id')], null, _datatables_obj, function (response) {

                        });
                    });
                }
            }]).then(function() {
                
            });

            e.preventDefault();
        });

        $(document).off('click', '#manage_save-btn').on('click', '#manage_save-btn', function (e) {
            var form = $('#edit_product_form');
            var op_type = form.find('#op_type').val();
            form.submit();
            var redraw_tables = [];
            if (op_type == 'stbs' || op_type == 'services') {
                redraw_tables = [datatables_obj.stbs, datatables_obj.services];
            } else {
                redraw_tables = [datatables_obj[op_type]];
            }
            
            delay(function(){
              $.each(redraw_tables, function( index, value ) {
                    value.button('.buttons-reload').trigger();
                });
            }, 200 );            
            $(this).off('click');
        });

        $(document).on('click', '.side-holder-collapse-btn', function (e) {
            var container = $(this).closest('.side-holder');
            var next = container.parent().find('.side-holder-next');
            if (container != undefined) {
                if (container.hasClass('side-holder-collapse')) {
                    container.removeClass('side-holder-collapse');
                    next.removeClass('side-holder-expand');
                    
                } else {
                    container.addClass('side-holder-collapse');
                    next.addClass('side-holder-expand');
                }
                /*container.addClass('')
                collapse = {'s': container.data('collapse-s'), 'm': container.data('collapse-m'), 'l': container.data('collapse-l')};
                var width;
                if (global_width >= 1500) {
                    width = collapse.l;
                } else if(global_width >= 903) {
                    width = collapse.m;
                } else {
                    width = collapse.s;
                }

                if($(container).width() > 80){
                    $(container).animate({width: '80px'})
                }
                else{
                    $(container).animate({width: width})
                }*/
            }
        });

        $(document).off('click', '.edit_service_package-btn').on('click', '.edit_service_package-btn', function (e) {
            var packages = $(this).closest('td').find('.service_package-select')[1];

            if (packages.length > 0) {
                packages = $(packages).val();
            }

            var data1 = {'packages': packages, 'service_id': $(this).data('id')};
            var redraw_tables = [datatables_obj.stbs, datatables_obj.services];
            var url = "{{route('orders_products.service.packages.add')}}";
            manage_service_packages(url, data1, redraw_tables);
        });

        $(document).off('click', '.disabled-switch-overlay').on('click', '.disabled-switch-overlay', function (e) {
            var message = $(this).data('message');
            Materialize.toast(message , 4000);
        });

        $(document).off('click', '.add_product_save-btn').on('click', '.add_product_save-btn', function (e) {
            var form = $('#add_product_form');
            var op_type = form.find('#op_type').val();
            form.submit();

            var redraw_tables = [];
            if (op_type == 'stbs' || op_type == 'services') {
                redraw_tables = [datatables_obj.stbs, datatables_obj.services];
            } else {
                redraw_tables = [datatables_obj[op_type]];
            }
            
            delay(function(){
              $.each(redraw_tables, function( index, value ) {
                    value.button('.buttons-reload').trigger();
                });
            }, 200 );

            //$(this).off('click');
        });

        $(document).off('click', '.show-order-btn').on('click', '.show-order-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('orders-show', "id_plc") }}";
            var container = $('.mn-inner');
            var data = {'order_id':$(this).data('id')};
            show_page($(this), container, url, data);
        });

        $(document).off('click', '.modal-close').on('click', '.modal-close', function (e) {
            $('#manage_modal').removeClass('modal-short');
        });

        /*$(document).on('click', '.order-delete-btn', function (e) {
            var _this = $(this);
            var redraw_tables = [orders_datatable];
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this order!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
            }).then(function () {
                var url = "{{route('orders-delete')}}";
                var ids = [_this.data('id')];
                delete_items(url, ids, null, redraw_tables);
                //swal("Deleted", "Order has been deleted.", "success");
            });
        });*/

        $(document).off('click', '.show-user-statistics-btn').on('click', '.show-user-statistics-btn',function(e) {
            e.preventDefault();
            $('.show-user-statistics-btn').off('click');
            var id = $(this).data('id');
            var data = {'id': id};
            var url = "{{ route('users-show-statistics', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).off('click', '.show-user-balance-btn').on('click', '.show-user-balance-btn',function(e) {
            e.preventDefault();
            $('.show-user-balance-btn').off('click');
            var id = $(this).data('id');
            var data = {'id': id};
            var url = "{{ route('users-show-balance', "id_plc") }}";
            var container = $('.mn-inner');
            show_page($(this), container, url, data);
        });

        $(document).off('click', '.side-slidable-toggle-btn').on('click', '.side-slidable-toggle-btn', function (e) {
            var wrapper = $(this).closest('.side-slidabl-wrapper');
            if (wrapper.hasClass('opened-1')) {
                wrapper.addClass('closed-1').removeClass('opened-1');
                wrapper.parent('.side-holder-next').find('.right').removeClass('col l9 m8 s12').addClass('col l12 m12 s12');
                wrapper.find('button.btn').addClass('btn-floating').removeClass('btn-flat');
            } else {
                wrapper.addClass('opened-1').removeClass('closed-1');
                wrapper.parent('.side-holder-next').find('.right').removeClass('col l12 m12 s12').addClass('col l9 m8 s12');
                wrapper.find('button.btn').removeClass('btn-floating tooltipped').addClass('btn-flat');
            }
        });

        
        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

        delay(function(){
            $('ul.profile-tabs .tab').first().find('a').click();
        }, 200 );


       // window.dispatchEvent(new Event('resize'));
	});
</script>