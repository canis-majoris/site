<form id="manage_delivery_form">
    <div class="modal-content row">
        <div class="row">
            <div class="col s12">
                @if(!$delivery)
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.new')}} {{trans('main.misc.delivery')}}</h5>
                    <input type="hidden" name="id" id="current_edited_item_id" value="">
                @else
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$delivery->name}}</h5>
                    <input type="hidden" name="id" id="current_edited_item_id" value="{{$delivery->id}}">
                @endif
                <h5>{{trans('main.misc.main_settings')}}</h5>
            </div>
            <div class="input-field col l6 m6 s12">
               <input id="name" type="text" class="validate" name="name" value="@if($delivery != null){{ $delivery->name}}@endif">
               <label for="name" class="input-label-1">{{trans('main.delivery.manage.form.name')}}</label>
            </div>
            <div class="input-field col l6 m6 s12">
               <input id="price" type="number" class="validate" name="price" value="@if($delivery != null){{$delivery->price}}@endif">
               <label for="price" class="input-label-1">{{trans('main.delivery.manage.form.price')}}</label>
            </div>
            <div class="input-field col l12 m12 s12">
                <textarea id="comment" class="materialize-textarea" length="512" style="height: 21.2px;" name="comment">@if($delivery != null){{ $delivery->comment }}@endif</textarea>
                <label for="comment" class="@if($delivery != null && $delivery->comment) active @endif">{{ trans('main.misc.comment') }}</label>
                <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
            </div>
            <div class="input-field col s12">
                <span class="input-wrapper-span-1">
                    <input type="checkbox" id="hide" name="hide" @if($delivery != null && !$delivery->hide) checked @endif>
                    <label for="hide" style="left:0;">{{trans('main.misc.status')}}</label>
                </span>
            </div>
            <div class="clear"></div>
            <ul class="collapsible" data-collapsible="expandable">
                <li class="active">
                    <div class="collapsible-header" id="attach_image-section"><i class="material-icons">insert_photo</i>Изображение</div>
                    <div class="collapsible-body">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <div class="col s12" style="padding: 0;">
                                    <div class="right">
                                        <ul class="tabs attach_images-tabs transparent-block">
                                            <li class="tab col s6 l6 m6"><a href="#load_images_from_gallery" id="load_images_from_gallery-btn">Load from gallery</a></li>
                                            <li class="tab col s6 l6 m6"><a href="#upload_new_image" id="upload_new_image-btn">Upload new image</a></li>
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
                                            <div class="page-title">{{ trans('main.image_upload.title') }}</div>
                                        </div>
                                        <div class="col s12">
                                            <div class="row">
                                                <div class="col l12 m12 s12">
                                                    <span>{{ trans('main.image_upload.preview') }}</span>
                                                </div>
                                                <div id="image_form" class="loading-block">
                                                    @include('settings.parts.image')
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="delivery_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
@php($attached_items_1 = ($delivery != null && $delivery->img != null && $delivery->img != '') ? json_decode($delivery->img, true) : [])

<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.collapsible').collapsible();
        
        $('ul.tabs').tabs({
            swipeable: false
        });

        var load_image_data = function () {
            manage_load("{{ route('delivery-load_gallery_items') }}", $('#gallery_items-container'), {'menu_item_name': 'delivery', 'layout_grid_gallery': 'l2 m3 s6', 'id': id, 'language_id': $('#current_lang_id').val()}, 300, null, 'post', function(e) {
                gallery_items_arr = gallery_items_arr_tmp.slice();
                console.log(gallery_items_arr);
            });
        };

        var gallery_items_arr_tmp = new Array();
        '@foreach($attached_items_1 as $a_item)'
            gallery_items_arr_tmp.push('{{$a_item}}');
        '@endforeach'

        '@if(!empty($attached_items_1))'
            setTimeout(function(){
                $('.collapsible #attach_image-section').unbind().click();
                //load_image_data();
            }, 0 );
        '@endif'

        var gallery_items_arr = gallery_items_arr_tmp.slice();
        console.log(gallery_items_arr)
        var id = $('#current_edited_item_id').val();
        

        $('#manage_delivery_form').off('click', '.collapsible #attach_image-section').on('click', '.collapsible #attach_image-section', function (e) {
            if ($(this).hasClass('active')) {
                // $('ul.tabs').tabs({
                //     swipeable: false
                // });
                $('#load_images_from_gallery-btn').unbind().click();
                //load_image_data();
            }
        })

        

        $('select:not(.select2-1)').material_select();

        $(document).off('click', '#load_images_from_gallery-btn').on('click', '#load_images_from_gallery-btn', function (e) {
            load_image_data();
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

            console.log(gallery_items_arr)
            
        });

        // $(document).off('click', '#upload_new_image-btn').on('click', '#upload_new_image-btn', function (e) {
        //     gallery_items_arr = [];
        //     console.log(gallery_items_arr);
        // });

        var url_sp = "{{route('delivery-save')}}";
        var form_sp = $('#manage_delivery_form');
        var from_sp_vaidator = {
            rules: {
                name: {
                    required: true
                },
                price: {
                    required: true
                },
                comment: {
                    maxlength: 512
                }
                /*date_start: {
                    required: true
                },
                date_end: {
                    required: true
                }*/
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator, {}, {}, 'post', function (response) {
            if ($('#attach_image-section').hasClass('active')) {
                var data = {'id': $('#current_edited_item_id').val(), 'images': gallery_items_arr};
                update_glob("{{route('delivery-attach_images')}}", data, function() {
                   // manage_load("{{route('settings-load_attached_images')}}", container, data, true);   
                });
            }
        });

        $(document).off('click', '.upload_image-btn').on('click', '.upload_image-btn', function(e) {
            var form = $('#image_form');
            var url = "{{route('delivery-updateimg')}}";
            var size = $('#image-size-select').val();
            data = {'id': id, 'size': size};
            upload_image(form, url, data, function (response) {
                gallery_items_arr = [response.newfilename];
            });
        });

        $(document).off('click', '.delete_image-btn').on('click', '.delete_image-btn', function(e) {
            $('#crop_status_switch-wrapper').slideUp(100);
            $('#image_upload_input-wrapper').slideDown(100);
            var url = "{{route('delivery-updateimg')}}";
            var name = $('#image_name').val();
            var data = {'id': id, 'name': name}; 
            delete_image(url, data);
        });

        $(document).off('click', '#delivery_save-cancel-btn').on('click', '#delivery_save-cancel-btn', function (e) {
            e.preventDefault();
            var tmp_image_name = $('#image_name').val();
            if (tmp_image_name && !id) {
                var url = "{{route('delivery-updateimg')}}";
                var data = {'name': tmp_image_name, 'action':'delete', 'directory': 'delivery', 'id': id};
                delete_image(url, data);
            }
            $('#manage_modal').modal('close');
        });

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

        @if($delivery != null)  $('#manage_delivery_form').find('label.input-label-1').addClass('active'); @endif
    });
</script>