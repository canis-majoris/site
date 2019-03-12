<div class="">
    <form id="manage_text_form" class="edit_item-form white">
        <input type="hidden" name="current_menu_item_id" value="@if($texts_type != null){{$texts_type->id}}@endif">
        <div class="modal-content row">
            <div class="editable-block-1">
                <div class=" bred-c-holder-1">
                    <i class="material-icons">settings_applications</i><strong style="font-size:25px;">@if(!$text){{trans('main.misc.new')}} {{trans('main.content.manage.form.text')}}@else @if($translated != null){{trans('main.misc.edit')}} {{$translated->name}}@endif @endif
                    </strong>

                    <button class="brdc-return-to-list-btn-1 right btn-flat" href="#" style="font-size: .75rem;" type="button"><i class="material-icons" style="margin-right: .5rem;">keyboard_backspace</i> @if($texts_type != null){{$texts_type->name}}@endif</button>
                </div>
                @if(!$text)
                    <input type="hidden" name="text_id" value="">
                @else
                    <input type="hidden" name="text_id" value="{{$text->id}}">
                @endif
                <input type="hidden" name="language_id" id="si_language_id" value="{{$language_id}}">
            </div>
            <div class="custom-divider-1"></div>
            <div class="editable-block-1">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="p_n_name" type="text" class="validate" name="name" value="@if($translated != null){{$translated->name}}@endif">
                        <label for="p_n_name" class="@if($translated != null && $translated->name) active @endif">{{ trans('main.content.texts.manage.form.name') }}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="p_n_name_trans" type="text" class="validate" name="name_trans" value="@if($text != null){{$text->name_trans}}@endif">
                        <label for="p_n_name_trans" class="@if($text != null && $text->name_trans) active @endif">{{ trans('main.content.texts.manage.form.name_trans') }}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="p_n_source" type="text" class="validate" name="source" value="@if($translated != null){{$translated->source}}@endif">
                        <label for="p_n_source" class="active">{{ trans('main.content.texts.manage.form.source') }}</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="p_n_short_text" class="materialize-textarea" name="short_text" id="short_text">@if($translated != null){{$translated->short_text}}@endif</textarea>
                        <label for="p_n_short_text" class="@if($translated != null && $translated->short_text) active @endif">{{ trans('main.content.texts.manage.form.short_text') }}</label>
                    </div>
                    <div class="input-field col s12">
                        <p>{{ trans('main.content.texts.manage.form.tags') }}</p><br>
                        <select class="js-example-tokenizer js-states browser-default select2-1" multiple="multiple" tabindex="-1" style="width: 100%" id="tags" name="tags[]">
                            @if($text != null && $text->tags)
                                @php($tags_list = json_decode($text->tags, 1))
                                @foreach($tags_list as $tag)
                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="input-field col s12">
                        <span>{{ trans('main.content.texts.manage.form.text') }}</span>
                        <div >
                            <textarea id="p_n_text" class="materialize-textarea" name="text">@if($translated != null){{$translated->text}}@endif</textarea>
                        </div>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="p_n_watch" name="watch" @if($translated != null && $translated->watch == 1) checked="checked" @endif>
                            <label for="p_n_watch">{{ trans('main.content.texts.manage.form.public') }}</label>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col l12 m12 s12">
                         <ul class="collapsible" data-collapsible="expandable">
                            <li class="active" id="options">
                                <div class="collapsible-header @if($translated != null && $translated->img != '' && $translated->img != null && $translated->img != '[]') active @endif" style="background-color: rgb(247, 247, 247);"><i class="material-icons">insert_photo</i>{{ trans('main.misc.image') }}</div>
                                <div class="collapsible-body" style="background-color: rgb(247, 247, 247);">
                                    <!-- <div class="col s12">
                                        <div class="text-title">{{ trans('main.image_upload.title') }}</div>
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
                            <li class="active" id="options">
                                <div class="collapsible-header" style="background-color: rgb(247, 247, 247);"><i class="material-icons">search</i>{{ trans('main.seo.title') }}</div>
                                <div class="collapsible-body" style="background-color: rgb(247, 247, 247);">
                                    <div class="col s12">
                                        <div class="row">
                                            <div class="input-field col s12">
                                                <input id="p_n_seo_title" type="text" class="validate" name="seo_title" value="@if($translated != null){{$translated->seo_title}}@endif">
                                                <label for="p_n_seo_title" class="@if($translated != null && $translated->seo_title) active @endif">{{ trans('main.content.texts.manage.form.seo_title') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <textarea id="p_n_seo_description" class="materialize-textarea" name="seo_description" id="seo_description">@if($translated != null){{$translated->seo_description}}@endif</textarea>
                                                <label for="p_n_seo_description" class="@if($translated != null && $translated->seo_description) active @endif">{{ trans('main.content.texts.manage.form.seo_description') }}</label>
                                            </div>
                                            <div class="input-field col s12">
                                                <p>{{ trans('main.content.texts.manage.form.seo_keywords') }}</p><br>
                                                <select class="js-example-tokenizer js-states browser-default select2-1" multiple="multiple" tabindex="-1" style="width: 100%" id="seo_keywords" name="seo_keywords[]">
                                                    @if($translated != null && $translated->seo_keywords)
                                                        @php($keywords_list = json_decode($translated->seo_keywords, 1))
                                                        @foreach($keywords_list as $kw)
                                                            <option value="{{ $kw }}" selected>{{ $kw }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="clear"></div>
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
                        <button class="waves-effect waves-green btn item-parameters-save-btn parameters-save-btn right " id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
                        <button class="modal-action modal-close waves-effect waves-light btn-flat right brdc-return-to-list-btn-1" id="manage_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select:not(.select2-1)').material_select();
        $('select.select2-1').select2();
        $('.collapsible').collapsible();

        var id = $('.edit_item-form').find('input[name=text_id]').val() || null;

        $(".js-example-tokenizer").select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'Tags'
        });

        var data = {'id': id, 'language_id': $('#current_lang_id').val()};
        manage_load("{{route('texts-load_attached_images')}}", $('#load_selected_images'), data, true);

        $('#short_text').val('@if($translated != null && $translated->short_text){{$translated->short_text}}@endif');
        $('#short_text').trigger('autoresize');

        

        if(CKEDITOR.instances['p_n_text']) {
            delete CKEDITOR.instances['p_n_text'];
        }

        CKEDITOR.replace('p_n_text', {
            uiColor: '#E6E6E6',
            language: 'ru',
        });

        var url_sp = "{{route('texts-save')}}";
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
            var url = "{{route('texts-updateimg')}}";
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
            var url = "{{route('texts-share_img_status')}}";
            var data = {'share_img': $('#share_img').val(), 'id': id, 'language_id': $('#current_lang_id').val()};

            update_glob(url, data, function() {
                manage_load("{{route('texts-load_img_form')}}", $('#image_form'), data);   
            });
            
        });

        $(document).off('click', '#attach_images-btn').on('click', '#attach_images-btn', function (e) {
            e.preventDefault();
            var container = $('#manage_modal > .container-2');
            manage_load_modal("{{route('texts-attach_image_form')}}", container, {'id': id, 'language_id': $('#current_lang_id').val()}, true, 'post', function() {
                $('#load_images_from_gallery-btn').click();
            });
        });

    });
</script>