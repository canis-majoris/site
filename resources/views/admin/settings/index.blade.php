@extends('layout.main')

@section('title', 'Admin - settings')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css" rel="stylesheet">
<link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.css') }}" rel="stylesheet"> 
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
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1 ">
                                <div class="col l6 m6 s12 hide-on-med-and-down">
                                    <div class=" bred-c-holder-1" >
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a><span><i class="material-icons">keyboard_arrow_right</i></span><a href="{{route('settings.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.settings')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            <ul class="tabs z-depth-1">
                                                @foreach($languages as $language)
                                                    <li class="tab"><a data-langid="{{$language->id}}" class="lang-selector-1">{{$language->language}}</a></li>
                                                @endforeach
                                                <div class="indicator" style="right: 403px; left: 201.5px;"></div>
                                            </ul>
                                        </div>
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
    <div class="">
        <div class="row no-m-t no-m-b">
            <div class="side-holder-next opened-1" id="">
                <div class="col s12 m4 l3 left-fixed-small-1 side-slidabl-wrapper opened-1" style="bottom: -15px;">
                    <div class="side-slidable-left-1-top">
                        <button class="btn btn-flat side-slidable-toggle-btn tooltipped" data-position="right" data-delay="50" data-tooltip="{{ trans('main.content.sidebar.settings_types.toggle_button_toolptip') }}"><i class="material-icons">keyboard_arrow_left</i></button>
                    </div>
                    <div class="mailbox-list">
                        <div class="row">
                            <div class="col s12">
                                <div class="">
                                    <div id="menu_items-container" style="margin: 0 -22px; padding: 20px 0;" class="side-menu-items-wrapper">
                                        @include('settings.parts.form_right')
                                    </div>
                                    <input type="hidden" name="current_menu_item_id" id="current_menu_item_id" class="current-side-menu-item-id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col l9 m8 s12 right side-content-wrapper-1">
                    <div class="white shadow-1-1 animate-c-wrapper">
                        <div class="loading-block animate-c animate-left-out visible-c" id="menu_item_data-container">
                            @include('settings.parts.form_right_data')
                        </div>
                        <div class="loading-block animate-c animate-left-in" id="item_data-container">
                            <div id="item_edit-container" style="display: none; position: relative;"></div>
                        </div>
                        <input type="hidden" name="current_edited_item_id" id="current_edited_item_id" value="">
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <input type="hidden" name="current_lang_id" id="current_lang_id" value="1">

    <!-- Manage text -->
    <div id="manage_modal" class="modal modal-fixed-footer">
        <div class="container-2"></div>
    </div>
</main>
<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
<script src="https://cdn.datatables.net/rowreorder/1.2.0/js/dataTables.rowReorder.min.js"></script>
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/form-select2.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/custom.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function (e) {
        $('.tooltipped').tooltip({delay: 50});
    });
    
    var search_input = null;
    var lang_ind = "@if($languages->count()){{ $languages->first()->id }}@else 0 @endif";
    var current_menu_item_id = null;
    var show_item_arr_btn = false;
    var contents_shown = 'show-list';
    var gallery_items_type_name = 'settings';
    var side_menu_load_url = "{{route('settings_types-load')}}",
        side_menu_load_settings_url = "{{ route('settings_types-load_settings') }}",
        side_menu_item_edit_url = "{{ route('settings_types-edit') }}",
        side_menu_item_update_url = "{{route('settings_types-update')}}",
        side_menu_item_delete_url = "{{route('settings_types-delete')}}",
        item_edit_url = "{{route('settings-edit')}}",
        item_create_url = "{{route('settings-new')}}",
        item_delete_url = "{{route('settings-delete')}}",
        item_change_status_url = "{{route('settings-changestatus')}}",
        item_reorder_url = "{{route('settings-reorder')}}",
        load_gallery_data = "{{route('gallery_types-load_data')}}";

    var texts = {
        'title': "{{trans('main.misc.delete.title')}}",
        'text': "{{trans('main.misc.delete.text')}}",
        'type': "warning",
        'confirm': "{{trans('main.misc.remove')}}",
        'cancel': "{{trans('main.misc.cancel')}}",
        'deleted': "{{trans('main.misc.deleted')}}",
        'success_title': "{{trans('main.misc.delete.success.title')}}"
    };

    $('.no-m-t.no-m-b').addClass('show-list');
    $('#current_edited_item_id').val('');

    var load_settings_type_data_f = function(lang_ind) {
        $('#current_lang_id, #si_language_id').val(lang_ind);
        current_menu_item_id = null;
        show_item_arr_btn = false;
        $('#item_options').slideUp(100);
        $('.add_settings_button-1').slideUp(100);
        manage_load(side_menu_load_url, $('#menu_items-container'), {'language_id': lang_ind});
    }

    var load_settings_type_data_inline = function() {
        load_settings_type_data_f(lang_ind);
    }

    $(document).ready(function(e) {
        //load_settings_type_data_f(lang_ind);
        load_side_meun_items(side_menu_load_url);
    });

    
    (function(window,$){

        window.LaravelDataTables = window.LaravelDataTables || {};
        window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: 'settings',
                
                //type: 'POST',
                data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    d.search_input = search_input;
                    d.current_menu_item_id = current_menu_item_id;
                    d.language_id = lang_ind;
                },
            },
            rowReorder: {
                dataSrc: 'ord',
                //seditor:  editor
            },
           /* responsive: true,*/
            select: true,
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
            select: true,
            columnDefs: [
                { orderable: false, targets: [ 1,2,3 ] }
            ],
            "columns":[
                /*{"name":"id","data":"id","title":"Id","orderable":true,"searchable":true},*/
                {"name":"ord","data":"ord","title":"order","orderable":true, className: 'reorder',visible:false},
                // {"name":"id", "data": 'id', "title": 'language',"orderable":true,"searchable":true, "visible":false},
                {"name":"name","data":"name","title":"{{ trans('main.parameters.settings.table.header.name') }}","orderable":false,"searchable":true},
                {"name":"value","data":"value","title":"{{ trans('main.parameters.settings.table.header.value') }}","orderable":false,"searchable":false},
                {"name":"img","data":"img","title":"{{ trans('main.parameters.settings.table.header.image') }}","orderable":false,"searchable":false},
                {"name":"status","data":"status","title":"{{ trans('main.parameters.settings.table.header.status') }}","orderable":false,"searchable":false, width:'50px'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width:'85px'},
                //{data: 'select', name: 'select', orderable: false, searchable: false, width:'35px'},
                
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
                    manage_item(item_create_url, null, $('#current_lang_id').val());
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
            if (show_item_arr_btn) {
                $('.custom-toolbar-btn-1').fadeIn(100);
            } else {
                $('.custom-toolbar-btn-1').fadeOut(100);
            }
        }

        $(document).on('click', '#clear_menu_items_filter-btn', function(e) {
            e.preventDefault();
            clear_menu_data(this, function () {});
        });

        $(document).on('click', '#add_menu_item-btn', function (e) {
            e.preventDefault();
            menu_item_add(this, function () {});
        });

        $(document).on('click', '.lang-selector-1', function(e) {
            e.preventDefault();
            language_switch(this, function () {});
        });

        $(document).on('click', '.collection-item', function (e) {
            e.preventDefault();
            menu_item_switch(this, function () {});
        });

        $(document).on('click', '.settings-edit-btn', function(e) {
            e.preventDefault();
            item_edit(this, function () {});
        });

        $(document).on('click', '.settings-delete-btn', function(e) {
            e.preventDefault();
            delete_item(this, texts, function () {});
        });

        $(document).on('click', '.settings_type-delete-btn', function(e) {
            e.stopPropagation();
            delete_menu_item(this, texts, function () {});
        });

        $(document).off('click', '.brdc-return-to-list-btn-1').on('click', '.brdc-return-to-list-btn-1', function(e) {
            e.preventDefault();
            return_to_list(this, function () {});
        });

        $(document).on('click', '.btn.stat-change', function(e) {
            changestatus($(this), item_change_status_url);
        });

        var form = $('.edit_menu_item-form');
        var from_sp_vaidator = {
            rules: {
                /*name: {
                    required: true
                },
                display_name: {
                    maxlength: 512edit_settings_type_settings
                }*/
            },
            messages: {}
        };

        save_parameters(side_menu_item_update_url, form, from_sp_vaidator, {}, {}, 'post', function() {
            load_side_meun_items(side_menu_load_url);
        });

        // $(document).off('click', '.side-menu-parameters-save-btn').on('click', '.side-menu-parameters-save-btn', function (e) {

        // });

    })(window,jQuery);

</script>
@stop
