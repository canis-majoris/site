<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Title -->
        <title>Administration Panel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Tvoyo TV admin dashboard" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Gigi Khomeriki" />
        <link rel="icon" type="image/png"  href="{{ URL::asset('img/site/favicon.png') }}">
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="{{ URL::asset('admin_assets/plugins/materialize/css/materialize.css') }}"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/material-preloader/css/materialPreloader.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/jquery-jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/codemirror/codemirror.css') }}" rel="stylesheet" type="text/css"/>       
        <link href="{{ URL::asset('admin_assets/plugins/codemirror/theme/material.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ URL::asset('admin_assets/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"> 
        <link href="{{ URL::asset('admin_assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet">  
        <link href="{{ URL::asset('admin_assets/plugins/datatables/css/jquery.dataTables.css') }}" rel="stylesheet">  
        <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet">  
        <link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/bgrins-spectrum/spectrum.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/dropzone/basic.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/css/scrollbar.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/google-code-prettify/prettify.css') }}" rel="stylesheet" type="text/css"/>
        <!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/animate.css') }}" /> -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin_assets/fonts/brush_script_georgian.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin_assets/fonts/bpg_arial_2009.css') }}" />  
        <!-- Theme Styles -->
        <link href="{{ URL::asset('admin_assets/css/alpha.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ URL::asset('admin_assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css">

        <script src="{{ URL::asset('admin_assets/plugins/jquery/jquery-2.2.0.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/materialize/js/materialize.js') }}"></script>
        <!-- <script src="{{ URL::asset('admin_assets/js/bootstrap.min.js') }}"></script> -->
        <script src="{{ URL::asset('admin_assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/site.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/ajax.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
        <script src="{{ URL::asset('admin_assets/plugins/ckeditor/ckeditor.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/ckeditor/lang/ka.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/ckeditor/config.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/bgrins-spectrum/spectrum.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/dropzone/dropzone.min.js') }}"></script>
        <link href="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.css') }}" rel="stylesheet"> 
        <link href="{{ URL::asset('admin_assets/css/croppie.css') }}" rel="stylesheet"> 
        <script src="{{ URL::asset('admin_assets/plugins/material-preloader/js/materialPreloader.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/google-code-prettify/prettify.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/nestable/jquery.nestable.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/alpha.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/pages/miscellaneous-nestable.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/pages/form-input-mask.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/chart.js/chart.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/pages/form-select2.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/pages/ui-dropdown.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/jszip.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/jquery.countdown.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/scrollbar.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
        <script src="{{ URL::asset('admin_assets/js/ResizeSensor.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/ElementQueries.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/jquery.synctranslit.min.js') }}"></script>
        <!-- <script src="{{ URL::asset('plugins/synctranslit_0.0.7/js/jquery.synctranslit.min.js') }}"></script> -->



       <!--  <script src="{{ URL::asset('resources/assets/js/require.js') }}"></script> -->
        <!-- <script src="{{ URL::asset('resources/assets/js/app.js') }}"></script> -->
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAK5VeEIALatoiino0zCd1gvCtSor9hanA&sensor=false&language=en&libraries=places" type="text/javascript"></script>
       <!--  <script src="{{ URL::asset('admin_assets/plugins/dropzone/dropzone-amd-module.min.js') }}"></script> -->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <svg height="0" style="display: none;">
          <defs>
            <filter id="wherearemyglasses" x="0" y="0">
            <feGaussianBlur in="SourceGraphic" stdDeviation="15"/>
            </filter>
          </defs>
          <defs>
            <filter id="foundmyglasses" x="0" y="0">
            <feGaussianBlur in="SourceGraphic" stdDeviation="0"/>
            </filter>
          </defs>
        </svg>

        <!-- This makes the current user's id available in javascript -->
        @if(!auth()->guest())
            <script>
                window.Laravel = {};
                window.Laravel.userId = <?php echo auth()->user()->id; ?>
            </script>
        @endif

    </head>
    <body class="search-app @if (\Session::has('global-message')) with-global-message @endif">
        <div class="loader-bg"></div>
        <div class="loader">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-spinner-teal lighten-1">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                    <div class="circle"></div>
                    </div><div class="circle-clipper right">
                    <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mn-content fixed-sidebar">
            @include('admin.parts.header')
            @include('admin.parts.search_results')
            @if (\Session::has('global-message'))
                <div class="global-message-wrapper {{ \Session::get('global-message')['type'] }}">
                    <div class="message-container-1">{!! \Session::get('global-message')['message'] !!}</div>
                    <a class="inline-dismiss-btn right"><i class="material-icons">close</i></a>
                </div>
            @endif
            @yield('content')
        </div>
        <div class="left-sidebar-hover"></div>

        <script src="{{ URL::asset('admin_assets/js/onload.js') }}"></script>   
    </body>
</html>