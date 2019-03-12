<!-- Add Menu Item -->
<form id="manage_role_form" class="edit_menu_item-form">
    <div class="modal-content row">
        <div class="col s12">
            <h4>{{ trans('main.permissions.menu_items.manage.title') }}</h4>
        </div>
        <div class="input-field col s12">
            <input id="m_i_n_name" type="text" class="validate" name="name">
            <label for="m_i_n_name">{{ trans('main.permissions.menu_items.manage.form.name') }}</label>
        </div>
        <div class="input-field col s12">
            <input id="m_i_n_display_name" type="text" class="validate" name="display_name">
            <label for="m_i_n_display_name">{{ trans('main.permissions.menu_items.manage.form.display_name') }}</label>
        </div>
        <div class="input-field col s12">
            <span>{{ trans('main.permissions.menu_items.manage.form.description') }}</span>
            <div id="m_i_n_description_wrapper">
                <textarea id="m_i_description" class="materialize-textarea" name="description"></textarea>
            </div>
        </div>
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

        var url_sp = "{{route('roles-save')}}";
        var form_sp = $('#manage_role_form');
        var from_sp_vaidator = {
            rules: {
                name: {
                    required: true
                },
                display_name: {
                    required: true,
                    maxlength: 512
                }
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator, {'description':'m_i_description'}, {}, 'post', function() {
            role_id = null;
            $('#clear_menu_items_filter-btn').click();
        });

        @if($menu_item != null)$('#manage_role_form').find('label.input-label-1').addClass('active');@endif
    });
</script>