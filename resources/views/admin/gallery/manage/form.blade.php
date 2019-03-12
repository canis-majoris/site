<form id="manage_gallery_item_form" class="edit_item-form">
    <input type="hidden" name="current_menu_item_id" value="{{$current_menu_item_id}}">
    <div class="modal-content row">
        <div> 
            <div class="col s12">
                @if(!$gallery_item)
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.new')}} {{trans('main.gallery.items.manage.form.gallery_item')}}</h5>
                    <input type="hidden" name="gallery_item_id" value="">
                @else
                    @if($gallery_item != null)<h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$gallery_item->title}}</h5>@endif
                    <input type="hidden" name="gallery_item_id" value="{{$gallery_item->id}}">
                @endif
                <h5>{{ trans('main.gallery.items.manage.title') }}</h5>
            </div>
            <div class="row">
                <div class="col l12 m12 s12">
                    <div class="wrapper-custom-4 white">
                        <div class="col s12">
                            <div class="page-title">{{ trans('main.image_upload.title') }}</div>
                        </div>
                        <div class="col s12">
                            <div class="row">
                                <div class="col l12 m12 s12">
                                    <span>{{ trans('main.image_upload.preview') }}<span>
                                </div>
                                <div id="image_form">
                                    @include('gallery.parts.image')
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="p_n_title" type="text" class="validate" name="title" value="@if($gallery_item != null){{$gallery_item->title}}@endif">
                    <label for="p_n_title" class="@if($gallery_item != null && $gallery_item->title) active @endif">{{ trans('main.gallery.items.manage.form.title') }}</label>
                </div>
                <div class="input-field col s12">
                    <span>{{ trans('main.gallery.items.manage.form.description') }}</span>
                    <div >
                        <textarea id="p_n_description" class="materialize-textarea" name="description">@if($gallery_item != null){{$gallery_item->description}}@endif</textarea>
                    </div>
                </div>
                <div class="col l6 m6 s12">
                    <div class="input-field">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_status" name="status" @if($gallery_item != null && $gallery_item->status == 1) checked="checked" @endif>
                            <label for="p_n_status">{{ trans('main.gallery.items.manage.form.status') }}</label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat item-parameters-save-btn parameters-save-btn" id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="gallery_item_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        //$('.collapsible').collapsible();
        $('select').material_select();
        CKEDITOR.replace('p_n_description', {
            uiColor: '#E6E6E6',
            language: 'ru'
        });

        var url_sp = "{{route('gallery.save')}}";
        var form_sp = $('#manage_gallery_item_form');
        var from_sp_vaidator = {
            rules: {
                title: {
                    required: true
                },
                pic_1: {
                    required: true
                }
  /*              display_name: {
                    maxlength: 512
                }*/
            },
            messages: {}
        };

        save_parameters(url_sp, form_sp, from_sp_vaidator, {'description':'p_n_description'}, {}, 'post', function() {
            if ($('#select_img-ind').val() == 1) {
                var form = $('#image_form');
                var url = "{{route('gallery.update_img')}}";
                var id = $('#current_edited_item_id').val();
                var size = $('#image-size-select').val();
                data = {'id': id, 'size': size};
                upload_image(form, url, data, function() {
                    var container = $('#items-container');
                    manage_load("{{ route('gallery_types-load_data') }}", container, {'current_menu_item_id': $('#current_menu_item_id').val()});
                });
            } else {
                var container = $('#items-container');
                manage_load("{{ route('gallery_types-load_data') }}", container, {'current_menu_item_id': $('#current_menu_item_id').val()});
            }
        });

        // $(document).off('click', '.upload_image-btn').on('click', '.upload_image-btn', function(e) {
        //     var form = $('#image_form');
        //     var url = "{{route('gallery.update_img')}}";
        //     var id = $('.edit_item-form').find('input[name=gallery_item_id]').val() || null;
        //     var size = $('#image-size-select').val();
        //     data = {'id': id, 'size': size, 'language_id': $('#current_lang_id').val()};
        //     upload_image(form, url, data);
        // });

        $(document).off('click', '.delete_image-btn').on('click', '.delete_image-btn', function(e) {
            $('#crop_status_switch-wrapper').slideUp(100);
            $('#image_upload_input-wrapper').slideDown(100);
            var url = "{{route('gallery.update_img')}}";
            var id = $('.edit_item-form').find('input[name=gallery_item_id]').val() || null;
            var name = $('#image_name').val();
            var data = {'id': id, 'name': name, 'language_id': $('#current_lang_id').val()}; 
            delete_image(url, data, function(response) {
                $('#select_img-btn').fadeIn(300);
                $('#select_img-ind').val(1);
            });
        });

        $(document).off('click', '#gallery_item_save-cancel-btn').on('click', '#gallery_item_save-cancel-btn', function (e) {
            e.preventDefault();
            var tmp_image_name = $('#image_name').val();
            var id = $('.edit_item-form').find('input[name=gallery_item_id]').val() || null;
            if (tmp_image_name && !id) {
                var url = "{{route('file-delete')}}";
                var data = {'filename': tmp_image_name, 'action':'delete', 'directory': 'gallery_items'};
                delete_image(url, data);
            }
            $('#manage_modal').modal('close');
        });
    });
</script>