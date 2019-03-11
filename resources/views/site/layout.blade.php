
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

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
    <body class="bg-light <?=$loggedIn?>">
        @include('site.components.header')

        @yield('content')

        @include('site.components.footer')
    </body>
    <script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>

    @yield('js')

    <script type="text/javascript">
        var headerOffset = $('.sub-header').innerHeight();
              console.log(headerOffset)
        $(window).on('scroll load', function(e) {
        
            if (this.pageYOffset > headerOffset) {
                $('body').addClass('fixed-header');
            } else {
                $('body').removeClass('fixed-header');
            }
        });

        var l_offset = 0;
  
        $(document).on("click", ".hamburger", function() {
            $(this).toggleClass("is-active");
            $('html').toggleClass("opened-nav");
            
            l_offset = $('html').hasClass('opened-nav') ? 300 : 0;
        });

        $(document).ready(function (e) {
             AOS.init({
                // Global settings:
              disable: false, // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
              startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
              initClassName: 'aos-init', // class applied after initialization
              animatedClassName: 'aos-animate', // class applied on animation
              useClassNames: false, // if true, will add content of `data-aos` as classes on scroll
              disableMutationObserver: false, // disables automatic mutations' detections (advanced)
              debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
              throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)
              

              // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
              offset: 120, // offset (in px) from the original trigger point
              delay: 0, // values from 0 to 3000, with step 50ms
              duration: 400, // values from 0 to 3000, with step 50ms
              easing: 'ease', // default easing for AOS animations
              once: true, // whether animation should happen only once - while scrolling down
              mirror: false, // whether elements should animate out while scrolling past them
              anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation

             });
        });

        $('.dropdown-submenu > a').on("mouseover", function(e) {
            var submenu = $(this);
            $('.dropdown-submenu .dropdown-menu').removeClass('show');
            submenu.next('.dropdown-menu').addClass('show');
            e.stopPropagation();
        });

        $('.dropdown').on("hidden.bs.dropdown", function() {
            // hide any open menus when parent closes
            $('.dropdown-menu.show').removeClass('show');
        });



        // // The function
        // var background_image_parallax = function($object, multiplier){
        //   multiplier = typeof multiplier !== 'undefined' ? multiplier : 0.5;
        //     multiplier = 1 - multiplier;
        //   var $doc = $(document);
        //   $object.css({"background-attatchment" : "fixed"});
        //     $(window).scroll(function(){
        //       var from_top = $doc.scrollTop(),
        //           bg_css = 'center ' +(multiplier * from_top) + 'px';
        //       $object.css({"background-position" : bg_css });
        //   });
        // };

        // //Just pass the jQuery object
        // background_image_parallax($(".parallax-1"));
    </script>
</html>