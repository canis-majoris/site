@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css" rel="stylesheet">
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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a><span><i class="material-icons">keyboard_arrow_right</i></span><a href="{{route('catalog.index')}}" style="font-size:25px;">{{trans('dashboard.sidebar.list.settings.permissions')}}</a>
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
            <div class="side-holder-next opened-1" id="">
                <div class="col s12 m4 l3 left-fixed-small-1 side-slidabl-wrapper opened-1" style="bottom: -15px;">
                    <div class="side-slidable-left-1-top">
                        <button class="btn btn-flat side-slidable-toggle-btn tooltipped" data-position="right" data-delay="50" data-tooltip="{{ trans('main.permissions.sidebar.menu.toggle_button_toolptip') }}"><i class="material-icons">keyboard_arrow_left</i></button>
                    </div>
                    <div class="mailbox-list">
                        <div class="row">
                            <div class="col s12">
                                <div class="">
                                    <div id="menu_items-container" style="margin: 0 -22px; padding: 20px 0;" class="side-menu-items-wrapper">
                                        @include('permissions.parts.form_right')
                                    </div>
                                    <input type="hidden" name="current_menu_item_id" id="current_menu_item_id" class="current-side-menu-item-id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col l9 m8 s12 loading-block right side-content-wrapper-1" id="roles_data_container">
                    @include('permissions.parts.form_right_data')
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <input type="hidden" name="current_lang_id" id="current_lang_id" value="1">

    <div id="manage_modal" class="modal modal-fixed-footer">
        <div class="container-2"></div>
    </div>
</main>
<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
<script src="{{ URL::asset('admin_assets/js/custom.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function (e) {
        $('.tooltipped').tooltip({delay: 50});
    });

    var search_input = null;
    var current_menu_item_id = null;
    var show_item_arr_btn = false;
    var contents_shown = 'show-list';
    var role_id = null;
    var lang_ind = null;
    var items_type = 'permissions';
    var side_menu_load_url = "{{route('roles-load')}}",
        side_menu_load_settings_url = "{{ route('roles-load_settings') }}",
        side_menu_item_edit_url = "{{ route('roles-edit') }}",
        side_menu_item_update_url = "{{route('roles-update')}}",
        side_menu_item_delete_url = "{{route('roles-delete')}}",
        side_menu_item_add_item = "{{route('roles-add_permission')}}",
        side_menu_item_remove_item = "{{route('roles-remove_permission')}}",
        item_edit_url = "{{route('permissions-edit')}}",
        item_create_url = "{{route('permissions-new')}}",
        item_delete_url = "{{route('permissions-delete')}}";

    var texts = {
        'title': "{{trans('main.misc.delete.title')}}",
        'text': "{{trans('main.misc.delete.text')}}",
        'type': "warning",
        'confirm': "{{trans('main.misc.remove')}}",
        'cancel': "{{trans('main.misc.cancel')}}",
        'deleted': "{{trans('main.misc.deleted')}}",
        'success_title': "{{trans('main.misc.delete.success.title')}}"
    };

    (function(window,$){
        
        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: 'permissions',
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                    d.search_input = search_input;
                    d.current_menu_item_id = current_menu_item_id;
                    d.role_id = role_id;
                },
            },
           /* rowReorder: {
                dataSrc: 'order',
            },*/
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
            /*"responsive": true,*/
            /*select: true,
            columnDefs: [
                { orderable: false, targets: [ 1,2,3 ] }
            ],*/
            "columns":[
                {"name":"id","data":"id","title":"Id","orderable":false,"searchable":false, 'visible':false},
                {"name":"name","data":"name","title":"{{ trans('main.permissions.items.table.header.name') }}","searchable":true, 'visible':false},
                {"name":"display_name","data":"display_name","title":"{{ trans('main.permissions.items.table.header.display_name') }}","orderable":true,"searchable":true},
                {"name":"description","data":"description","title":"{{ trans('main.permissions.items.table.header.description') }}","orderable":true,"searchable":false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                //{data: 'remove', name: 'remove', orderable: false, searchable: false},
                
                // {"name":"for_mobile","data":"for_mobile","title":"for mobile","orderable":false,"searchable":false}
            ],
            "order": [0, 'asc'],
            "dom":"<'row'<'col l12 m12 s12'lBfr>>" +
                "<'row'<'col l12 m12 s12'ip>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "buttons":["csv","excel","print","reset","reload",
                {
                    text: '<i class="material-icons dp48 " style="font-size: 25px;">playlist_add</i> <span>'+"{{ trans('main.misc.add') }}"+'</span>',
                    className: 'custom-toolbar-btn-1',
                    action: function ( e, dt, node, config ) {
                        manage_permission(item_create_url);
                    }
                }],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'iDisplayLength': 25,
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
        });

        datatables_init_simple();

        function initDataTableCtrl(container) {
            
            $('.materialboxed').materialbox();
        }

        $(document).on('click', '#clear_menu_items_filter-btn', function(e) {
            e.preventDefault();
            clear_menu_data(this, function () {});
        });

        $(document).on('click', '#add_role_btn', function (e) {
            e.preventDefault();
            menu_item_add(this, function () {});
        });

        $(document).on('click', '.collection-item', function (e) {

            var _this = $(this);

            load_side_meun_data(_this, side_menu_load_settings_url, function() {

                if (_this.hasClass('active')) {
                    show_product_arr_btn = true;
                    role_id = _this.data('id');
                    $('.add_role_permission-container').slideDown(100);
                } else {
                    show_product_arr_btn = false;
                    role_id = null;
                    $('.add_role_permission-container').slideUp(100);
                }

                if (window.LaravelDataTables !== undefined) {
                    delay(function(){
                      window.LaravelDataTables["dataTableBuilder"].draw();
                    }, 100 );
                }
            });

            e.preventDefault();
        });

        $(document).on('click', '.menu_item-delete-btn', function(e) {
            e.stopPropagation();
            role_id = null;
            delete_menu_item(this, texts, function () {});
        });

        $(document).on('click', '.permissions-delete-btn', function(e) {
            e.preventDefault();
            delete_item(this, texts, function () {});
        });
        
        $(document).on('click', '.permissions-edit-btn', function(e) {
            var permission_id = $(this).data('id');
            manage_permission(item_edit_url, permission_id);
        });

        $(document).on('click', '#role_permissions-save-btn', function (e) {
            data = {'permission_id': $('.role_permissions-select').val(), 'role_id': role_id};

            add_cash_payer(side_menu_item_add_item, data);
            $('.role_permissions-select').find('option:selected').remove();
            $('.role_permissions-select').select2("val", "default");
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            $(this).prop('disabled', true).unbind('click');
        });

        $(document).on('click', '.remove_from_role-btn', function (e) {
            data = {'permission_id': $(this).data('id'), 'role_id': role_id};

            add_cash_payer(side_menu_item_remove_item, data);
            if ($('.role_permissions-select').find("option[value='"+$(this).data('id')+"']").lenght > 0) {
                $('.role_permissions-select').find("option[value='"+$(this).data('id')+"']").prop('disabled', false);
            } else {
                $('.role_permissions-select').append('<option value="'+$(this).data('id')+'">'+$(this).data('display_name')+'</option>');
            }
            
            $('.role_permissions-select').select2("val", $(this).data('id'));
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
        });

        var manage_permission = function (url, permission_id = null) {
            var container = $('#manage_modal > .container-2');
            var data = {'role_id': role_id, 'permission_id': permission_id};
            manage_load_modal(url, container, data);
        };

        var form = $('.edit_menu_item-form');
        var from_sp_vaidator = {
            rules: {
                name: {
                    required: true
                },
                display_name: {
                    required: true,
                    maxlength: 512
                }
            },
            messages: {}
        };

        save_parameters(side_menu_item_update_url, form, from_sp_vaidator, {}, {}, 'post', function() {
            load_side_meun_items(side_menu_load_url);
        });

    })(window,jQuery);
</script>
@stop
