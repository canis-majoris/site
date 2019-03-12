@extends('layout.main')

@section('title', 'Gallery')

@section('content')
<link rel="stylesheet" href="{{ URL::asset('plugins/lightGallery-master/dist/css/lightgallery.css') }}"> 
<link rel="stylesheet" href="{{ URL::asset('plugins/lightGallery-master/dist/css/lg-transitions.css') }}"> 

<script src="{{ URL::asset('plugins/lightGallery-master/dist/js/lightgallery.min.js') }}"></script>
<script src="{{ URL::asset('plugins/lightGallery-master/demo/js/lg-thumbnail.min.js') }}"></script>
<script src="{{ URL::asset('plugins/lightGallery-master/demo/js/lg-fullscreen.min.js') }}"></script>
<script src="{{ URL::asset('plugins/lightGallery-master/demo/js/lg-video.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/masonry/masonry.pkgd.min.js') }}"></script>

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
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a><span><i class="material-icons">keyboard_arrow_right</i></span><a href="{{route('gallery.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.gallery')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
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
                        <button class="btn btn-flat side-slidable-toggle-btn tooltipped" data-position="right" data-delay="50" data-tooltip="{{ trans('main.gallery.sidebar.menu.toggle_button_toolptip') }}"><i class="material-icons">keyboard_arrow_left</i></button>
                    </div>
                    <div class="mailbox-list">
                        <div class="row">
                            <div class="col s12">
                                <div class="">
                                    <div id="menu_items_container" style="margin: 0 -22px; padding: 20px 0;" class="side-menu-items-wrapper"></div>
                                    <input type="hidden" name="current_menu_item_id" id="current_menu_item_id" class="current-side-menu-item-id">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col l9 m8 s12 loading-block right side-content-wrapper-1" id="menu_items_data-container">
                    @include('gallery.parts.form_right_data')
                </div>
                <input type="hidden" name="current_edited_item_id" id="current_edited_item_id" value="">
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <input type="hidden" name="current_lang_id" id="current_lang_id" value="1">

    <!-- Manage Product -->
    <div id="manage_modal" class="modal modal-fixed-footer">
        <div class="container-2"></div>
    </div>
</main>
@include('gallery.parts.photoswipe')

<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
<script src="{{ URL::asset('admin_assets/js/custom.js') }}"></script>
<script type="text/javascript">

    $(document).ready(function (e) {
        $('.tooltipped').tooltip({delay: 50});
        $('.materialboxed').materialbox();

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var lang_ind = null;
    var current_menu_item_id = null;
    var show_item_arr_btn = false;

    var side_menu_load_url = "{{route('gallery_types-load')}}",
        side_menu_load_settings_url = "{{ route('gallery_types-load_settings') }}",
        side_menu_load_data_url = "{{ route('gallery_types-load_data') }}",
        side_menu_item_edit_url = "{{ route('gallery_types-edit') }}",
        side_menu_item_update_url = "{{route('gallery_types-update')}}",
        side_menu_item_delete_url = "{{route('gallery_types-delete')}}",
        item_edit_url = "{{route('gallery.edit')}}",
        item_create_url = "{{route('gallery.new')}}",
        item_delete_url = "{{route('gallery.delete')}}",
        item_change_status_url = "{{route('gallery.changestatus')}}";

    var load_menu_item_data_f = function(lang_ind) {
        current_menu_item_id = null;
        show_item_arr_btn = false;
        $('#item_options').slideUp(100);
        $('.add_product_button-1').slideUp(100);
        manage_load(side_menu_load_url, $('#menu_items_container'), {'language_id': lang_ind});
    }

    var load_menu_item_data_inline = function() {
        load_menu_item_data_f(lang_ind);
    }

    $(document).ready(function(e) {
        load_side_meun_items(side_menu_load_url);
    });

    var manage_gallery_item = function (url, gallery_item_id = null) {
        var container = $('#manage_modal > .container-2');
        var data = {'current_menu_item_id': current_menu_item_id, 'gallery_item_id': gallery_item_id};
        manage_load_modal(url, container, data);
    };

    manage_load(side_menu_load_data_url, $('#items-container'), {'current_menu_item_id': null}, 100, null, 'post', function() {
        $("img.lazy").lazyload({
            effect: 'fadeIn',
            effectspeed: 1000,
            threshold: 200
        });
    });

    (function(window,$){

        $(document).on('click', '#clear_menu_items_filter-btn', function(e) {
            current_menu_item_id = null;
            $('#current_menu_item_id').val(current_menu_item_id);
            clear_items_change(e);
            load_side_meun_items(side_menu_load_url);
            show_item_arr_btn = false;
            e.preventDefault();
        });

        $(document).on('click', '#add_menu_item_btn', function (e) {
            e.preventDefault();
            var data = {'language_id': lang_ind};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(side_menu_item_edit_url, container, data, false);
        });

        $(document).off('click', '.side-menu-parameters-save-btn').on('click', '.side-menu-parameters-save-btn', function (e) {
            //$('.edit_menu_item-form').submit();
            // var url = "{{ route('menus_items-load') }}";
            // delay(function(){
            //     //window.LaravelDataTables["dataTableBuilder"].draw();
            //     manage_load(url, $('#menu_items_container'), {'language_id': lang_ind});
            // }, 200 );
        });


        $(document).on('click', '.collection-item', function (e) {

            load_side_meun_data($(this), side_menu_load_settings_url, function() {
                var data = {'current_menu_item_id': current_menu_item_id};
                manage_load(side_menu_load_data_url, $('#items-container'), data, 200, null, 'post', function() {
                    $("img.lazy").lazyload({
                        effect: 'fadeIn',
                        effectspeed: 1000,
                        threshold: 200
                    });
                });

                if (show_item_arr_btn) {
                    $('.custom-toolbar-btn-1').fadeIn(100);
                } else {
                    $('.custom-toolbar-btn-1').fadeOut(100);
                }
            });

            //e.preventDefault();
        });

        $(document).on('click', '#gallery-items-refresh', function (e) {
            var data = {'current_menu_item_id': current_menu_item_id};
            manage_load(side_menu_load_data_url, $('#items-container'), data, 200, null, 'post', function() {
                $("img.lazy").lazyload({
                    effect: 'fadeIn',
                    effectspeed: 1000,
                    threshold: 200
                });
            });
        });
        

        $(document).off('click', '.remove-image-btn').on('click', '.remove-image-btn', function(e) {
            var id = $(this).data('id');
            var wrapper = $(this).closest('.gallery-item');
            var container = $('#items-container');
            var data = {'ids': [id]};

            swal.queue([{
              title: "{{trans('main.misc.delete.img.title')}}",
              text: "{{trans('main.misc.delete.img.text')}}",
              showLoaderOnConfirm: true,
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "{{trans('main.misc.delete')}}",
              cancelButtonText: "{{trans('main.misc.cancel')}}",
              preConfirm: () => {
                swal.showLoading();
                $(wrapper).fadeOut(100, function() {
                    update_glob("{{route('gallery.delete')}}", data, function() {
                        manage_load(side_menu_load_data_url, container, {'current_menu_item_id': current_menu_item_id}, 100, null, 'post');
                        swal("{{trans('main.misc.deleted')}}", "{{trans('main.misc.delete.img.success.title')}}", "success");
                    });
                });
              }
            }])
        });

        $(document).on('click', '.menu_item-delete-btn', function (e) {
            e.stopPropagation();
            $(this).closest('.collection-item').trigger('mouseover');
            var _this = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this menu item!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
            }).then(function () {
                m_i_id = _this.data('id');
                current_menu_item_id = null;
                clear_items_change(e);
                show_item_arr_btn = false;
                var load_menu_item_data_simple = function() {
                    manage_load(side_menu_load_url, $('#menu_items_container'), {'language_id': $('#current_lang_id').val()}, 100, $('#edit_menu_item_settings-save-btn'));
                }
                delete_main(side_menu_item_delete_url, {'id': m_i_id, 'language_id': lang_ind}, [], load_menu_item_data_simple)

                //manage_load(url, $('#menu_items_container'), {'id': m_i_id, 'language_id': lang_ind});
                //load_menu_item_data(url, $('#current_lang_id').val(), $('#menu_items_container'), m_i_id);
                $('#item_options').slideUp(100);
                
                swal("Deleted", "Menu item has been deleted.", "success");
            })
            
            e.preventDefault();
        });

        $(document).on('click', '.add_gallery_item_button-1', function (e) {
            e.preventDefault();
            var data = {'current_menu_item_id': current_menu_item_id};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(side_menu_item_edit_url, container, data, false);
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
                    maxlength: 512edit_menu_item_settings
                }*/
            },
            messages: {}
        };

        save_parameters(side_menu_item_update_url, form, from_sp_vaidator, {}, {}, 'post', function() {
            load_side_meun_items(side_menu_load_url);
        });


        $(document).on('click', '.gallery_item-delete-btn', function(e) {
            var _this = $(this);
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this product!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
            }).then(function () {
                changestatus(_this, item_delete_url, []);
                /*product_changestatus(_this, url);
                window.LaravelDataTables["dataTableBuilder"].draw();*/
                swal("Deleted", "Menu item has been deleted.", "success");
            });
            e.preventDefault();
        });

        $(document).on('click', '.gallery_item-edit-btn', function(e) {
            var gallery_item_id = $(this).data('id');
            manage_gallery_item(item_edit_url, gallery_item_id);
        });

        $(document).on('click', '.gallery_item-add-btn', function(e) {
            var gallery_item_id = $(this).data('id');
            manage_gallery_item(item_create_url);
        });
    })(window,jQuery);
</script>

@stop