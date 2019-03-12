<!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Title -->
        <title>Alpha | Responsive Admin Dashboard Template</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="{{ URL::asset('admin_assets/plugins/materialize/css/materialize.css') }}"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/material-preloader/css/materialPreloader.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/jquery-jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('admin_assets/plugins/codemirror/codemirror.css') }}" rel="stylesheet" type="text/css"/>       
        <link href="{{ URL::asset('admin_assets/plugins/codemirror/theme/material.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ URL::asset('admin_assets/plugins/fullcalendar/fullcalendar.min.css') }}" rel="stylesheet"> 
        <link href="{{ URL::asset('admin_assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet">  
        <link href="{{ URL::asset('admin_assets/plugins/select2/css/select2.css') }}" rel="stylesheet">
            
        <!-- Theme Styles -->
        <link href="{{ URL::asset('admin_assets/css/alpha.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ URL::asset('admin_assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
        
        <script src="{{ URL::asset('admin_assets/plugins/jquery/jquery-2.2.0.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/google-code-prettify/prettify.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/materialize/js/materialize.min.js') }}"></script>
        
        <script src="{{ URL::asset('plugins/validator/dist/jquery.validate.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/site.js') }}"></script>

        {{-- <script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script> --}}
       {{--  <script src="{{ URL::asset('admin_assets/js/pages/form-select2.js') }}"></script> --}}
        <script src="{{ URL::asset('admin_assets/js/pages/ui-dropdown.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/jszip.min.js') }}"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAK5VeEIALatoiino0zCd1gvCtSor9hanA&sensor=false&language=en&libraries=places" type="text/javascript"></script>
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body>
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
        <div class="mn-content">
            @yield('content')
        </div>
        <div class="left-sidebar-hover"></div>

        <!-- Javascripts -->
        
        <script src="{{ URL::asset('admin_assets/plugins/material-preloader/js/materialPreloader.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/alpha.min.js') }}"></script>
    </body>
</html>