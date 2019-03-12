@extends('layout.default')

@section('title', 'Admin - Sign In')

@section('content')
<main class="mn-inner container">
    <div class="valign">
          <div class="row">
              <div class="col s12 m6 l4 offset-l4 offset-m3">
                  <div class="card white darken-1">
                      <div class="custom-color-1" style="border-radius: 3px 3px 0 0;">
                        <div class="card-content ">
                            <div class="col l12 center" style="padding: 1rem 0;">
                                <img src="{{ URL::asset('img/site/logo.png') }}" class="responsive-img">
                              <!-- <h3 class="white-text">{{trans('auth.login.header')}}</h3> -->
                            </div>
                            <div class="clear"></div>
                        </div>
                      </div>
                      <div class="card-content" style="border-radius: 0 0 3px 3px;">
                           <div class="row">
                               <form class="col s12" id="login-form">
                                   <div class="input-field col s12">
                                       <input id="email" type="email" class="validate" name="email">
                                       <label for="email">{{trans('auth.login.fields.email')}}</label>
                                   </div>
                                   <div class="input-field col s12">
                                       <input id="password" type="password" class="validate" name="password">
                                       <label for="password">{{trans('auth.login.fields.password')}}</label>
                                   </div>
                                   <div class="col s12">
                                      <p class="p-v-xs">
                                          <input type="checkbox" id="remember" name="remember">
                                          <label for="remember">{{trans('auth.login.fields.remember_me')}}</label>
                                      </p>
                                     <div class="clear"></div>
                                     <br>
                                   </div>
                                   <div class="col s12 right-align m-t-sm">
                                       <a href="{{route('register')}}" class="waves-effect waves-grey btn-flat" id="sign-up-btn" >{{trans('auth.login.other.register_page_btn')}}</a>
                                       <button class="waves-effect waves-light btn custom-main-color-1" type="submit" data-reset="{{trans('auth.login.fields.login')}}" id="sign-in-btn">{{trans('auth.login.fields.login')}}</button>
                                   </div>
                               </form>
                          </div>
                      </div>
                  </div>
              </div>
        </div>
    </div>
</main>

<?php $info = null; ?>
@if(session()->has('ok'))
  <?php $info = session('ok'); ?>
@elseif(session()->has('error'))
  <?php $info = session('error'); ?>
@elseif(isset($info))
  <?php $info = $info ?>
@endif

<script type="text/javascript">
    $(window).on('load', function() {
      $timer = null;
      clearTimeout($timer);
      var ms = 2000; // milliseconds
      $timer = setTimeout(function() {
          Materialize.toast("{{ $info }}", 10000);
      }, ms);
      
    }) 
  </script>


<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.validator.addMethod("regexName", function(value) {
        return /^[a-z0-9 .\ ,\-]+$/i.test(value); 
    },"Incorrect name format, Invalid characters used.");

    $("#login-form").validate({
        errorElement: 'span',
        rules: {
            email: {
                required: true,
                email: true,
                maxlength:100,
            },
            password: {
                required: true,
                minlength: 5,
                maxlength:42
            }
        },
        messages: { 
          
        },
        submitHandler: function(form) {
          btn_loading($('#sign-in-btn'));
          var formData = $(form).serialize();
          $.ajax({
              url: "{{ route('admin-login-post') }}",
              type: "post",
              datatype: 'json',
              data: formData,
              error: function(a,b,c) {
                 btn_reset($('#sign-in-btn'));
              },
              success: function(data){
                  btn_reset($('#sign-in-btn'));
                  if (!data.success) {
                      msg_text = data.message;

                      Materialize.toast( data.message, 10000);

                  } else {

                      msg_text = data.message;

                      Materialize.toast( data.message, 400);
                      $timer = null;
                      clearTimeout($timer);
                      var ms = 0; // milliseconds
                      var redirect = data.redirect != null ? data.redirect : "{{ route('home-index') }}";
                      $timer = setTimeout(function() {
                          window.location.replace(redirect);
                      }, ms);
                  }
              }
          });
          return false;
        }
    });
</script>

@stop
