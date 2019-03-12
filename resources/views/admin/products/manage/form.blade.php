<form id="manage_product_form" class="edit_item-form white">
    <input type="hidden" name="current_menu_item_id" value="@if($menu_item != null){{$menu_item->id}}@endif">
    <div class="modal-content row">
        <div class="editable-block-1">
            <div class=" bred-c-holder-1">
                <i class="material-icons">settings_applications</i><strong style="font-size:25px;">@if(!$product){{trans('main.misc.new')}} {{trans('main.content.manage.form.text')}}@else @if($translated != null){{trans('main.misc.edit')}} {{$translated->name}}@else <div class="red-text text-lighten-3">{{trans('main.misc.translated name not assigned')}}</div> @endif @endif
                </strong>

                <button class="brdc-return-to-list-btn-1 right btn-flat" href="#" style="font-size: .75rem;" type="button"><i class="material-icons" style="margin-right: .5rem;">keyboard_backspace</i>
                        @if($menu_item != null)
                            @php($tr = $menu_item->translated()->where('language_id', $language_id)->first() ?? $menu_item)
                            {{$tr->name}}
                        @endif</button>
            </div>
            @if(!$product)
                <input type="hidden" name="product_id" value="">
            @else
                <input type="hidden" name="product_id" value="{{$product->id}}">
            @endif
            <input type="hidden" name="language_id" id="si_language_id" value="{{$language_id}}">
        </div>
        <div class="custom-divider-1"></div>
        <div class="editable-block-1">
            <div class="row">
                <div class="input-field col s12">
                    <input id="p_n_name" type="text" class="validate" name="name" value="@if($translated != null){{$translated->name}}@endif" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                    <label for="p_n_name" class="@if($translated != null && $translated->name) active @endif">{{ trans('main.catalog.products.manage.form.name') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_short_text" type="text" class="validate" name="short_text" value="@if($translated != null){{$translated->short_text}}@endif" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                    <label for="p_n_short_text" class="@if($translated != null && $translated->short_text) active @endif">{{ trans('main.catalog.products.manage.form.short_text') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_price" type="text" maxlength="35" class="validate" name="price" value="@if($product != null){{$product->price}}@else 0 @endif" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                    <label for="p_n_price" class="active">{{ trans('main.catalog.products.manage.form.price') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_weight" type="text" class="validate" name="weight" value="@if($product != null){{$product->weight}}@else 0 @endif">
                    <label for="p_n_weight" class="active">{{ trans('main.catalog.products.manage.form.weight') }}</label>
                </div>
                <div class="input-field col s12">
                    <span>{{ trans('main.catalog.products.manage.form.text') }}</span>
                    <div >
                        <textarea id="p_n_text" class="materialize-textarea" name="text">@if($translated != null){{$translated->text}}@endif</textarea>
                    </div>
                </div>
                <div class="col s12 m12 l12">
                    <div class="col s12 deep-purple lighten-5 wrapper-custom-1 shadow-0-1" style="margin: 2rem 0;">
                        <div class="input-field col s12">
                            <input id="p_n_ind" type="text" class="validate" name="itemindex" value="@if($product != null){{$product->ind}}@endif">
                            <label for="p_n_ind" class="active">{{ trans('main.catalog.products.manage.form.index') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col l6 m6 s12">
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_watch" name="watch" @if($product != null && $product->watch == 1) checked="checked" @endif>
                            <label for="p_n_watch">{{ trans('main.catalog.products.manage.form.public') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_is_service" name="is_service" onclick="$('#dayswrap').toggle();" @if($product != null && $product->is_service == 1) checked="checked" @endif>
                            <label for="p_n_is_service">{{ trans('main.catalog.products.manage.form.is_service') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_is_p" name="is_p" @if($product != null && $product->is_p == 1) checked="checked" @endif>
                            <label for="p_n_is_p">{{ trans('main.catalog.products.manage.form.is_p') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_yearly" name="yearly" @if($product != null && $product->yearly == 1) checked="checked" @endif>
                            <label for="p_n_yearly">{{ trans('main.catalog.products.manage.form.yearly') }}</label>
                        </span>
                    </div>
                </div>
                <div class="col l6 m6 s12">
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_visible_on_site" name="visible_on_site" @if($product != null && $product->visible_on_site == 1) checked="checked" @endif>
                            <label for="p_n_visible_on_site">{{ trans('main.catalog.products.manage.form.visible_on_site') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_is_goods" name="is_goods" @if($product != null && $product->is_goods == 1) checked="checked" @endif>
                            <label for="p_n_is_goods">{{ trans('main.catalog.products.manage.form.is_goods') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_for_mobile" name="for_mobile" @if($product != null && $product->for_mobile == 1) checked="checked" @endif>
                            <label for="p_n_for_mobile">{{ trans('main.catalog.products.manage.form.for_mobile') }}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_is_com" name="is_com" @if($product != null && $product->is_com == 1) checked="checked" @endif>
                            <label for="p_n_is_com">{{ trans('main.catalog.products.manage.form.is_com') }}</label>
                        </span>
                    </div>
                </div>
                <div class="col s12">
                    <div class="col s12 deep-purple lighten-5 wrapper-custom-1 shadow-0-1" style="@if($product != null && $product->is_service==1) display: block; @else  display: none; @endif margin: 2rem 0; padding-top: 1rem;" id="dayswrap">
                        <div class="input-field col s12">
                            <input id="p_n_per_month_count" value="@if($product != null && $product->per_month_count != null) {{$product->per_month_count}} @endif"  name="per_month_count" type="text" maxlength="12" data-val="range->0->9999999->(0-9999999)" class="validate">
                            <label for="p_n_per_month_count" class="active">{{ trans('main.catalog.products.manage.form.per_month_count') }}</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="p_n_days" value="@if($product != null && $product->days>0) {{$product->days}} @else 0 @endif"  name="days" type="text" maxlength="35" data-val="range->0->9999999->(0-9999999)" class="validate">
                            <label for="p_n_days" class="active">{{ trans('main.catalog.products.manage.form.days') }}</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="p_n_discount" value="@if($product != null){{$product->discount}}@endif" name="discount" type="text" maxlength="35" data-val="range->0->100->(0-100)" class="validate">
                            <label for="p_n_discount" class="active">{{ trans('main.catalog.products.manage.form.discount') }}</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="p_n_sum_x" value="@if($product != null){{$product->sum_x}}@endif"  name="sum_x" type="text" maxlength="35" data-val="range->0->100->(0-100)" class="validate">
                            <label for="p_n_sum_x" class="active">{{ trans('main.catalog.products.manage.form.sum_x') }}</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="p_n_sum_y" value="@if($product != null){{$product->sum_y}}@endif"  name="sum_y" type="text" maxlength="35" data-val="range->0->100->(0-100)" class="validate">
                            <label for="p_n_sum_y" class="active">{{ trans('main.catalog.products.manage.form.sum_y') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                     <ul class="collapsible" data-collapsible="expandable">
                        <li class="active" id="options">
                            <div class="collapsible-header @if($translated != null && $translated->img != '' && $translated->img != null && $translated->img != '[]') active @endif" style="background-color: rgb(247, 247, 247);"><i class="material-icons">insert_photo</i>{{ trans('main.misc.image') }}</div>
                            <div class="collapsible-body" style="background-color: rgb(247, 247, 247);">
                                <!-- <div class="col s12">
                                    <div class="page-title">{{ trans('main.image_upload.title') }}</div>
                                </div> -->
                                <div class="col s12">
                                    <div class="row">
                                        <button type="button" id="attach_images-btn" class="btn custom-btn-5 custom-color-1 shadow-1-1-h" style="@if($translated == null ) display: none; @endif"><i class="material-icons">attach_file</i> {{trans('main.misc.attach_image')}}</button>
                                        <div class="wrapper-custom-inline-1 info-inline small" id="no_images_before_create" style="@if($translated) display: none; @endif"><i class="material-icons">priority_high</i> {{ trans('main.mics.please_create_item_before_attaching_images') }}</div>
                                        <div id="load_selected_images" class="loading-block wrapper-custom-4 fancy-bck-1 white" style="margin: 1rem 0;"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row action-block-1">
                <div class="col l12 m12 s12" >
                    <button class="waves-effect waves-green btn item-parameters-save-btn parameters-save-btn right" id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
                    <button class="modal-action modal-close waves-effect waves-light btn-flat right brdc-return-to-list-btn-1" id="manage_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select:not(.select2-1)').material_select();
        //$('select.select2-1').select2();
        $('.collapsible').collapsible();

        var id = $('.edit_item-form').find('input[name=product_id]').val() || null;

        // $(".js-example-tokenizer").select2({
        //     tags: true,
        //     tokenSeparators: [',', ' '],
        //     placeholder: 'Tags'
        // });

        var data = {'id': id, 'language_id': $('#current_lang_id').val()};
        manage_load("{{route('products-load_attached_images')}}", $('#load_selected_images'), data, true);

        if(CKEDITOR.instances['p_n_text']) {
            delete CKEDITOR.instances['p_n_text'];
        }

        CKEDITOR.replace('p_n_text', {
            uiColor: '#E6E6E6',
            language: 'ru',
        });

        var url_sp = "{{route('products-save')}}";
        var form_sp = $('.edit_item-form');
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
        save_parameters(url_sp, form_sp, from_sp_vaidator, {'text':'p_n_text'}, [window.LaravelDataTables["dataTableBuilder"]], 'post', function(response) {
            if (id == null && response.result) {
                id = response.result.id;
                $('#no_images_before_create').slideUp(function() {
                    $('#attach_images-btn').slideDown();
                })
            }
        });

        $(document).off('click', '.delete_image-btn').on('click', '.delete_image-btn', function(e) {
            $('#crop_status_switch-wrapper').slideUp(100);
            $('#image_upload_input-wrapper').slideDown(100);
            var url = "{{route('products-updateimg')}}";
            var name = $('#image_name').val();
            var data = {'id': id, 'name': name, 'language_id': $('#current_lang_id').val()}; 
            delete_image(url, data);
        });

        $(document).off('click', '#manage_save-cancel-btn').on('click', '#manage_save-cancel-btn', function (e) {
            e.preventDefault();
            var tmp_image_name = $('#image_name').val();
            if (tmp_image_name && !id) {
                var url = "{{route('file-delete')}}";
                var data = {'filename': tmp_image_name, 'action':'delete', 'directory': 'texts'};
                delete_image(url, data);
            }
            $('#manage_modal').modal('close');
        });

        $(document).off('change', '#share_img').on('change', '#share_img', function(e) {
            var url = "{{route('products-share_img_status')}}";
            var data = {'share_img': $('#share_img').val(), 'id': id, 'language_id': $('#current_lang_id').val()};

            update_glob(url, data, function() {
                manage_load("{{route('products-load_img_form')}}", $('#image_form'), data);   
            });
            
        });

        $(document).off('click', '#attach_images-btn').on('click', '#attach_images-btn', function (e) {
            e.preventDefault();
            var container = $('#manage_modal > .container-2');
            manage_load_modal("{{route('products-attach_image_form')}}", container, {'id': id, 'language_id': $('#current_lang_id').val()}, true, 'post', function() {
                $('#load_images_from_gallery-btn').click();
            });
        });
    });
</script>