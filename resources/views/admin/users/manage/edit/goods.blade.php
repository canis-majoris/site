<form id="edit_product_form">
    <input type="hidden" name="id" id="id" value="{{$product->id}}">
    <input type="hidden" name="op_type" id="op_type" value="goods">
    <div class="modal-content row">
        <h4>{{trans('main.misc.edit')}} {{$product->code}}</h4>
        <div class="row">
            <div class="deep-purple lighten-5 wrapper-custom-1" style="padding: 0.75rem 0.75rem 2rem 0.75rem; margin-bottom: 1rem; ">
                <div class="input-field col l12 m12 s12">
                    <textarea id="comment" class="materialize-textarea" length="512" style="height: 21.2px;" name="comment">{{ $product->comment }}</textarea>
                    <label for="comment" class="@if($product->comment) active @endif">{{ trans('main.misc.comment') }}</label>
                    <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            {{-- LOGS --}}
            <div>
                <ul class="collapsible" data-collapsible="expandable">
                    <li class="active">
                        <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                            <h5><i class="material-icons">list</i> {{trans('main.orders_products.manage.show.log')}}</h5>
                        </div>
                        <div class="collapsible-body table-holder-narrow">
                            <div class="row" style="margin-bottom: 0;">
                                <div class="">
                                    <div class="white">
                                        @include('users.products.logs.logs')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
            {{-- IMAGE --}}
            @php($prod_1 = $product->product()->first()->translated()->first())
            @php($p_img = null)
            @if($prod_1 && $prod_1->img != null && $prod_1->img != '')
                @php($p_img = json_decode($prod_1->img, true))
            @endif
            @if($p_img)
                <div>
                    <ul class="collapsible" data-collapsible="expandable">
                        <li class="active">
                            <div class="collapsible-header" style="padding: 0.5rem 1rem;">
                                <h5><i class="material-icons">photo_camera</i> {{trans('main.orders_products.manage.show.img')}}</h5>
                            </div>
                            <div class="collapsible-body custom-color-1 darken-1">
                                <div class="row">
                                    <div class="col s12">
                                        <div class="" style="text-align: center;">
                                            <img src="{{url('/img/products').'/'.$p_img[0]}}"  alt="{{ $product->name }}" class="product_image_holder-inline materialboxed responsive-img" data-caption="{{ $product->name }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
            @endif
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat parameters-save-btn" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="edit_product_save-cancel-btn" type="button">{{trans('main.buttons.close')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '#edit_product_save-cancel-btn', function (e) {
            $('#manage_modal').modal('close');
        });

        $('.modal select').material_select();
        $('.modal .collapsible').collapsible();
        $('.modal input').focus();
        $('.materialboxed').materialbox();

        var url = "{{route('orders_products.edit.save')}}";
        var form = $('#edit_product_form');
        save_parameters(url, form);
    });
</script>