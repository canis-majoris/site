
<!-- Stored in resources/views/layouts/app.blade.php -->
<!doctype html>
<html>
    <head>
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/swiper/swiper.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/animate.css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=1.0.1') }}"> -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/hamburger.css') }}">
        <link rel="stylesheet" href="{{ asset('../node_modules/hover.css/css/hover.css') }}">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.js"></script>

        <!-- <script src="{{ asset('assets/js/jQuery/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/popper/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/swiper/swiper_.js') }}"></script> -->
        <!-- <script src="{{ asset('js/app.js') }}"></script> -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

        @yield('head')

        <script type="text/javascript">
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
        </script>
    </head>
    @php
        $loggedIn = '';
        if(session('loggedUser')) {
            $loggedIn = 'loggedin';
        }
    @endphp
    <body class="<?=$loggedIn?>">
        @include('site.components.header')

        @yield('content')

        @include('site.components.footer')
    </body>
    @include('site.components.login_form')
    <script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>

    @yield('js')

    <script type="text/javascript">
        
    </script>
</html>