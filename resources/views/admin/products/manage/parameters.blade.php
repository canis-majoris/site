<form id="manage_product_parameters_form">
    <input type="hidden" name="menu_item_id" value="{{$menu_item_id}}">
    <div class="modal-content row">
        <div>
            <div class="col s12">
                @if(!$product)
                    <h4>{{trans('main.misc.new')}} {{trans('main.promos.manage.form.product')}}</h4>
                    <input type="hidden" name="product_id" value="">
                @else
                    <h4>{{trans('main.misc.edit')}} {{$product->name}}</h4>
                    <input type="hidden" name="product_id" value="{{$product->id}}">
                @endif
                <h5 class="inner-header-5"><i class="material-icons">settings_applications</i>{{ trans('main.catalog.products.manage.title') }}</h5>
            </div>
            <div class="">
                <div class="input-field col s12">
                    <input id="p_n_price" type="text" maxlength="35" class="validate" name="price" value="@if($product != null){{$product->price}}@else 0 @endif" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                    <label for="p_n_price" class="active">{{ trans('main.catalog.products.manage.form.price') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_weight" type="text" class="validate" name="weight" value="@if($product != null){{$product->weight}}@else 0 @endif">
                    <label for="p_n_weight" class="active">{{ trans('main.catalog.products.manage.form.weight') }}</label>
                </div>
                <div class="input-field col s12">
                    <input id="p_n_ind" type="text" class="validate" name="itemindex" value="@if($product != null){{$product->ind}}@endif">
                    <label for="p_n_ind" class="active">{{ trans('main.catalog.products.manage.form.index') }}</label>
                </div>
            </div>
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
            <div class="col s12" style="background-color: rgba(63, 81, 181, 0.07); border-radius: 2px; padding: 5px 0.75rem; margin-top: 15px; @if($product != null && $product->is_service==1) display: block; @else  display: none; @endif" id="dayswrap">
                <div class="input-field col s12">
                    <input id="p_n_per_month_count" value="@if($product && $product->per_month_count != null) {{$product->per_month_count}} @endif"  name="per_month_count" type="text" maxlength="12" data-val="range->0->9999999->(0-9999999)" class="validate">
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
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat products-manage_save-btn" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="product_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('select').material_select();
        $('.collapsible').collapsible();
        CKEDITOR.replace('p_n_text', {
            uiColor: '#E6E6E6',
            language: 'ru'
        });

        var url_sp = "{{route('product-save-parameters')}}";
        var form_sp = $('#manage_product_parameters_form');
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
        save_parameters(url_sp, form_sp, from_sp_vaidator, {'text':'p_n_text'});
    });
</script>