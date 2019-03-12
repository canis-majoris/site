@extends('layout.main')

@section('title', 'Admin - Messaging')

@section('content')
    <script>
       
    </script>

    <?php $ruDisp = 'none';  $enDisp = 'none'; $kzDisp = 'none';?>
    @if(trans('main.indicator') == 'ru')
        <?php $ruDisp = 'block';?>
    @elseif(trans('main.indicator') == 'en')
        <?php $enDisp = 'block';?>
    @elseif(trans('main.indicator') == 'kz')
        <?php $kzDisp = 'block';?>
    @endif

    <main class="mn-inner">
        <div class="search-header" style="margin-bottom: 15px;">
            <div class="card card-transparent no-m">
                <div class="card-content no-s">
                    <div class="z-depth-1 search-tabs">
                        <div class="search-tabs-container">
                            <div class="col s12 m12 l12">
                                <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                    <div class="col l6 m6 s12">
                                        <div class=" bred-c-holder-1" >
                                            <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('messaging.index')}}" style="font-size:25px;">{{trans('dashboard.sidebar.list.tools.messagging')}}</a>
                                        </div>
                                    </div>
                                    <div class="col s12 m6 l6 hide-on-med-and-down" style="margin-top: 15px;">
                                        <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="row no-m-t no-m-b">
                <div class="col s12 m10 l8">
                    <div class="card">
                        <div class="card-content">
                            <div class="load_layer_light-1" style="display:none;">
                            </div>
                            <form class="" id="messaging_form">
                              <div class="row">
                                <div class="input-field col s12 m3 l2">
                                    <span class="input-wrapper-span-1">
                                        <input type="checkbox" id="send-to-all" name="send_to_all"/>
                                        <label for="send-to-all" class="input_label_main">Отправить всем</label>
                                    </span>
                                </div>
                                <div class="input-field col s12 m9 l10">
                                  <textarea id="mac_address" type="text" class="validate materialize-textarea" name="mac_address" ></textarea>
                                  <label for="mac_address" class="input_label_main">MAC-адрес</label>
                                  <div id="error_cont-mac_address"></div>
                                </div>
                                <div class="input-field col l12 m12 s12">
                                  <textarea id="message_ru" class="materialize-textarea" name="message_ru" length="512"></textarea>
                                  <label for="message_ru"class="input_label_main" >Сообщение (Russian)</label>
                                </div>
                                <div class="input-field col l12 m12 s12">
                                  <textarea id="message_en" class="materialize-textarea" name="message_en" length="512"></textarea>
                                  <label for="message_en" class="input_label_main">Сообщение (English)</label>
                                </div>
                                <div class="clear"></div>
                                <div class="input-field col l12 m12 s12">
                                    <button type="submit"  class="btn btn-lg btn-success btn-block right icon_container-tiny" id="message-submit"><i class="tiny material-icons">input</i> {{ trans('main.misc.send') }}</button>
                                </div>
                              </div>
                              <div class="preloader-wrapper big active" style="display:none;" id="sendMessagePreloader">
                                <div class="spinner-layer spinner-blue-only">
                                  <div class="circle-clipper left">
                                    <div class="circle"></div>
                                  </div><div class="gap-patch">
                                    <div class="circle"></div>
                                  </div><div class="circle-clipper right">
                                    <div class="circle"></div>
                                  </div>
                                </div>
                              </div>
                            </form>
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

        $(function(){
            $(document).ready(function() {
                $('#send-to-all').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#mac_address').prop('disabled', true);
                        $('#mac_address').closest('.input-field').removeClass('has-error').addClass('has-success');
                        $('#error_cont-mac_address').fadeOut(0);
                    } else {
                        $('#mac_address').prop('disabled', false);
                    }
                })
            });

            $.validator.addMethod("regexMac", function(value) {
                return /^([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})$/i.test(value); 
            },"Incorrect format of MAC");


            $("#messaging_form").validate({
                errorClass: 'error-custom',
                errorElement: 'span',
                errorContainer: '#error_cont-mac_address',
                rules: {
                    name: {
                        required:true,
                        regexName:true,
                        minlength:3,
                        maxlength:32,
                        
                    },
                    mac_address: {
                        required: {       
                            depends: function(element) {
                                return !$("#send-to-all").is(':checked');
                            }
                        },
                        //regexMac:true,
                        //maxlength:50,
                        remote: {
                            url: "{{URL::route('check-mac-address')}}",
                            type: "post"
                         }
                    },
                    message_ru: {
                        required: true,
                        minlength: 4,
                        maxlength: 512
                    },
                    message_en: {
                        required: true,
                        minlength: 4,
                        maxlength: 512
                    },
                },
                errorPlacement: function(error, element) {
                    $('#error_cont-' + element.attr('id')).append(error);
                   //Materialize.toast(error, 5000, 'custom-message-toast-error');
                },
                highlight: function(element) {   // <-- fires when element has error
                    $(element).closest('.input-field').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function(element) { // <-- fires when element is valid
                    $(element).closest('.input-field').removeClass('has-error').addClass('has-success');
                },
                messages: { 
                  mac_address: {
                    remote: 'No such MAC found',
                    required: 'Please enter MAC address'
                  } 
                },
                submitHandler: function(form) {
                    swal({
                      title: 'Are you sure?',
                      text: 'Your message will be send immediately',
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Send',
                      closeOnConfirm: true
                    }).then((result) => {
                        $.ajax({
                            type: "POST",
                            data : $(form).serialize(),
                            cache: false,
                            url: "{{URL::route('send-message')}}",
                            beforeSend: function(){
                                $('.load_layer_light-1').fadeIn(100);
                                $('#sendMessagePreloader').fadeIn(100);
                            },
                            complete: function(){
                                $('#sendMessagePreloader').fadeOut(100);
                                $('.load_layer_light-1').fadeOut(100);
                            },
                            success: function(data){
                                console.log(data);
                                if (data.success == false) {
                                    Materialize.toast(data.message, 5000, 'custom-message-toast-error');
                                } else {
                                    Materialize.toast(data.message, 5000, 'custom-message-toast-success');
                                }
                            }
                        });
                    });
                    
                    return false;
                }
            });
        });
    </script>




@stop