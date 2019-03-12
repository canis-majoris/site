<div class="">
    <ul class="collapsible" data-collapsible="expandable" style="margin: 0;">
        <li class="active" id="item_options" style="display: none;">
            <div class="collapsible-header"><i class="material-icons">settings_applications</i>{{ trans('main.permissions.items.collection_headers.settings') }}</div>
            <div class="collapsible-body">
                <form id="edit_role_settings" class="edit_menu_item-form">
                    <input type="hidden" name="id" id="i_id" value="">
                    <div class="col l12 m12 s12">
                        <div class="input-field col s12 l6 m6">
                            <input id="i_name" type="text" class="validate" name="name">
                            <label class="input-label-1" for="i_name">{{ trans('main.permissions.items.settings.name') }}</label>
                        </div>
                        <div class="input-field col s12 l6 m6">
                            <input id="i_display_name" type="text" class="validate" name="display_name">
                            <label class="input-label-1" for="i_display_name">{{ trans('main.permissions.items.settings.display_name') }}</label>
                        </div>
                        <div class="input-field col s12">
                            <span>{{ trans('main.permissions.items.settings.description') }}</span>
                            <div id="i_description_wrapper">
                                <textarea id="i_description" class="materialize-textarea" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="row">
                        <div class="col l12"> 
                            <div class="input-field col s12">
                                <button class="waves-effect waves-light btn pull-left btn-custom-width side-menu-parameters-save-btn parameters-save-btn" id="edit_menu_item-save-btn" type="submit" data-reset="<i class='material-icons'>check</i> {{trans('main.buttons.save')}}"><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </li>
        <li>
            <div class="collapsible-header active"><i class="material-icons">accessibility</i>{{ trans('main.permissions.items.collection_headers.items') }}</div>
            <div class="collapsible-body table-holder-1" style="padding:20px;">
                <div class="add_role_permission-container load-filter-container" style="display: none;">
                    <div class="row col l5 m7 s6" style="padding-left: 0;">
                        <div class="input-field col l10 m10 s10" style="padding-left: 0;">
                            <select class="js-states browser-default role_permissions-select select-custom" tabindex="-1" style="width: 100%" id="role_permissions">
                                <option value="default" selected></option>
                            </select>
                            <label style="top: -15px; font-size: 0.8rem; left:0;">{{ trans('main.misc.attach_item_to_menu_list') }}</label>
                        </div>
                        <div class="input-field col l2 m2 s2">
                            <button class="waves-effect waves-light btn pull-left btn-custom-wi" id="role_permissions-save-btn" type="button" data-reset="<i class='material-icons'>check</i> save" style="margin-top: 5px;" disabled><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div id="products_table-container">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </li>
    </ul>
</div>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $('.role_permissions-select').select2();

    $(document).on('change', '.role_permissions-select', function (e) {
        if (val = $(this).val()) {
            $('#role_permissions-save-btn').prop('disabled', false);
        } else $('#role_permissions-save-btn').prop('disabled', true);
    });
</script>

