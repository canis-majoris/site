<form id="add_user_form" class="edit_item-form">
    <div class="modal-content row">
        <div class="col s12">
            <h5 class="inner-header-5"><i class="material-icons">add_box</i> {{trans('main.users.manage.form.new_user')}}</h5>
            <h5>{{trans('main.misc.main_settings')}}</h5>
        </div>
        <div class="">
            <div class="row">
                <div class="input-field col s12">
                    <input id="username" type="text" class="validate" value="" name="username">
                    <label for="username" class="input-label-1">{{trans('main.users.manage.form.username')}}</label>
                </div>
            </div>
        </div>
        <div class="col l6 s12">
            <div class="row white">
                <div class="col s12">
                    <h4 class="card-title">{{ trans('main.users.manage.form.headers.personal_info') }}</h4>
                </div>
                <div class="row">
                    <div class="col s12" style="padding: 1rem 0;">
                        <div class="col">{{trans('main.users.manage.form.gender.header')}}:</div>
                        <div class="col l4 m4 s12">
                            <input name="gender" type="radio" id="is_male" value="male">
                            <label for="is_male">{{trans('main.users.manage.form.gender.male')}}</label>
                            <div class="clear"></div>
                        </div>
                        <div class="col l4 m4 s12">
                            <input name="gender" type="radio" id="is_female" value="female">
                            <label for="is_female">{{trans('main.users.manage.form.gender.female')}}</label>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="input-field col s12">
                        <input type="text" class="validate" name="name" id="name" value="">
                        <label for="name" class="input-label-1">{{trans('main.users.manage.form.name')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="birth_date" type="date" class="datepicker" value="" name="bith_date">
                        <label for="birth_date" class="input-label-1">{{trans('main.users.manage.form.birth_date')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="email" type="email" class="validate" value="" name="email">
                        <label for="email" class="input-label-1">{{trans('main.users.manage.form.email')}}</label>
                    </div>
                </div>
            </div>
            <div class="row white">
                <div class="col s12">
                    <h4 class="card-title">{{ trans('main.users.manage.form.headers.accress') }}</h4>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" class="validate" name="flat" id="flat" value="">
                        <label for="flat" class="input-label-1">{{trans('main.users.manage.form.address')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="region" type="text" class="validate" name="region" value="">
                        <label for="region" class="input-label-1">{{trans('main.users.manage.form.region')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="postcode" type="text" class="validate" name="postcode" value="">
                        <label for="postcode" class="input-label-1">{{trans('main.users.manage.form.postcode')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <select class="" style="width: 100%" name="country">
                            <option value="">-</option>
                            @foreach($countries as $country)
                                <option value="{{$country->id}}" >{{$country->countryName}}</option>
                            @endforeach
                        </select>
                        <label>{{trans('main.users.manage.form.country')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="city" type="text" class="validate" name="city" value="">
                        <label for="city" class="input-label-1">{{trans('main.users.manage.form.city')}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l6 s12">
            <div class="row white">
                <div class="col s12">
                    <h4 class="card-title">{{ trans('main.users.manage.form.headers.contact_info') }}</h4>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" class="validate" name="phone" id="phone" value="">
                        <label for="phone" class="input-label-1">{{trans('main.users.manage.form.phone')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="mobile" type="text" class="validate" name="mobile" value="">
                        <label for="mobile" class="input-label-1">{{trans('main.users.manage.form.mobile')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="password" type="password" class="validate" name="password" value="">
                        <label for="password" class="input-label-1">{{trans('main.users.manage.form.password')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="password_confirmation" type="password" class="validate" name="password_confirmation" value="">
                        <label for="password_confirmation" class="input-label-1">{{trans('main.users.manage.form.repeat_password')}}</label>
                    </div>
                    <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="activated" name="activated">
                            <label for="activated" style="left:0;">{{trans('main.users.manage.form.activated')}}</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row white">
                <div class="col s12">
                    <h4 class="card-title">{{ trans('main.users.manage.form.headers.dealer_settings') }}</h4>
                </div>
                <div class="row">
                    <div class="input-field col s12 row">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="is_diller" name="is_diller">
                            <label for="is_diller" style="left:0;">{{trans('main.users.manage.form.is_dealer')}}</label>
                        </span>
                    </div>
                    <div class="input-field col s12">
                        <input id="percent" type="text" class="validate" name="percent" value="">
                        <label for="percent" class="input-label-1">{{trans('main.users.manage.form.percent')}} </label>
                    </div>
                    <div class="input-field col s12">
                        <input id="percent_first" type="text" class="validate" name="percent_first" value=""> 
                        <label for="percent_first" class="input-label-1">{{trans('main.users.manage.form.percent_first')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <!-- <button class="waves-effect waves-green btn-flat right" id="save-user-parameters-btn" type="submit" data-reset="save">SAVE</button>
        <a class="waves-effect waves-light btn-flat right modal-action modal-close" id="add_user_save-calcel-btn">cancel</a> -->

        <button class="waves-effect waves-green btn-flat item-parameters-save-btn parameters-save-btn" id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="manage_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#manage_save-cancel-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        function initMap() {
            var options = {
                types: ['(cities)']
            };
            var input = document.getElementById('city');
            var autocomplete = new google.maps.places.Autocomplete(input, options);

            //    var input2 = document.getElementById('city2');
            //    var autocomplete2 = new google.maps.places.Autocomplete(input2, options);
        }
        initMap();

        $('select').material_select();

        var datepicker = $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 100,
            format: 'yyyy-mm-dd',
        });

        url_au = "{{ route('users-add') }}";
        form_au = $('#add_user_form');
        un_username_url = "{{ route('user-check-unique-username') }}";
        un_email_url = "{{ route('user-check-unique-email') }}";

        var from_sp_vaidator = {
            rules: {
                username: {
                    required:true,
                    minlength:3,
                    maxlength:30,
                    remote: {
                        url: un_username_url,
                        type: "post"
                    }
                },
                code: {
                    required:true,
                    minlength:10,
                    maxlength:30,
                },
                phone: {
                    required:true,
                    minlength:10,
                    maxlength:30,
                },
                name: {
                    required: true,
                    minlength:1,
                    maxlength:100,
                },
                bith_date: {
                    minlength:2,
                    maxlength:100,
                },
                flat: {
                    minlength:2,
                    maxlength:100,
                },
                region: {
                    minlength:2,
                    maxlength:100,
                },
                postcode: {
                    minlength:2,
                    maxlength:100,
                },
                email: {
                    required: true,
                    minlength:2,
                    maxlength:100,
                    email: true,
                    remote: {
                        url: un_email_url,
                        type: "post"
                    }
                },
                phone: {
                    minlength:2,
                    maxlength:100,
                },
                mobile: {
                    minlength:2,
                    maxlength:100,
                },
                password: {
                    required: true,
                    minlength:6,
                    maxlength:100,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
        }

        save_parameters(url_au, form_au, from_sp_vaidator, {}, [window.LaravelDataTables["dataTableBuilder"]]);


       // add_user(url_au, form_au, un_username_url, un_email_url);

        /*$('#add_user_form').find('label.input-label-1').addClass('active');*/
    });
</script>