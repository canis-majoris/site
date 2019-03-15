<header class="mn-header navbar-fixed">
    <nav class="custom-color-1 darken-1">
        <div class="nav-wrapper row">
            <section class="material-design-hamburger navigation-toggle">
                <a href="#" data-activates="slide-out" class="button-collapse show-on-large material-design-hamburger__icon">
                    <span class="material-design-hamburger__layer"></span>
                </a>
            </section>
            <div class="header-title col l4 m2 s4">      
                <span class="chapter-title">{{trans('dashboard.main_title')}}</span>
            </div>
            <form class="left search col l4 m2 hide-on-med-and-down">
                <div class="input-field">
                    <input id="search" type="search" placeholder="{{ trans('main.misc.search') }}" autocomplete="off">
                    <label for="search" class="active label-icon"><i class="material-icons search-icon">search</i></label>
                </div>
                <a href="javascript: void(0)" class="close-search"><i class="material-icons">close</i></a>
            </form>
            <ul class="right nav-right-menu col s7 l1 m2" style="display: block;">

                <li>
                    <a href="javascript:void(0)" class="dropdown-button" data-activates='header_main-dropdown'><i class="material-icons">more_vert</i></a>
                    <ul id='header_main-dropdown' class='dropdown-content'>
                        {{-- <li><a class="waves-effect waves-grey" href=""><i class="material-icons">account_box</i>{{trans('dashboard.sidebar.admin.list.profile')}}</a></li> --}}
                        <li><a class="waves-effect waves-grey" href="{{route('logout-post')}}"><i class="material-icons">exit_to_app</i>{{trans('dashboard.sidebar.admin.list.sign_out')}}</a></li>
                    </ul>
                </li>
                @if(isset($notifications))
                <li class=""><a href="javascript:void(0)" data-activates="dropdown1" class="dropdown-button dropdown-right show-on-large"><i class="material-icons">notifications_none</i>@if(count($notifications) > 0)<span class="badge">{{ count($notifications) }}</span>@endif</a>
                    <ul id="dropdown1" class="dropdown-content notifications-dropdown" aria-labelledby="notificationsMenu" id="notificationsMenu">
                        <li class="notificatoins-dropdown-container">
                            <ul>
                                @foreach($notifications as $ntf)
                                    <li>
                                        <a href="#!">
                                            <div class="notification">
                                                <div class="notification-icon circle cyan"><i class="material-icons">done</i></div>
                                                <div class="notification-text"><p><b>{{ $ntf->author }}</b> {{ $ntf->action }}</p><span>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($ntf->created_at))->diffForHumans() }}</span></div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif
                {{-- <li class="hide-on-small-and-down"><a href="javascript:void(0)" data-activates="dropdown1" class="dropdown-button dropdown-right show-on-large"><i class="material-icons">notifications_none</i><span class="badge">4</span></a></li>
                <li class="hide-on-med-and-up"><a href="javascript:void(0)" class="search-toggle"><i class="material-icons">search</i></a></li> --}}
                
            </ul>
        </div>
    </nav>
</header>

<?php $auth_user = Auth::user(); ?>

<aside id="slide-out" class="side-nav custom-color-1 fixed">
    <div class="side-nav-wrapper">
        @if($auth_user)
        <div class="sidebar-profile">
            <div class="sidebar-profile-image">
                @if(!$auth_user->avatar)
                    <img src="{{URL::asset('/img/site/default_avatar.jpg')}}" class="circle" alt="">
                @else
                    <img src="{{url('/img/admins/avatars').'/'.$auth_user->avatar}}" class="circle" alt="">
                @endif
            </div>
            <div class="sidebar-profile-info">
                <a href="javascript:void(0);" class="">
                    <p>{{$auth_user->username}}</p>
                    <span>{{$auth_user->email}}<!-- <i class="material-icons right">arrow_drop_down</i> --></span>
                </a>
            </div>
        </div>
        @endif
       <!--  <div class="sidebar-account-settings">
           <ul>
               {{-- <li class="no-padding">
                   <a class="waves-effect waves-grey" href=""><i class="material-icons">account_box</i>{{trans('dashboard.sidebar.admin.list.profile')}}</a>
               </li> --}}
               <li class="no-padding">
                   <a class="waves-effect waves-grey" href="{{route('logout-post')}}"><i class="material-icons">exit_to_app</i>{{trans('dashboard.sidebar.admin.list.sign_out')}}</a>
               </li>
           </ul>
       </div> -->
        <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
            <li class="no-padding" data-index="/"><a class="waves-effect waves-grey " href="{{ route('home-index') }}" id="homepage_dashboard"><i class="material-icons">dashboard</i>{{trans('dashboard.sidebar.list.home')}}</a></li>
            <!-- <li class="no-padding" data-index="charts"><a class="waves-effect waves-grey" href="{{route('charts.index')}}"><i class="material-icons">show_chart</i>{{trans('dashboard.sidebar.list.charts')}}</a></li> -->
            <li class="no-padding" data-index="settings"><a class="waves-effect waves-grey" href="{{route('settings.index')}}"><i class="material-icons">build</i>{{trans('dashboard.sidebar.list.parameters')}}</a></li>
            <li class="no-padding" data-index="catalog"><a class="waves-effect waves-grey" href="{{route('catalog.index')}}"><i class="material-icons">view_list</i>{{trans('dashboard.sidebar.list.catalog')}}</a></li>
            <li class="no-padding" data-index="users">
                <a class="waves-effect waves-grey" href="{{route('users.index', 'type=all')}}">
                    <i class="material-icons">supervisor_account</i>{{trans('dashboard.sidebar.list.users.title')}}
                </a>
            </li>
            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">shopping_cart</i>{{trans('dashboard.sidebar.list.orders.title')}}<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="orders_products" class="sub-menu-item-1"><a href="{{route('orders_products.index', 'type=services')}}">{{trans('dashboard.sidebar.list.orders.items')}}</a></li>
                        <li data-index="delivery" class="sub-menu-item-1"><a href="{{route('delivery.index')}}">{{trans('dashboard.sidebar.list.orders.delivery_options')}}</a></li>
                        <li data-index="transactions" class="sub-menu-item-1"><a href="{{route('transactions.index', 'provider=cartu')}}">{{trans('dashboard.sidebar.list.orders.transactions')}}</a></li>
                        <!-- <li data-index="order_settings" class="sub-menu-item-1"><a href="{{route('order_settings.index')}}">{{trans('dashboard.sidebar.list.orders.settings')}}</a> -->
                    </ul>
                </div>
            </li>
            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">local_offer</i>{{trans('dashboard.sidebar.list.actions.title')}}<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="discounts" class="sub-menu-item-1"><a href="{{route('discounts.index')}}">{{trans('dashboard.sidebar.list.actions.discounts')}}</a></li>
                        <li data-index="promos" class="sub-menu-item-1"><a href="{{route('promos.index')}}">{{trans('dashboard.sidebar.list.actions.promocodes')}}</a></li>
                    </ul>
                </div>
            </li>
            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">description</i>{{trans('dashboard.sidebar.list.content.title')}}<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="pages" class="sub-menu-item-1"><a class="waves-effect waves-grey" href="{{route('pages.index')}}">{{trans('dashboard.sidebar.list.content.pages')}}</a></li>
                        <li data-index="texts" class="sub-menu-item-1"><a class="waves-effect waves-grey" href="{{route('texts.index')}}">{{trans('dashboard.sidebar.list.content.documents')}}</a></li>
                    </ul>
                </div>
            </li>
            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">library_books</i>{{trans('dashboard.sidebar.list.log.title')}}<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="activitylog" class="sub-menu-item-1"><a href="{{route('activitylog.index')}}">{{trans('dashboard.sidebar.list.log.activity_log')}}</a>
                        <li data-index="billinglog" class="sub-menu-item-1"><a href="{{route('billinglog.index')}}">{{trans('dashboard.sidebar.list.log.billing_log')}}</a>
                    </ul>
                </div>
            </li>

            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">perm_media</i><span>{{trans('dashboard.sidebar.list.media.title')}}</span><i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="gallery" class="sub-menu-item-1"><a href="{{route('gallery.index')}}">{{trans('dashboard.sidebar.list.media.gallery')}}</a>
                    </ul>
                </div>
            </li>
            <li class="no-padding submenu-parent">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">settings</i>{{trans('dashboard.sidebar.list.settings.title')}}<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li data-index="admins" class="sub-menu-item-1"><a href="{{route('admins.index')}}">{{trans('dashboard.sidebar.list.settings.administrators')}}</a>
                        <li data-index="permissions" class="sub-menu-item-1"><a href="{{route('permissions.index')}}">{{trans('dashboard.sidebar.list.settings.permissions')}}</a>
                        <li data-index="languages" class="sub-menu-item-1"><a href="{{route('languages.index')}}">{{trans('dashboard.sidebar.list.settings.language_parameters')}}</a>
                        <li data-index="messaging" class="sub-menu-item-1"><a href="{{route('messaging.index')}}">{{trans('dashboard.sidebar.list.tools.messaging')}}</a>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="footer">
            <p class="copyright">TVOYO.TV ©</p>
        </div>
    </div>
</aside>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    var request_path = "{{ Request::path() }}";

    $(document).ready(function(e) {
        type_parameter = getUrlParameter('type');
        
        var qurrent_menu_item = (type_parameter == undefined ? $('li[data-index="' + request_path + '"]') : $('li[data-index="' + request_path + '"]'));

        if (qurrent_menu_item.hasClass('sub-menu-item-1')) {
            parent_menu = qurrent_menu_item.closest('li.no-padding');
            parent_menu.addClass('active').find('.collapsible-header').click();
        }

        qurrent_menu_item.addClass('active');

       // alert(qurrent_menu_item.data('index'));
    });


    $(document).on('click', '.region-option-1', function(e) {
        var id = $(this).data('id');
        $.ajax({
            url: "{{route('change-region')}}",
            type: "post",
            datatype: 'json',
            data: {region_id: id},
            //contentType: true,
            //processData: true,
            error: function(a,b,c) {
                //$('.send_contact_mail-btn').button('reset');
                Materialize.toast( c, 4000);
            },
            success: function(data){
                //$('.send_contact_mail-btn').button('reset');
                if (!data.success) {
                    msg_text = data.message;
                    Materialize.toast( data.message, 4000, 'toast-1');

                } else {

                    msg_text = data.message;
                    Materialize.toast( data.message, 4000, 'toast-1');
                    window.location.reload();
                }
            },
            beforeSend: function() {
                //$('.card-preloader-full').fadeIn(100);
            },
            complete: function(){
                //$('.card-preloader-full').fadeOut(100);
            },
        });
    });

    $('.dropdown-button').dropdown({
          inDuration: 300,
          outDuration: 225,
          constrainWidth: false, // Does not change width of dropdown to that of the activator
          hover: false, // Activate on hover
          gutter: 0, // Spacing from edge
          belowOrigin: false, // Displays dropdown below the button
          alignment: 'left', // Displays dropdown with edge aligned to the left of button
          stopPropagation: false // Stops event propagation
        }
    );

</script>
