@extends('layout.default')

@section('title', 'Admin - Sign Un')

@section('content')
<main class="mn-inner container ">
    <div class="valign">
          <div class="row">
              <div class="col s12 m6 l4 offset-l4 offset-m3">
                  <div class="card white darken-1">
                      <div class="custom-color-1" style="border-radius: 3px 3px 0 0;">
                        <div class="card-content ">
                            <div class="col l12">
                              <h2 class="white-text">{{trans('auth.signup.header')}}</h2>
                            </div>
                            <div class="clear"></div>
                        </div>
                      </div> 
                      <div class="card-content" style="border-radius: 0 0 3px 3px;">
                           <div class="row">
                               <form class="col s12" id="register-form">
                                   <div class="input-field col l6 s12">
                                       <input id="firstname" type="text" class="validate" name="firstname" value="{{ old('firstname') }}">
                                       <label for="firstname">{{trans('auth.signup.fields.firstname')}}</label>
                                   </div>
                                   <div class="input-field col l6 s12">
                                       <input id="lastname" type="text" class="validate" name="lastname" value="{{ old('lastname') }}">
                                       <label for="lastname">{{trans('auth.signup.fields.lastname')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                       <input id="email" type="email" class="validate" name="email">
                                       <label for="email">{{trans('auth.signup.fields.email')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                       <input id="phone" type="text" class="validate" name="phone">
                                       <label for="phone">{{trans('auth.signup.fields.phone')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                       <input id="city" type="text" class="validate" name="city">
                                       <label for="city">{{trans('auth.signup.fields.city')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                      <select class="" style="width: 100%" name="country">
                                          <option value="">{{trans('main.misc.any')}}</option>
                                          @foreach($countries as $country)
                                              <option value="{{$country->id}}" >{{$country->countryName}}</option>
                                          @endforeach
                                      </select>
                                      <label>{{trans('auth.signup.fields.country')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                    <select multiple name="region[]">
                                      <option value="" disabled selected>{{trans('main.misc.any')}}</option>
                                      @foreach($regions as $region)
                                          <option value="{{$region->id}}" >{{$region->name}}</option>
                                      @endforeach
                                    </select>
                                    <label>{{trans('auth.signup.fields.region')}}</label>
                                  </div>
                                   <div class="input-field col l6 s12">
                                       <input id="password" type="password" class="validate" name="password">
                                       <label for="password">{{trans('auth.signup.fields.password')}}</label>
                                   </div>
                                   <div class="input-field col l6 s12">
                                       <input id="password_confirmation" type="password" class="validate" name="password_confirmation">
                                       <label for="password_confirmation">{{trans('auth.signup.fields.repeat_password')}}</label>
                                   </div>
                                   
                                    <div class="col s12 right-align m-t-sm">
                                       <a href="{{route('admin-login')}}" class="waves-effect waves-grey btn-flat" id="sign-in-btn" >{{trans('auth.signup.other.login_page_btn')}}</a>
                                       <button class="waves-effect waves-light btn custom-main-color-1" id="sign-up-btn" data-reset="{{trans('auth.signup.fields.register')}}">{{trans('auth.signup.fields.register')}}</button>
                                   </div>
                               </form>
                          </div>
                      </div>
                  </div>
              </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select').material_select();

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

    /*$('.select-multiple').select2({
        placeholder: 'Select Multiple States'
    });*/

    $.validator.addMethod("regexName", function(value) {
        return /^[a-z0-9 .\ ,\-]+$/i.test(value); 
    },"Incorrect name format, Invalid characters used.");

    $.validator.addMethod("countrySelect", function(value) {
        return (value != 'select country'); 
    },"test.");

    $.validator.addMethod("regexPhone", function(value) {
        return /(^\+(\s?)(\d(\s?))+)$|(^(\d(\s?))+)$/.test(value); 
    },"Incorrect phone format, Invalid characters used.");

      function handleData( responseData ) {
        // Do what you want with the data
        //console.log(responseData);
        //return responseData;
    }

    $("#register-form").validate({
        errorElement: 'span',
        errorClass: 'error',
        rules: {
            firstname: {
                required:true,
                regexName:true,
                minlength:1,
                maxlength:100,
                
            },
            lastname: {
                required:true,
                regexName:true,
                minlength:1,
                maxlength:100,
                
            },
            email: {
                required: true,
                email: true,
                maxlength:100,
                //uniqueEmail: true,
                remote: {
                    url: "{{URL::route('check-unique-email')}}",
                    type: "post"
                 }
            },
            password: {
                required: true,
                minlength: 5,
                maxlength:42
            },
            password_confirmation: {
                required: true,
                equalTo: '#password'
            },
            /*flat: {
                required:true
            },
            city: {
                required:true,
                minlength:3,
                maxlength:60
            },
            phone: {
                required:true,
                minlength:6,
                maxlength:15,
                regexPhone:true
            },
            country: {
                required:  function(element) {
                    if( $("#your_country").val() =='-1' || $("#your_country").val() =='select country' ){
                      return false;
                    } else {
                      return true;
                    }
                }
            },
            captcha_cnt: {
                required: true,
            },
            postcode: {
                minlength:3,
                maxlength:7
            }*/
        },
        messages: { 
          
        },
        submitHandler: function(form) {
              btn_loading($('#sign-up-btn'));
                var formData = $(form).serialize();
                $.ajax({
                    url: "{{ route('register-post') }}",
                    type: "post",
                    datatype: 'json',
                    data: formData,
                    error: function(a,b,c) {
                        btn_reset($('#sign-up-btn'));
                    },
                    success: function(data){
                        btn_reset($('#sign-up-btn'));
                        if (!data.success) {
                            msg_text = data.message;

                            Materialize.toast( data.message, 10000);
                            /*if (!data.message_arr.user_stat) {
                                msg_text += '<div>incorrect username</div>';
                            }
                            if (!data.message_arr.pass_stat) {
                                msg_text += '<div>incorrect password</div>';
                            }*/

                            /*$('.login-form').find('.inline-message-box').removeClass('success-message').addClass('error-message').html(msg_text).slideDown(10, function() {
                                $('.remodal').css({'max-height': $('.remodal').find('.login-form').height()});
                            });*/

                        } else {

                            msg_text = data.message;

                            //Materialize.toast( data.message, 400);
                            /*$('.login-form').find('.inline-message-box').removeClass('error-message').addClass('success-message').html(msg_text).slideDown(10, function() {
                                $('.remodal').css({'max-height': $('.remodal').find('.login-form').height()});
                            });
*/                          
                            $timer = null;
                            clearTimeout($timer);
                            var ms = 0; // milliseconds
                            $timer = setTimeout(function() {
                                window.location.replace("{{ route('admin-login') }}");
                            }, ms);
                            
                        }
                    }
                });
                return false;
        }
    });
</script>
@stop
