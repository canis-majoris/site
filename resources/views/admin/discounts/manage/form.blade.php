<form id="manage_discount_form">
    <div class="modal-content row">
        <div>
            <div class="col s12">
                @if(!$discount)
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.new')}} {{trans('main.discounts.manage.form.discount')}}</h5>
                    <input type="hidden" name="id" value="">
                @else
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$discount->code}}</h5>
                    <input type="hidden" name="id" value="{{$discount->id}}">
                @endif
                <h5>{{trans('main.misc.main_settings')}}</h5>
            </div>
            <div class="">
                <div class="input-field col s12">
                    <input id="d_code" type="text" class="validate" name="code" value="@if($discount != null){{$discount->code}}@endif" data-val="required->Введите текст;minlength->3->Количество символов в поле должно быть не меньше 3 символов">
                    <label for="d_code" class="@if($discount != null && $discount->code) active @endif">{{trans('main.misc.code')}}</label>
                </div>
                <div class="input-field col s12">
                    <input id="d_limit" type="text" class="validate" name="limit" value="@if($discount != null){{$discount->limit}}@else 0 @endif">
                    <label for="d_limit" class="active">{{trans('main.discounts.manage.form.limit')}}</label>
                </div>
                <div class="input-field col sl6 m6 s12">
                    <input id="d_date_start" type="date" class="datepicker" value="@if($discount != null){{date('Y-m-d', strtotime($discount->date_start))}}@else{{date('Y-m-d')}}@endif" name="date_start">
                    <label for="d_date_start" class="input-label-1 active">{{trans('main.discounts.manage.form.date_start')}}</label>
                </div>
                <div class="input-field col sl6 m6 s12">
                    <input id="d_date_end" type="date" class="datepicker" value="@if($discount != null){{date('Y-m-d', strtotime($discount->date_end))}}@endif" name="date_end">
                    <label for="d_date_end" class="input-label-1 active">{{trans('main.discounts.manage.form.date_end')}}</label>
                </div>
                <div class="input-field col sl6 m6 s12">
                    <span class="input-wrapper-span-1" style="padding-bottom: 50px;">
                        <input type="checkbox" id="d_status" name="status" @if($discount != null && $discount->status == 1) checked @endif>
                        <label for="d_status" style="left: 3px">{{trans('main.misc.status')}}</label>
                    </span>
                </div>
                <div class="divider"></div>
                <div class="discount_types_holder col s12">
                    <div class="deep-purple lighten-5 wrapper-custom-1 row" style="border-radius: 2px; padding: 10px;">
                        <div class="discount_parameters_holder">
                            <div class="input-field col sl6 m6 s12">
                                <select class="custom-select type_select" style="width: 100%" name="type" id="d_type">
                                    <option value="1" @if($discount != null && $discount->type == 1) selected @endif>{{trans('main.discounts.manage.form.all_discount')}}</option>
                                    <option value="2" @if($discount != null && $discount->type == 2) selected @endif>{{trans('main.discounts.manage.form.packet_discount')}}</option>
                                    <option value="3" @if($discount != null && $discount->type == 3) selected @endif>{{trans('main.discounts.manage.form.products_discount')}}</option>
                                </select>
                                <label>{{trans('main.discounts.manage.form.discount_type')}}</label>
                            </div>
                            <div>
                                <div class="input-field col sl6 m6 s12 discount_parameter_input_1" style="@if($discount != null && $discount->products_percent != null) display: none; @else display: block; @endif">
                                    <input id="d_percent" type="text" maxlength="35" class="validate masked" name="percent" value="@if($discount != null){{$discount->percent}}@else 0 @endif" >
                                    <label for="d_percent" class="active">{{trans('main.discounts.manage.form.discount_percent')}}</label>
                                </div>
                                <div class="input-field col sl6 m6 s12 discount_parameter_select_1" style="@if($discount != null && $discount->products_percent != null) display: block; @else display: none; @endif">
                                    <select class="custom-select" style="width: 100%" name="type_discount" id="d_type_discount">
                                        <option value="1" @if($discount != null && $discount->type_discount == 1) selected @endif>{{trans('main.discounts.manage.form.discount_on_one')}}</option>
                                        <option value="2" @if($discount != null && $discount->type_discount == 2) selected @endif>{{trans('main.discounts.manage.form.discount_on_all')}}</option>
                                    </select>
                                    <label>{{trans('main.discounts.manage.form.discount_on_one')}}</label>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="disount_additional_parameters" style="@if($discount != null && $discount->products_percent != null) display: block; @else display: none; @endif     background-color: rgb(255, 255, 255);padding-bottom: 1px;border-radius: 2px;">
                                {{-- <div class="col l6 m12 s12 right">
                                    <ul class="tabs z-depth-1 right" style="width: 100%; box-shadow: none;">
                                        @foreach($languages as $language)
                                            <li class="tab"><a data-langid="{{$language->id}}" class="lang-selector-1 @if($language->id == 1) active @endif">{{$language->language}}</a></li>
                                        @endforeach
                                    </ul>
                                    <div class="clear"></div>
                                </div> --}}
                                <div class="col l6 m12 s12">
                                    <h5 class="header">{{trans('main.discounts.manage.form.discount_products')}}</h5>
                                </div>
                                <div class="row">
                                    <div class="col s12">
                                        <table id="products-datatable" class="display datatable-example">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat parameters-save-btn" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="discount_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('.custom-select').material_select();
        var datepicker = $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 100,
            format: 'yyyy-mm-dd',
        });
        var picker = datepicker.pickadate('picker');
        $('.collapsible').collapsible();

        var language_id = {{ $languages->first()->id }};;
        var discount_id = "@if($discount != null) {{$discount->id}} @else 0 @endif";
        var products_datatable = $('#products-datatable').DataTable({
            "serverSide":true,
            "processing":true,
            "ajax": {
                url: "{{route('discounts-show-products')}}",
                
                //type: 'POST',
                data: function (d) {
                    d.language_id = language_id;
                    d.discount_id = discount_id;
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                },
            },
            //order: [[0, 'desc']],
            language: {
                searchPlaceholder: 'Search records',
                sSearch: '',
                sLengthMenu: 'Show _MENU_',
                sLength: 'dataTables_length',
                oPaginate: {
                    sFirst: '<i class="material-icons">chevron_left</i>',
                    sPrevious: '<i class="material-icons">chevron_left</i>',
                    sNext: '<i class="material-icons">chevron_right</i>',
                    sLast: '<i class="material-icons">chevron_right</i>' 
                }
            },
            "columns": [
                {"name":"id","data":"id","orderable":false,"searchable":false,'visible':false},
                {"name":"","data":"select","orderable":false,"searchable":false},
                {"name":"name","data":"name","orderable":false,"searchable":true},
            ],
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            }, 
            "dom":"<'row'<'col-sm-12'tr>>",
            /*'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [0,1,2,3,5,6,7,8] }
            ],
            "order": [[ 0, "desc" ]],*/
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            'iDisplayLength': 25,
            'responsive': true,
        });

        function initDataTableCtrl(container) {
            //$('.custom-select', container).material_select();
            $('select[name="products-datatable_length"]').fadeIn(0);
            $('ul.tabs').tabs();
            $(".masked").inputmask('percentage', {
                removeMaskOnSubmit: true,
                autoUnmask: true
            });
            // PrettyPrint
            $('pre').addClass('prettyprint');
            prettyPrint();
        }

        $(document).on('change click', '.product_percent_check', function (e) {

            var percent_input = $(this).closest('.percent_select_holder').find('.product_percent_value');
            //alert(percent_input.val());
            if ($(this).is(':checked')) {
                percent_input.prop('disabled', false);
            } else {
                percent_input.prop('disabled', true);
            }
        });

        /*$(document).on('change', '.discount_type_switch', function (e) {
            $('ul.tabs').tabs();
            var global_percent_input = $('#d_percent');
            var discount_parameters = $('.discount_parameters_holder');
            if ($(this).is(':checked')) {
                global_percent_input.prop('disabled', true);
                discount_parameters.slideDown(200);
            } else {
                global_percent_input.prop('disabled', false);
                discount_parameters.slideUp(200);
            }
        });*/

        $(document).on('change', '.type_select', function (e) {
            var type = $(this).val();
            var discount_parameter_input_1 = $('.discount_parameter_input_1');
            var discount_parameter_select = $('.discount_parameter_select_1');
            var disount_additional_parameters = $('.disount_additional_parameters');
            console.log(type)
            if (type == 2 || type == 3) {
                discount_parameter_input_1.fadeOut(200, function (e) {
                    discount_parameter_select.fadeIn(200);
                });

                disount_additional_parameters.slideDown(200);
            } else if(type == 1) {
                discount_parameter_select.fadeOut(400, function (e) {
                    discount_parameter_input_1.fadeIn(200);
                });

                disount_additional_parameters.slideUp(400);
            }
        });

        $(document).off('click', '.lang-selector-1').on('click', '.lang-selector-1', function (e) {
            language_id = $(this).data('langid');
            delay(function(){
                products_datatable.draw();
            }, 200 );
        });

        /*$('#manage_save-btn').off('click').on('click', function (e) {
            $('#manage_discount_form').submit();
            $(this).unbind('click');
        });*/

        var url_sp = "{{route('discounts-save')}}";
        var form_sp = $('#manage_discount_form');
        var from_sp_vaidator = {
            rules: {
                code: {
                    required: true
                },
                date_end: { greaterThan: "#d_date_start" }
                /*date_start: {
                    required: true
                },
                date_end: {
                    required: true
                }*/
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator);

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

    });
</script>