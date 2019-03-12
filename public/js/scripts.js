// (function() {

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

    // $('.dropdown-submenu > a').on("mouseover", function(e) {
    //     var submenu = $(this);
    //     $('.dropdown-submenu .dropdown-menu').removeClass('show');
    //     submenu.next('.dropdown-menu').addClass('show');
    //     e.stopPropagation();
    // });

    $('.navbar-nav li.dropdown-submenu > a, .navbar-nav li.w-menu > a').on("click", function(e) {
        if ($(window).width() <= 991) {
            let parent = $(this).closest('li');
            parent.siblings('.dropdown-submenu, .w-menu').removeClass('active');
            parent.toggleClass('active')
        }
    });

    // $('.dropdown').on("hidden.bs.dropdown", function() {
    //     // hide any open menus when parent closes
    //     $('.dropdown-menu.show').removeClass('show');
    // });



    // The function
    var background_image_parallax = function($object, multiplier){
      multiplier = typeof multiplier !== 'undefined' ? multiplier : 0.5;
        multiplier = 1 - multiplier;
      var $doc = $(document);
      $object.css({"background-attatchment" : "fixed"});
        $(window).scroll(function(){
          var from_top = $doc.scrollTop(),
              bg_css = 'center ' +(multiplier * from_top) + 'px';
          $object.css({"background-position" : bg_css });
      });
    };

    //Just pass the jQuery object
    // background_image_parallax($(".parallax-1"));

	function debouncer( func , timeout ) {
	   var timeoutID , timeout = timeout || 200;
	   return function () {
	      var scope = this , args = arguments;
	      clearTimeout( timeoutID );
	      timeoutID = setTimeout( function () {
	          func.apply( scope , Array.prototype.slice.call( args ) );
	      } , timeout );
	   }
	}


    
	$(window).on('load resize orientationchange',  debouncer(function (e) {

		$('.b-style').remove();

		//
			$('.c-anchor').map((i, el) => {
				let offset = $(el).position();

				console.log(offset.top, $('header').innerHeight())
				$(el).closest('.anchored').append('<div class="b-0 b-style" style="top: '+(offset.top - 87)+'px; height:'+$(el).innerHeight()+'px;"></div>');

				
			});



		setTimeout(() => {	
			$('.b-style').addClass('show');
		}, 50);
		
	}, 200));

	//optional second value for speed
	//background_image_parallax($(".box3"), 0.25);
    
    
// });