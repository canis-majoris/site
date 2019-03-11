
$(document).ready(() => {

	let sidebarOpen = false;
	
	$('#navigation_sidebar').click(function(){
		if($(this).attr('id') === 'navigation_sidebar'){
			sidebarOpen = false;
			$(this).removeClass('opened');
		}
	})
	
	$('#bars').click(function(){
		if(sidebarOpen){
			sidebarOpen = false;
			$('#navigation_sidebar').removeClass('opened');
		}else{
			sidebarOpen = true;
			$('#navigation_sidebar').addClass('opened');
		}
	});

	$('#times').click(function(){
		if(sidebarOpen){
			$('#navigation_sidebar').removeClass('opened');
			sidebarOpen = false;
		}
	})

	AOS.init();
	
});
