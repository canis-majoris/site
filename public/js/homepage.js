$(document).ready(function (e) {
	$('#slider .owl-carousel').owlCarousel({
       center: true,
       items:1,
       loop:true,
       autoplay:true,
       autoplayTimeout:5000,
       autoplayHoverPause:true,
       margin:0,
       stagePadding: 0,
       navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
       responsive : {
        0: {
            nav: false,
            dots: true
        },
        // breakpoint from 480 up
        480: {
         nav: false,
         dots: true
        },
        // breakpoint from 768 up
        768: {
         nav: true,
         dots: false
        }
    }
   });
})