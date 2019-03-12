<div class="modal-content row">
	<div class="col s12">
		<h5>Attach images to <b>@if($translated) {{$translated->name}} @else {{trans('main.misc.new_texts')}} @endif</b></h5>
	</div>
	<div class="col s12" style="padding: 0;">
		<div class="right">
		    <ul class="tabs transactions-tabs transparent-block">
                <li class="tab col s6 l6 m6"><a href="#load_images_from_gallery" id="load_images_from_gallery-btn">Load from gallery</a></li>
		        <li class="tab col s6 l6 m6"><a class="active" href="#upload_new_image" id="upload_new_image-btn">Upload new image</a></li>
		    </ul>
		</div>
	</div>
    <div id="load_images_from_gallery" class="tabs-content-container-inline col s12">
        <div class="wrapper-custom-4 white">
            <div id="gallery_items-container" class="loading-block" style="padding: 1rem !important; min-height: 25rem;"></div>
        </div>   
    </div>
	<div id="upload_new_image" class="tabs-content-container-inline col s12">
        <div class="wrapper-custom-4 white">
            <div class="col s12">
                <div class="text-title">{{ trans('main.image_upload.title') }}</div>
            </div>
            <div class="col s12">
                <div class="row">
                    <div class="col l12 m12 s12">
                        <span>{{ trans('main.image_upload.preview') }}<span>
                    </div>
                    <div id="image_form" class="loading-block">
                        @include('texts.parts.image')
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
	</div>
</div>
<div class="modal-footer">
    <button class="waves-effect waves-green btn-flat item-attach_images-save-btn parameters-save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
    <button class="modal-action modal-close waves-effect waves-light btn-flat" id="attach_images_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
</div>
@php($attached_items_1 = ($translated != null && $translated->img != null && $translated->img != '') ? json_decode($translated->img, true) : [])

<script type="text/javascript">
	$(document).ready(function(e) {

        var gallery_items_arr_tmp = new Array();
        '@foreach($attached_items_1 as $a_item)'
            gallery_items_arr_tmp.push('{{$a_item}}');
        '@endforeach'



        var gallery_items_arr = gallery_items_arr_tmp.slice();
        console.log(gallery_items_arr)
        var id = $('#current_edited_item_id').val() || null;

		$('ul.tabs').tabs({
            swipeable: false
        });

        setTimeout(function(){
            $('ul.transactions-tabs .tab').first().find('a').click();
        }, 0 );

        $('select:not(.select2-1)').material_select();

        $(document).off('click', '#load_images_from_gallery-btn').on('click', '#load_images_from_gallery-btn', function (e) {
            manage_load("{{ route('texts-load_gallery_items') }}", $('#gallery_items-container'), {'menu_item_name': 'texts', 'layout_grid_gallery': 'l2 m3 s6', 'id': id, 'language_id': $('#current_lang_id').val()}, 300, null, 'post', function(e) {
                gallery_items_arr = gallery_items_arr_tmp.slice();
                console.log(gallery_items_arr);
            });
            e.preventDefault();
        });

        $(document).off('click', '#selectable-gallery .gallery-item-figure-inline').on('click', '#selectable-gallery .gallery-item-figure-inline', function (e) {
            $(this).toggleClass('selected');
            var item_img = $(this).data('img');
            // var checkbox = $(this).find('input[type=checkbox]');
            // if (checkbox.is(':checked')) {
            //     checkbox.prop('checked', false);
            // } else {
            //     checkbox.prop('checked', true);
            // }

            if($.inArray(item_img, gallery_items_arr) !== -1) {
                gallery_items_arr = $.grep(gallery_items_arr, function(value) {
                  return value != item_img;
                });
            } else {
                gallery_items_arr.push(item_img);
            }
            
        });

        $(document).off('click', '#upload_new_image-btn').on('click', '#upload_new_image-btn', function (e) {
            // gallery_items_arr = [];
            // $('#gallery_items-container input[type=checkbox]').attr('checked', false);
            //console.log(gallery_items_arr);
        });

        $(document).off('click', '.item-attach_images-save-btn').on('click', '.item-attach_images-save-btn', function (e) {
            e.preventDefault();
            var container = $('#load_selected_images');
            var data = {'id': id, 'language_id': $('#current_lang_id').val(), 'images': gallery_items_arr};
            update_glob("{{route('texts-attach_images')}}", data, function() {
                manage_load("{{route('texts-load_attached_images')}}", container, data, true);   
            });
            $('#manage_modal').modal('close');
        });

        $(document).off('click', '.upload_image-btn').on('click', '.upload_image-btn', function(e) {
            var form = $('#image_form');
            var url = "{{route('texts-updateimg')}}";
            var size = $('#image-size-select').val();
            data = {'id': id, 'size': size, 'language_id': $('#current_lang_id').val()};

            upload_image(form, url, data, function(response) {
                // if ($('#share_img_check').is(":hidden")) {
                //     $('#share_img_check').slideDown(100);
                // }
                // 
                gallery_items_arr = [response.newfilename];
            });
        });
	})
</script>