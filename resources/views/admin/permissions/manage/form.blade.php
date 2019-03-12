<form id="manage_permission_form" class="edit_item-form">
    <input type="hidden" name="current_menu_item_id" value="{{$role_id}}">
    <div class="modal-content row">
        <div>
            <div class="col s12">
                @if(!$permission)
                    <h4>{{trans('main.misc.new')}} {{trans('main.promos.manage.form.permission')}}</h4>
                    <input type="hidden" name="permission_id" value="">
                @else
                    <h4>{{trans('main.misc.edit')}} {{$permission->name}}</h4>
                    <input type="hidden" name="permission_id" value="{{$permission->id}}">
                @endif
                <h5 class="inner-header-5"><i class="material-icons">settings_applications</i>{{ trans('main.permissions.items.collection_headers.settings') }}</h5>
            </div>

            <div class="input-field col s12">
                <input id="i_name" type="text" class="validate" name="name" value="@if($permission != null) {{ $permission->name }} @endif">
                <label for="i_name">{{ trans('main.permissions.items.manage.form.name') }}</label>
            </div>
            <div class="input-field col s12">
                <input id="i_display_name" type="text" class="validate" name="display_name" value="@if($permission != null) {{ $permission->display_name }} @endif">
                <label for="i_display_name">{{ trans('main.permissions.items.manage.form.display_name') }}</label>
            </div>
            <div class="input-field col s12">
                <span>{{ trans('main.permissions.items.manage.form.description') }}</span>
                <div id="i_description_wrapper">
                    <textarea id="p_n_description" class="materialize-textarea" name="description">@if($permission != null){{$permission->description}}@endif</textarea>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat item-parameters-save-btn parameters-save-btn" id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat brdc-return-to-list-btn-1" id="manage_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">

    
    $(document).ready(function() {
        $('select').material_select();
        $('.collapsible').collapsible();

        $('#manage_save-cancel-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        if(CKEDITOR.instances['p_n_description']) {
            delete CKEDITOR.instances['p_n_description'];
        }

        CKEDITOR.replace('p_n_description', {
            uiColor: '#E6E6E6',
            language: 'ru',
        });


        var url_sp = "{{route('permissions-save')}}";
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
        });

        @if($permission)$('#manage_permission_form').find('.input-field').find('label').addClass('active');@endif
    });
</script>