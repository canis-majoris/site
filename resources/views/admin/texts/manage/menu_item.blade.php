<!-- Add Menu Item -->
<form id="manage_texts_type_form" class="edit_menu_item-form">
    <div class="modal-content row">
        <div class="col s12">
            <h4>{{ trans('main.content.texts_types.manage.title') }}</h4>
        </div>
        <input type="hidden" name="language_id" value="{{ $language_id }}">
        <div class="input-field col s12">
            <input id="m_i_n_name" type="text" class="validate" name="name" value="" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
            <label for="m_i_n_name">{{ trans('main.content.texts_types.manage.form.name') }}</label>
        </div>
        <div class="input-field col s12">
            <input id="m_i_title" type="text" class="validate" name="title" value="" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
            <label for="m_i_title">{{ trans('main.content.texts_types.manage.form.title') }}</label>
        </div>
        <div class="input-field col s12">
            <span>{{ trans('main.content.texts_types.manage.form.description') }}</span>
            <div id="m_i_n_description_wrapper">
                <textarea id="m_i_description" class="materialize-textarea" name="description"></textarea>
            </div>
        </div>
        <div class="input-field col s12">
            <p class="">
                <input type="checkbox" id="m_i_n_watch" name="watch">
                <label for="m_i_n_watch">{{ trans('main.content.texts_types.manage.form.public') }}</label>
            </p>
        </div>
        <input type="hidden" id="current_lang_id" name="current_lang_id" value="1">
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat parameters-save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="save-cancell-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    CKEDITOR.replace('m_i_description', {
        uiColor: '#E6E6E6',
        language: 'ru'
    });

    $(document).ready(function() {
        $('#save-cancell-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        $('select').material_select();

        var url_sp = "{{route('texts_types-save')}}";
        var form_sp = $('#manage_texts_type_form');
        var from_sp_vaidator = {
            rules: {
                name: {
                    required: true
                },
                name_trans: {
                    maxlength: 512
                }
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator, {'text':'m_i_description'}, {}, 'post', function() {
            $('#clear_menu_items_filter-btn').click();
        });

        @if($menu_item != null)$('#manage_texts_type_form').find('label.input-label-1').addClass('active');@endif
    });
</script>
