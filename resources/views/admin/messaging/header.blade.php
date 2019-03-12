@section(header)
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messaging System</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="{{url()."/public/css/hover-min.css"}}" rel="stylesheet" type="text/css">
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
@stop