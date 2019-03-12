@extends('layout.main')

@section('title', 'Admin - Pages')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css" rel="stylesheet">
<link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.css') }}" rel="stylesheet"> 
<script src="{{ URL::asset('plugins/js/masonry.pkgd.min.js') }}"></script>

<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<!-- or -->
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js"></script>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.css" rel="stylesheet"> -->


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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a><span><i class="material-icons">keyboard_arrow_right</i></span><a href="{{route('pages.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.pages')}}</a>
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
                        <button class="btn btn-flat side-slidable-toggle-btn tooltipped" data-position="right" data-delay="50" data-tooltip="{{ trans('main.content.sidebar.pages_types.toggle_button_toolptip') }}"><i class="material-icons">keyboard_arrow_left</i></button>
                    </div>
                    <div class="mailbox-list">
                        <div class="row">
                            <div class="col s12">
                                <div class="">
                                    <div id="menu_items-container" style="margin: 0 -22px; padding: 20px 0;" class="side-menu-items-wrapper">
                                        @include('pages.parts.form_right')
                                    </div>
                                    <input type="hidden" name="current_menu_item_id" id="current_menu_item_id" class="current-side-menu-item-id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col l9 m8 s12 right side-content-wrapper-1">
                    <div class="white shadow-1-1 animate-c-wrapper" style="height: 75vh;">
                        <div class="loading-block animate-c animate-left-out visible-c flex-1-center" id="menu_item_data-container" style="height: 100%;">
                            <div class="center-align" style="width: 100%; color: rgba(255, 87, 34, 0.75);"><i class="material-icons" style="font-size: 5rem; color: #f1f1f1;">border_color</i><div>Edit Page</div></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.js"></script>
<!-- <script src="{{ URL::asset('admin_assets/plugins/nestable/jquery.nestable.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/pages/miscellaneous-nestable.js') }}"></script> -->


<script type="text/javascript">

    var search_input = null;
    var lang_ind = "@if($languages->count()){{ $languages->first()->id }}@else 0 @endif";
    var current_menu_item_id = null;
    var show_item_arr_btn = false;
    var contents_shown = 'show-list';
    var gallery_items_type_name = 'pages';
    var nestable_list = {};
    var nestable = null;
    var nested_list_state = false;
    var add_item_to_parent_id = false;
    var add_item_to_list = false;
    var side_menu_load_url = "{{route('pages_types-load')}}",
        side_menu_load_settings_url = "{{ route('pages_types-load_settings') }}",
        side_menu_item_edit_url = "{{ route('pages_types-edit') }}",
        side_menu_item_update_url = "{{route('pages_types-update')}}",
        side_menu_item_delete_url = "{{route('pages_types-delete')}}",
        item_edit_url = "{{route('pages-edit')}}",
        item_add_url = "{{route('pages-new')}}",
        item_create_url = "{{route('pages-new')}}",
        item_delete_url = "{{route('pages-delete')}}",
        item_change_status_url = "{{route('pages-changestatus')}}",
        load_gallery_data = "{{route('gallery_types-load_data')}}",

        update_pages_nestable_list_url = "{{route('pages-update_nestable_list')}}";
        load_pages_nestable_list_url = "{{route('pages-load_nestable_list')}}";

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

    var load_pages_type_data_f = function() {
        load_side_meun_nested_list(load_pages_nestable_list_url, function () {
            //$('#nestable').html(response.data);
            nestable = $('#nestable2').nestable({ 
                scroll: true,
                callback: function(l,e){
                    //e.preventDefault();
                    return_to_list_nested(this, function () {});
                }
            });
            //nestable.nestable('collapseAll');
        });
    }

    var update_pages_type_data_f = function() {
        nestable_list = $('#nestable2').nestable('serialize');
        $('#nestable2').nestable('destroy');
        load_side_meun_nested_list(update_pages_nestable_list_url, function () {
            //$('#nestable').html(response.data);
            nestable = $('#nestable2').nestable({ 
                scroll: true,
                callback: function(l,e){
                    //e.preventDefault();
                    return_to_list_nested(this, function () {});
                }
            });
            //nestable.nestable('collapseAll');
        });        
    }

    var load_pages_type_data_inline = function() {
        load_pages_type_data_f();
    }

    $(document).ready(function (e) {
        load_pages_type_data_inline();
    });

    
    (function(window,$){


        $(document).off('change', '#nestable2').on('change', '#nestable2', function(e) {
            e.preventDefault();
            update_pages_type_data_f();
        });


        $(document).on('click', '#clear_menu_items_filter-btn', function(e) {
            e.preventDefault();
            $('#nestable2').nestable('destroy');
            load_pages_type_data_inline()
        });

        $(document).on('click', '#nested_list_state_change-btn', function(e) {
            e.preventDefault();
            nested_list_state_change(this, function () {});
        });

        

        $(document).on('click', '#add_menu_item-btn', function (e) {
            e.preventDefault();
            manage_item_nested(item_create_url, null, $('#current_lang_id').val(), null, $(this));
            e.stopPropagation();
        });


        $(document).on('click', '.page-add_item-btn', function (e) {
            e.preventDefault();
            manage_item_nested(item_create_url, null, $('#current_lang_id').val(), $(this).data('id'));
            e.stopPropagation();
        });
        

        $(document).on('click', '.lang-selector-1', function(e) {
            e.preventDefault();
            language_switch_nested(this, function () {});
        });

        $(document).off('click', '.collection-item').on('click', '.collection-item', function (e) {
            e.preventDefault();
            menu_item_switch_nested($(this).data('id'), function () {});
        });

        $(document).on('click', '.pages-edit-btn', function(e) {
            e.preventDefault();
            item_edit(this, function () {});
        });

        $(document).on('click', '.page-delete-btn', function(e) {
            e.preventDefault();
            delete_menu_item_nested(this, texts, function () {});
            e.stopPropagation();
        });

        $(document).on('click', '.menu_item-delete-btn', function (e) {
            e.stopPropagation();
            delete_menu_item(this, texts, function () {});
            e.stopPropagation();
        });

        $(document).off('click', '.brdc-return-to-list-btn-1').on('click', '.brdc-return-to-list-btn-1', function(e) {
            e.preventDefault();
            return_to_list_nested(this, function () {});
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
                    maxlength: 512edit_menu_item-form
                }*/
            },
            messages: {}
        };

        save_parameters(side_menu_item_update_url, form, from_sp_vaidator, {}, {}, 'post', function() {
           // load_side_meun_items(side_menu_load_url);
           $('#nestable2').nestable('destroy');
            load_pages_type_data_inline()
        });

        // $(document).off('click', '.side-menu-parameters-save-btn').on('click', '.side-menu-parameters-save-btn', function (e) {

        // });

    })(window,jQuery);

</script>
@stop
