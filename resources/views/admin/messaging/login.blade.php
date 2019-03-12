<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messaging System</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="{{url()."/public/css/hover-min.css"}}" rel="stylesheet" type="text/css">
    <link href="{{url()."/public/css/messaging/style.css"}}" rel="stylesheet" type="text/css">
    <link href="{{url().'/bower_components/Materialize/bin/materialize.css'}}" rel="stylesheet" type="text/css">
    <link href="{{url().'/bower_components/Materialize/bin/materialize.js'}}" rel="stylesheet" type="text/css">
    <script src="{{url()."/bower_components/sweetalert2/dist/sweetalert2.min.js"}}"></script>
    <script src="{{url().'/bower_components/Materialize/bin/materialize.js'}}"></script>
    <script src="{{url()."/public/plugins/validator/dist/jquery.validate.js"}}"></script>
    <script src="{{url()."/public/plugins/validator/dist/additional-methods.min.js"}}"></script>
    @if(trans('main.indicator') == 'ru') 
      <script src="{{url().'/public/plugins/validator/src/localization/messages_ru.js'}}"></script>
    @endif
    
    <link rel="stylesheet" type="text/css" href="{{url()."/bower_components/sweetalert2/dist/sweetalert2.css"}}">
    <meta property="og:type"  content="website" />
    <meta property="og:image" content="{{asset("public/img/001.jpeg")}}" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 m6 l6 offset-m3 offset-l3">
                <div class="mes-box ">
                @if($mesType=='error')<div class="error-box animated fadeInDown"><span class="ic-caution"></span> {{$message}}</div>@endif  
                @if($mesType=='info') <div class="info-box animated fadeInDown">{!!$message!!}</div>@endif
                </div>
                <div class="login-panel panel panel-default animated fadeInDown blue-grey lighten-5 card">
                    <div class="panel-heading col l12 m12 s12">
                        <h3 class="panel-title">Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <div class="logo-holder custom-message-toast">
                        </div>
                        {!! Form::open(array('route' => 'messaging-login-post', 'method' => 'post', 'id' => 'login-form')) !!}
                            <fieldset style="border:none;">
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Email" name="email" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Passowrd" name="password" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit"  class="btn btn-lg btn-success btn-block right" id="login-submit">Login</button>
                            </fieldset>
                        {!! Form::close() !!}
                        <hr>
                        <fieldset>
                            <button type="button"  class="waves-effect waves-teal btn-flat" style="width:100%; background-color:#fff;" id="register_switch">Register</button>
                        </fieldset>
                    </div>
                </div>

                <div class="register-panel panel panel-default animated fadeInDown blue-grey lighten-5 card" style="display:none;">
                    <div class="panel-heading col l12 m12 s12">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body">
                        <div class="register-holder custom-message-toast">
                        </div>
                        {!! Form::open(array('route' => 'messaging-register-post', 'method' => 'post', 'id' => 'register-form')) !!}
                            <fieldset style="border:none;">
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="First name" name="firstname" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Last name" name="lastname" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Email" name="email" type="email" >
                                </div>
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Passowrd" name="password" type="password" value="" id="reg_password">
                                </div>
                                <div class="form-group">
                                    <input class="form-control validate" placeholder="Repeat passowrd" name="repeat_password" type="password" value="" id="reg_repeat_password">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit"  class="btn btn-lg btn-success btn-block right" id="register-submit">Register</button>
                            </fieldset>
                        {!! Form::close() !!}
                        <hr>
                        <fieldset>
                            <button type="button"  class="waves-effect waves-teal btn-flat" style="width:100%; background-color:#fff;" id="login_switch">Sign in</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">
    $("#login-form").validate({
        rules: {
            email: {
                required: true,
                email: true,
                maxlength:100,
                //uniqueEmail: true,
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
            //$('#login-submit').button('loading');
            // business logic...
            //$btn.button('reset')
            $.ajax({
                type: "POST",
                data : $(form).serialize(),
                cache: false,
                url: "{{url()."/"}}messaging/post-login",
                success: function(data){
                    if (data.success) {
                        Materialize.toast(data.message, 5000, 'custom-message-toast-success');
                        window.location.replace('{{url()."/"}}messaging/' + data.route);
                    } else {
                        Materialize.toast(data.message, 5000, 'custom-message-toast-error');
                    }
                }
            });
            return false;
        }
    });

    $("#register-form").validate({
        rules: {
            firstname:{
                required: true,
                minlength: 1,
                maxlength:100
            },
            lastname:{
                required: true,
                minlength: 1,
                maxlength:100
            },
            email: {
                required: true,
                email: true,
                maxlength:100,
                //uniqueEmail: true,
            },
            password: {
                required: true,
                minlength: 5,
                maxlength:42
            },
            repeat_password: {
                required: true,
                minlength: 5,
                maxlength:42,
                equalTo: "#reg_password"
            }
        },
        messages: { 
          
        },
        submitHandler: function(form) {
            //$('#login-submit').button('loading');
            // business logic...
            //$btn.button('reset')
            $.ajax({
                type: "POST",
                data : $(form).serialize(),
                cache: false,
                url: "{{url()."/"}}messaging/post-register",
                success: function(data){
                    if (data.success) {
                        Materialize.toast(data.message, 5000, 'custom-message-toast-success');
                        window.location.replace('{{url()."/"}}messaging/' + data.route);
                    } else {
                        Materialize.toast(data.message, 5000, 'custom-message-toast-error');
                    }
                }
            });
            return false;
        }
    });

    $('#register_switch').on('click', function() {
        $('.login-panel').slideUp(100, function() {
            $('.register-panel').slideDown(300);
        })
    });

    $('#login_switch').on('click', function() {
        $('.register-panel').slideUp(100, function() {
            $('.login-panel').slideDown(300);
        })
    });
</script>
</html>

