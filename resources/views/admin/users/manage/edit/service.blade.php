<form id="edit_product_form">
    <input type="hidden" name="id" id="id" value="{{$product->id}}">
    <input type="hidden" name="op_type" id="op_type" value="services">
    <div class="modal-content row">
        <h4>{{trans('main.misc.edit')}} {{$product->code}}</h4>
        <div class="row">
            <div class="col s12 deep-purple lighten-5 wrapper-custom-1" style="margin-bottom: 10px;">
                
            <!-- <input type="hidden" name="stbs[0]" value="0"> -->

                <div class="collection row" style="margin-top: 1rem; border: none; overflow: visible;">
                    @if($product->status()->count() > 0 || 1)
                        <div class="col l12 m12 s12">
                            <h6 style="color: #9e9e9e;">STBs that will be assigned to this service</h6><br>
                        </div>
                        @php($service_stbs = $product->get_service_stbs()->get())
                        @foreach($stbs as $stb)
                            @if($stb->mac)
                                <div class="col l6 m6 s12">
                                    <div class="collection-item">
                                        <div class="input-field" style="margin-top: 0;">
                                            <span class="input-wrapper-span-1">
                                                @php($serv = $stb->get_stb_service()->first())
                                                @php($checked_1 = ($service_stbs->count() > 0 && $service_stbs->find($stb->id) ? true : false))
                                                @php($disabled_1 = (($serv && $serv->id != $product->id) || in_array($product->products_statuse_id, [5]) || $product->status()->count() > 0 ? true : false))
                                                <input type="checkbox" id="stb-{{$stb->id}}" name="stbs[{{$stb->id}}]" @if($disabled_1) disabled @endif @if($checked_1) checked @endif class="select_stb limit">
                                                <label for="stb-{{$stb->id}}" style="left:0;">{{$stb->code}} @if($stb->mac)(<b class="green-text">{{$stb->mac}}</b>)@endif</label>
                                            </span>
                                            @if($checked_1 && $disabled_1) 
                                                <input type="hidden" name="stbs[{{$stb->id}}]" value="on">
                                            @endif

                                            @if(isset($check_op['answer'][$stb->code]))
                                                {{-- @php($package_switch_status = (!$check_op['answer'][$stb->code]['SWITCH']))
                                                @php($pck_serv_1 = $check_op['answer'][$stb->code]['SERVICEOFF'])
                                                @foreach($pck_serv_1 as $pck_serv)
                                                    <span class="new badge @if($package_switch_status) blue @else orange @endif">{{ $pck_serv['BILLSERVICE'] }}</span>
                                                @endforeach --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="col l12 m12 s12">
                            <div class="inline-info-block z-depth-1">
                                <h6>STBs that will be assigned to this service after automatic activation</h6>
                            </div>
                        </div>
                        @php($service_stbs = $product->user->get_all_stbs()->find(explode(',', $product->order_stb)))
                        @foreach($stbs as $stb)
                            <div class="col l6 m6 s12">
                                <div class="collection-item">
                                    <div class="input-field" style="margin-top: 0;">
                                        <span class="input-wrapper-span-1">
                                            @php($checked_1 = ($service_stbs->count() > 0 && $service_stbs->find($stb->id) ? true : false))
                                            @php($disabled_1 = false)
                                            <input type="checkbox" id="order_stb-{{$stb->id}}" name="order_stb[{{$stb->id}}]" @if($disabled_1) disabled @endif @if($checked_1) checked @endif class="select_stb limit">
                                            <label for="order_stb-{{$stb->id}}" style="left:0;">{{$stb->code}} @if($stb->mac)(<b class="green-text">{{$stb->mac}}</b>)@endif</label>
                                        </span>
                                        @if($checked_1 && $disabled_1) 
                                            <input type="hidden" name="order_stbs[{{$stb->id}}]" value="on">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @php($pre_attached_stb = $product->user->get_all_stbs()->find($product->s_code))
                    @if($product->s_code && $pre_attached_stb)
                        <div class="col l12 m12 s12">
                            <div class="inline-info-block">
                                Pre-attached STB: {{ $pre_attached_stb->code }}
                            </div>
                        </div>
                    @endif

                    @if($product->order_stb || 1)
                        <input type="hidden" name="preselected_order_stb" value="1">
                        @php($order_stbs_arr = explode(',', $product->order_stb))
                        @php($all_stbs = $product->user->get_all_stbs()->get())
                        <div class="col l12 m12 s12">
                            <div class="inline-info-block row white">
                                <div class="col l12 m12 s12">
                                    <label>STBs selected during order:</label>
                                    <select multiple name="order_stb[]" id="order_stb" data-limit="3" @if($product->status()->count() > 0) disabled @endif>
                                        <option value="" disabled selected>{{trans('main.misc.none')}}</option>
                                        @foreach($all_stbs as $ostb)
                                            <span class="badge-1">{{ $ostb->code }}</span>
                                            <option value="{{$ostb->id}}" @if(in_array($ostb->id, $order_stbs_arr)) selected @endif>{{$ostb->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="clear"></div>
            <div class="deep-purple lighten-5 wrapper-custom-1" style="padding: 0.75rem 0.75rem 2rem 0.75rem; margin-bottom: 1rem;">
                <div class="input-field col l12 m12 s12">
                    <textarea id="comment" class="materialize-textarea" length="512" style="height: 21.2px;" name="comment">{{ $product->comment }}</textarea>
                    <label for="comment" class="@if($product->comment) active @endif">{{ trans('main.misc.comment') }}</label>
                    <span class="character-counter" style="float: right; font-size: 12px; height: 1px;"></span>
                </div>
                <div class="input-field col l6 m6 s12" style="">
                    <input id="date_end" type="date" class="datepicker" value="@if($product->date_end){{ date('Y-m-d', $product->date_end) }}@endif" name="date_end" style="margin: 0;">
                    <label for="date_end" class="input-label-1">Дата окончания службы</label>
                </div>
                @if($product->product->yearly)
                    <div class="input-field col l6 m6 s12" style="">
                        <input id="date_month_end" type="date" class="datepicker" value="@if($product->date_month_end){{ date('Y-m-d', $product->date_month_end) }}@endif" name="date_month_end" style="margin: 0;">
                        <label for="date_month_end" class="input-label-1">Дата окончания подписки</label>
                    </div>
                @endif
                <div class="clear"></div>
            </div>
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
        var datepicker = $('.modal .datepicker').pickadate({
            selectMonths: true,
            selectYears: 100,
            format: 'yyyy-mm-dd',
        });
        var picker = datepicker.pickadate('picker');
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
        $('#edit_product_form').find('label.input-label-1').addClass('active');

        $(document).on('click', '.select_stb.limit', function() {
            if ($('.select_stb.limit:checked').length > 3) {
                $(this).prop('checked', false);
                var $toastContent = $('<span><i class="material-icons">warning</i> Only 3 STBs are alowed in multiroom service</span>');
                Materialize.toast($toastContent, 5000, 'rounded warning-bck custom-toast-1');
            }
            // if ($(this).val().length > $(this).data('limit')) {
            //     $(this).val($(this).data('value'));
            //     $('select').material_select();
            //     var $toastContent = $('<span><i class="material-icons">error</i> Only 3 STBs are alowed in multiroom service</span>');
            //     Materialize.toast($toastContent, 5000, 'rounded warning-bck custom-toast-1');
            // } else {
            //     $(this).data('value', $(this).val());
            // }
        });

        $(document).on('change', 'select#order_stb', function() {
            if ($(this).val().length > $(this).data('limit')) {
                $(this).val($(this).data('value'));
                $('select#order_stb').material_select();
                var $toastContent = $('<span><i class="material-icons">error</i> Only 3 STBs are alowed in multiroom service</span>');
                Materialize.toast($toastContent, 5000, 'rounded warning-bck custom-toast-1');
            } else {
                $(this).data('value', $(this).val());
            }
        });
    });
</script>