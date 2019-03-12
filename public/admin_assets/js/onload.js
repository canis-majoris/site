$(document).ready(function() {
	$(document).on('click', '.global-message-wrapper .inline-dismiss-btn', function(e) {
		e.preventDefault();
		$(this).closest('.global-message-wrapper').slideUp(100, 'easeOutQuad');
		$('body').removeClass('with-global-message');
	});

	$(document).off('click', '#denied_save-cancel-btn').on('click', '#denied_save-cancel-btn', function(e) {
		$(this).closest('.modal').modal('close');
	});
});
