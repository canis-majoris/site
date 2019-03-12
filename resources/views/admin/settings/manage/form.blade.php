<form id="manage_settings_form" class="edit_item-form white">
    <input type="hidden" name="current_menu_item_id" value="@if($settings_type != null){{$settings_type->id}}@endif">
    <div class="modal-content row">
        <div class="editable-block-1">
            <div class=" bred-c-holder-1">
                <i class="material-icons">settings_applications</i><strong style="font-size:25px;">@if(!$translated){{trans('main.misc.new')}} {{trans('main.content.manage.form.settings')}}@else {{trans('main.misc.edit')}} <b>{{$translated->name}}</b> @endif
                </strong>

                <button class="brdc-return-to-list-btn-1 right btn-flat" href="#" style="font-size: .75rem;" type="button"><i class="material-icons" style="margin-right: .5rem;">keyboard_backspace</i> @if($settings_type != null){{$settings_type->name}}@endif</button>
            </div>
            @if(!$settings)
                <input type="hidden" name="settings_id" value="">
            @else
                <input type="hidden" name="settings_id" value="{{$settings->id}}">
            @endif
            <input type="hidden" name="language_id" id="si_language_id" value="{{$language_id}}">
        </div>
        <div class="custom-divider-1"></div>
        <div class="editable-block-1">
            <div class="row">
                <div class="input-field col s12">
                    <input id="p_n_name" type="text" class="validate" name="name" value="@if($translated != null){{$translated->name}}@endif">
                    <label for="p_n_name" class="input-label-1 @if($translated != null && $translated->name) active @endif">{{ trans('main.content.settings.manage.form.name') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_value" type="text" class="validate" name="value" value="@if($translated != null){{$translated->value}}@endif">
                    <label for="p_n_value" class="input-label-1 @if($translated != null && $translated->value) active @endif">{{ trans('main.content.settings.manage.form.value') }}</label>
                </div>
                <!-- <div class="input-field col s12">
                    <input id="p_n_category" type="text" class="validate" name="category" value="@if($translated != null){{$translated->category}}@endif">
                    <label for="p_n_category" class="active">{{ trans('main.content.settings.manage.form.category') }}</label>
                </div> -->
                <div class="input-field col s12">
                    <textarea id="p_n_text" class="materialize-textarea" name="text" id="text">@if($translated != null){{$translated->text}}@endif</textarea>
                    <label for="p_n_text" class="input-label-1 @if($translated != null && $translated->text) active @endif">{{ trans('main.content.settings.manage.form.text') }}</label>
                </div>
                <div class="input-field col s12">
                    <p>{{ trans('main.content.settings.manage.form.options') }}</p><br>
                    <select class="js-example-tokenizer js-states browser-default select2-1" multiple="multiple" tabindex="-1" style="width: 100%" id="options" name="options[]">
                        @if($translated != null && $translated->options)
                            @php($options_list = json_decode($translated->options, 1))
                            @foreach($options_list as $tag)
                                <option value="{{ $tag }}" selected>{{ $tag }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="input-field col s12">
                    <span>{{ trans('main.content.settings.manage.form.description') }}</span>
                    <div >
                        <textarea id="p_n_description" class="materialize-textarea" name="description">@if($translated != null){{$translated->description}}@endif</textarea>
                    </div>
                </div>
                <div class="input-field col s12">
                    <span class="input-wrapper-span-1">
                        <input type="checkbox" id="p_n_status" name="status" @if($translated != null && $translated->status == 1) checked="checked" @endif>
                        <label for="p_n_status">{{ trans('main.content.settings.manage.form.public') }}</label>
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
                    <button class="waves-effect waves-green btn item-parameters-save-btn parameters-save-btn right " id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
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
        $('select.select2-1').select2();

        var id = $('.edit_item-form').find('input[name=settings_id]').val() || null;

        $(".js-example-tokenizer").select2({
            options: true,
            tokenSeparators: [',', ' '],
            placeholder: 'With Tokenization'
        });

        var data = {'id': id, 'language_id': $('#current_lang_id').val()};
        manage_load("{{route('settings-load_attached_images')}}", $('#load_selected_images'), data, true);


        $('#text').val('@if($settings != null && $settings->text){{$settings->text}}@endif');
        $('#text').trigger('autoresize');

        $('.collapsible').collapsible();

       // var ckeditor = CKEDITOR.instances['p_n_description'];
        //CKEDITOR.instances.editor.removeAllListeners()
        //CKEDITOR.remove(ckeditor);
        //if (ckeditor) { ckeditor.destroy(true); }
        //
        if(CKEDITOR.instances['p_n_description']) {
            delete CKEDITOR.instances['p_n_description'];
        }

        CKEDITOR.replace('p_n_description', {
            uiColor: '#E6E6E6',
            language: 'ru',
        });

        var url_sp = "{{route('settings-save')}}";
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
        save_parameters(url_sp, form_sp, from_sp_vaidator, {'description':'p_n_description'}, [window.LaravelDataTables["dataTableBuilder"]], 'post', function(response) {
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
            var url = "{{route('settings-updateimg')}}";
            var name = $('#image_name').val();
            var data = {'id': id, 'name': name, 'language_id': $('#current_lang_id').val()}; 
            delete_image(url, data);
        });

        $(document).off('click', '#settings_save-cancel-btn').on('click', '#settings_save-cancel-btn', function (e) {
            e.preventDefault();
            var tmp_image_name = $('#image_name').val();
            if (tmp_image_name && !id) {
                var url = "{{route('file-delete')}}";
                var data = {'filename': tmp_image_name, 'action':'delete', 'directory': 'settings'};
                delete_image(url, data);
            }
            $('#manage_modal').modal('close');
        });

        $(document).off('change', '#share_img').on('change', '#share_img', function(e) {
            var url = "{{route('settings-share_img_status')}}";
            var data = {'share_img': $('#share_img').val(), 'id': id, 'language_id': $('#current_lang_id').val()};

            update_glob(url, data, function() {
                manage_load("{{route('settings-load_img_form')}}", $('#image_form'), data);   
            });
            
        });

        $(document).off('click', '#attach_images-btn').on('click', '#attach_images-btn', function (e) {
            e.preventDefault();
            var container = $('#manage_modal > .container-2');
            manage_load_modal("{{route('settings-attach_image_form')}}", container, {'id': id, 'language_id': $('#current_lang_id').val()}, true, 'post', function() {
                $('#load_images_from_gallery-btn').click();
            });
        });

        @if($settings)$('.edit_item-form').find('label.input-label-1').addClass('active');@endif


    });

</script>