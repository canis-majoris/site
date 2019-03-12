<div class="modal-content row">
	<div class="col s12">
		<h5>Attach images to <b>{{$gallery_type->title}}</b></h5>
	</div>
	<div id="upload_new_image" class="tabs-content-container-inline col s12">
        <div class="wrapper-custom-3 images-wrapper-2">
    	    <div id="image_form" class="loading-block" style="padding: 1rem !important;">
    	        @include('gallery.parts.image')
    	    </div>
        </div>
	</div>
</div>
<div class="modal-footer">
    <button class="waves-effect waves-green btn-flat item-attach_images-save-btn parameters-save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
    <button class="modal-action modal-close waves-effect waves-light btn-flat" id="attach_images_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
</div>
<script type="text/javascript">
	$(document).ready(function(e) {

        $('select:not(.select2-1)').material_select();


        $(document).off('click', '#upload_new_image-btn').on('click', '#upload_new_image-btn', function (e) {
            // gallery_items_arr = [];
            // $('#gallery_items-container input[type=checkbox]').attr('checked', false);
            //console.log(gallery_items_arr);
        });

        $('.collapsible').collapsible();
        // CKEDITOR.replace('p_n_text', {
        //     uiColor: '#E6E6E6',
        //     language: 'ru'
        // });

        var url_sp = "{{route('gallery.save')}}";
        var form_sp = $('#manage_gallery_item_form');
        var from_sp_vaidator = {
            rules: {
                name: {
                    required: true
                },
  /*              display_name: {
                    maxlength: 512
                }*/
            },
            messages: {}
        };
        save_parameters(url_sp, form_sp, from_sp_vaidator, {'text':'p_n_text'});

        $(document).off('click', '.item-attach_images-save-btn').on('click', '.item-attach_images-save-btn', function (e) {
            e.preventDefault();
            var container = $('#load_selected_images');
            var data = {'id': "{{$settings->id}}", 'language_id': $('#current_lang_id').val(), 'images': gallery_items_arr};
            update_glob("{{route('settings-attach_images')}}", data, function() {
                manage_load("{{route('settings-load_attached_images')}}", container, data, true);   
            });
            $('#manage_modal').modal('close');
        });
	})
</script>