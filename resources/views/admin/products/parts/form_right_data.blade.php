<div class="" >
    <ul class="collapsible" data-collapsible="expandable" style="margin: 0; box-shadow: none;">
        <li class="active" id="item_options" style="display: none;">
            <div class="collapsible-header active"><i class="material-icons">settings_applications</i>{{ trans('main.catalog.products.collection_headers.settings') }}</div>
            <div class="collapsible-body">
                <form id="edit_menu_item_settings" class="edit_menu_item-form">
                    <input type="hidden" name="id" id="i_id" value="">
                    <input type="hidden" name="language_id" id="si_language_id" value="">
                    <div class="col s12">
                        <div class="col s12">
                            <a style="font-size: 16px;margin-bottom: 40px" href="" target="_blank"></a>
                        </div>
                    </div>
                    <div class="col l7 m12 s12">
                        <div class="input-field col l6 m6 s12">
                            <input id="i_name" type="text" class="validate" name="name" value="" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                            <label for="i_name">{{ trans('main.catalog.products.settings.name') }}</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <input id="i_name_trans" type="text" class="validate" name="name_trans" value="" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                            <label for="i_name_trans">{{ trans('main.catalog.products.settings.url') }}</label>
                        </div>
                    </div>
                    <div class="input-field col l2 m4 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="i_watch" name="watch">
                            <label for="i_watch">{{ trans('main.catalog.products.settings.public') }}</label>
                        </span>
                    </div>
                    <div class="col l3"> 
                        <div class="input-field col s12">
                            <button class="waves-effect waves-light btn pull-right btn-custom-width side-menu-parameters-save-btn parameters-save-btn" id="edit_menu_item_settings-save-btn" type="submit" data-reset="<i class='material-icons'>check</i> {{trans('main.buttons.save')}}"><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                        </div>
                    </div>  
                    {{-- <div class="input-field col l1 m4 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="i_is_group" name="is_group">
                            <label for="i_is_group">{{ trans('main.catalog.products.settings.group') }}</label>
                        </span>
                    </div> --}}
                    {{-- <div class="">
                        <div class="input-field col l2 m4 s12">
                            <div class="col s12">
                                <a class="modal-trigger waves-effect waves-light btn-flat btn-flat-custom-1 pull-right" href="#modal1">{{ trans('main.catalog.products.settings.text') }}</a>
                            </div>
                        </div>
                    </div> --}}
                    
                   {{--  <div class="clear"></div>
                    <div class="row">
                        <div class="col l12"> 
                            <div class="input-field col s12">
                                <button class="waves-effect waves-light btn pull-left btn-custom-width side-menu-parameters-save-btn parameters-save-btn" id="edit_menu_item_settings-save-btn" type="submit" data-reset="<i class='material-icons'>check</i> {{trans('main.buttons.save')}}"><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                            </div>
                        </div>      
                    </div> --}}
                    <div class="clear"></div>
                </form>
                <!-- Modal Structure -->
                <div id="modal1" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="input-field col s12">
                            <span>{{ trans('main.catalog.products.settings.text') }}</span>
                            <div id="i_description_wrapper">
                                <textarea id="i_description" class="materialize-textarea" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">{{trans('main.buttons.save')}}</a>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <div class="collapsible-header active"><i class="material-icons">view_list</i>{{ trans('main.catalog.products.collection_headers.products') }}</div>
            <div class="collapsible-body table-holder-1" style="padding:20px;">
                <div class="add_menu_product-container load-filter-container" style="display: none;">
                    <div class="row col l5 m7 s6" style="padding-left: 0;">
                        <div class="input-field col l10 m10 s10" style="padding-left: 0;">
                            <select class="js-states browser-default menu_product-select select-custom" tabindex="-1" style="width: 100%" id="menu_product">
                                <option value="default" selected></option>
                                {{-- @foreach($regular_users as $user)
                                    <option value="{{$user->id}}" @if($user->cash_payment_status) disabled @endif>{{$user->username}} ({{$user->code}})</option>
                                @endforeach --}}
                            </select>
                            <label style="top: -15px; font-size: 0.8rem; left:0;">{{ trans('main.misc.attach_item_to_menu_list') }}</label>
                        </div>
                        <div class="input-field col l2 m2 s2">
                            <button class="waves-effect waves-light btn pull-left btn-custom-wi" id="menu_product-save-btn" type="button" data-reset="<i class='material-icons'>check</i> save" style="margin-top: 5px;" disabled><i class='material-icons'>check</i> save</button>
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
    $('.menu_product-select').select2();

    $(document).on('change', '.menu_product-select', function (e) {
        if (val = $(this).val()) {
            $('#menu_product-save-btn').prop('disabled', false);
        } else $('#menu_product-save-btn').prop('disabled', true);
    });
</script>

