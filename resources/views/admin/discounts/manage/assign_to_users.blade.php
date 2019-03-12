<form id="manage_discount_form">
    <div class="modal-content row">
        <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$discount->code}}</h5>
        <input type="hidden" name="id" value="{{$discount->id}}">
        <div>
            <div class="row">
                <div class="col s12 grey lighten-4" style="padding: 10px 0; margin: 10px 0; border-radius: 2px;">
                    <div class="col s12">
                        <h4>Dealers</h4>
                    </div>
                    <div class="input-field col l12 m12 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="select_all-dealers" name="select_all-dealers" >
                            <label for="select_all-dealers">select all available dealers</label>
                        </span>
                    </div>
                    <div class="col l12 m12 s12">
                        <select class="{{-- dealers_select multi-select2 --}}js-example-basic-multiple js-data-example-ajax js-states form-control custom-select-2" style="width: 100%" name="dealers_select[]" id="dealers_select" tabindex="-1"  multiple="multiple">
                            @foreach($all_dealers as $dealer)
                                <option value="{{$dealer->id}}" @if($discount->dealers()->find($dealer->id)) selected @endif @if($dealer->dealer_discounts()->count() && !$discount->dealers()->find($dealer->id)) disabled @endif>{{$dealer->username}}</option>
                            @endforeach
                        </select>
                        <label class="active">{{trans('main.discounts.manage.assign_to_user.dealers')}}</label>
                    </div>
                </div>
                <div class="col s12 grey lighten-4" style="padding: 10px 0; margin: 10px 0; border-radius: 2px;">
                    <div class="col s12">
                        <h4>Users</h4>
                    </div>
                    {{-- <div class="input-field col l12 m12 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="select_all-users" name="select_all-users" >
                            <label for="select_all-users">select all available users</label>
                        </span>
                    </div> --}}
                    <div class="col l12 m12 s12">
                        <select class="{{-- users_select multi-select2 --}}js-example-basic-multiple js-data-example-ajax js-states form-control custom-select-2" style="width: 100%" name="users_select[]" id="users_select" tabindex="-1"  multiple="multiple">
                            @foreach($all_users as $user)
                                <option value="{{$user->id}}" @if($discount->users()->find($user->id)) selected @endif @if($user->user_discounts()->count() && !$discount->users()->find($user->id)) disabled @endif>{{$user->username}} {{-- @if($tmp_dis = $user->dealer_discounts()->first()) ({{$tmp_dis->code}}) @endif --}}</option>
                            @endforeach
                        </select>
                        <label class="active">{{trans('main.discounts.manage.assign_to_user.users')}}</label>
                    </div>
                </div>
                {{-- <div class="">
                    <div class="input-field col l3 m6 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="select_all-users" name="select_all-users" >
                            <label for="select_all-users">select all users</label>
                        </span>
                    </div>
                    <div class="input-field col l9 m6 s12">
                        <select class="users_select multi-select1" style="width: 100%" name="users_select" id="users_select" multiple tabindex="-1">
                           
                        </select>
                        <label>{{trans('main.discounts.manage.assign_to_user.dealers')}}</label>
                    </div>
                </div> --}}
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat parameters-save-btn" id="manage_save-btn" type="button" data-reset="{{ trans('main.buttons.save') }}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="discount_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script>
<script type="text/javascript">
    //$('.multi-select2').material_select();
    //$('.multi-select1').select2();



    var dealers_select = $("#dealers_select").select2({
      ajax: {
        url: "{{route('discounts.dealers.load.free')}}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page,
            main_id: "{{$discount->id}}"
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          var results = [];
            if (data.items != null && data.items.length > 0) {

                $.each(data.items, function (index, item) {

                    results.push({
                        id: item.id,
                        text: item.email
                    });
                });
            }
            return {
                results: results
            };
        },
        cache: true
      },
      placeholder: "Search for a Creditor",
      // theme: "bootstrap",
       //allowClear: true,
      //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
      //minimumInputLength: 1,
      //templateResult: formatRepo, // omitted for brevity, see the source of this page
      //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });

    var users_select = $("#users_select").select2({
      ajax: {
        url: "{{route('discounts.users.load.free')}}",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page,
            id: "{{$discount->id}}"
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          var results = [];
            if (data.items != null && data.items.length > 0) {

                $.each(data.items, function (index, item) {

                    results.push({
                        id: item.id,
                        text: item.email
                    });
                });
            }
            return {
                results: results
            };
        },
        cache: true
      },
      placeholder: "Search for a Creditor",
      // theme: "bootstrap",
       //allowClear: true,
      //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
      //minimumInputLength: 1,
      //templateResult: formatRepo, // omitted for brevity, see the source of this page
      //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });


    var load_items_1 = function (url, select_box, container) {
        $.ajax({
          url: url,
          type: "post",
          datatype: 'json',
          data: {'id':"{{$discount->id}}"},
          error: function(a,b,c) {
             Materialize.toast( c, 4000);
          },
          success: function(data){
              if (!data.success) {
                  
              } else {
                select_box.select2("val", "");
                //$('.select2-selection__rendered').html('');
                time = 500;

                var selected_arr_1 = $.map(data.items, function(n, i) {
                   return n.id;
                });

                select_box.val(selected_arr_1);

                /*$.each(data.items, function (index, item) {
                    current_val_1 = select_box.find("option:contains('" + item.id  + "')");
                    console.log(item.username, current_val_1);
                    if(!current_val_1.length) {
                        $_item1 = $('<option selected>'+item.username+'</option>').val(item.id);
                        select_box.append($_item1);
                    } else {
                        current_val_1.prop('selected', true);
                    }
                    
                    select_box.select2();
                });*/
                select_box.select2();
              }
          },
          beforeSend: function() {
            haight = container.height();
            width = container.width();
            container.append('<div class="preloader-overlay" style="">' +
                '<div class="preloader-full" style="display: block;">' +
                    '<div class="progress">' +
                        '<div class="indeterminate"></div>' + 
                    '</div>' +
                '</div>' +
            '</div>');
          },
          ajaxComplete: function () {
                
          }
      });
    }


    $(document).ready(function() {

        $(document).off('click', '#select_all-dealers').on('click', '#select_all-dealers', function (e) {
            if ($(this).is(':checked')) {
                var url = "{{route('discounts.dealers.load.all_available')}}";
                var container = $('#dealers_select').closest('.col').find('.select2-selection__rendered');
                load_items_1(url, dealers_select, container);
            } else {
                dealers_select.select2("val", "");

            }
        });

        $(document).off('click', '#select_all-users').on('click', '#select_all-users', function (e) {
            if ($(this).is(':checked')) {
                var url = "{{route('discounts.users.load.free')}}";
                var container = $('#users_select').closest('.col').find('.select2-selection__rendered');
                load_items_1(url, users_select, container);
            } else {
                users_select.select2("val", "");
            }
        });
        
        //$('.regular-select').material_select();
        
        //$('.custom-select').material_select();
        var datepicker = $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 100,
            format: 'yyyy-mm-dd',
        });
        var picker = datepicker.pickadate('picker');
        $('.collapsible').collapsible();
        $('ul.tabs').tabs();

        /*$(document).on('change', '#dealers_select, #users_select', function (e) {
            $(this).off('change').on('change');
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

        var url_sp = "{{route('discounts.users.save')}}";
        var form_sp = $('#manage_discount_form');
        var from_sp_vaidator = {
            rules: {},
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