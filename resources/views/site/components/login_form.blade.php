 <!-- Modal structure -->
<div id="modal" style="display: none;">
	<button data-izimodal-close="" class="icon-close"><i class="fa fa-times" aria-hidden="true"></i></button>
	<div class="p-5">
	    <header class="pb-4">
	        <h4>Sign In</h4>
	    </header>
	    <div class="sections">
	    	<form class="login-form row" id="popup_account">
	    		<div class="form-group col-12">
				    <input type="text" class="form-control" id="username"  placeholder="Username" name="username">
				</div>
				<div class="form-group col-12">
				    <input type="password" class="form-control" id="password-new"  placeholder="Password" name="password">
				</div> 
				<div class="col-12 col-lg-6 col-md-6">
					<div class="form-check"> 
					  <input class="form-check-input" type="checkbox" value="" id="rememberMeCheck">
					  <label class="form-check-label" for="rememberMeCheck">
					    Remember me
					  </label>
					</div>
				</div>
				<div class="col-12 col-lg-6 col-md-6 text-right">
					<span class="c-inline-href"><a href="/forgot-password">Forgot Password?</a></span>
				</div>
				<div class="col-12 col-lg-6 col-md-6">
					<div class="success-message"></div>
				</div>
				<div class="col-12 mt-3">
					<button type="submit" id="login_sbmt" class="c-button c-color-1 hvr-shutter-out-vertical hover-c-color-1 w-100 login-form-submit">Sign In</button>
				</div>

		    </form>
		</div>
    </div>
</div>

<script type="text/javascript">
	$("#modal").iziModal({
	    title: '',
	    subtitle: '',
	    headerColor: '#88A0B9',
	    background: null,
	    theme: '',  // light
	    icon: null,
	    iconText: null,
	    iconColor: '',
	    rtl: false,
	    width: 400,
	    top: null,
	    bottom: null,
	    borderBottom: true,
	    padding: 0,
	    radius: 3,
	    zindex: 999,
	    iframe: false,
	    iframeHeight: 400,
	    iframeURL: null,
	    focusInput: true,
	    group: '',
	    loop: false,
	    arrowKeys: true,
	    navigateCaption: true,
	    navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
	    history: false,
	    restoreDefaultContent: false,
	    autoOpen: 0, // Boolean, Number
	    bodyOverflow: false,
	    fullscreen: false,
	    openFullscreen: false,
	    closeOnEscape: true,
	    closeButton: true,
	    appendTo: 'body', // or false
	    appendToOverlay: 'body', // or false
	    overlay: true,
	    overlayClose: true,
	    overlayColor: 'rgba(0, 0, 0, 0.4)',
	    timeout: false,
	    timeoutProgressbar: false,
	    pauseOnHover: false,
	    timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
	    transitionIn: 'comingIn',
	    transitionOut: 'comingOut',
	    transitionInOverlay: 'fadeIn',
	    transitionOutOverlay: 'fadeOut',
	    onFullscreen: function(){},
	    onResize: function(){},
	    onOpening: function(){},
	    onOpened: function(){},
	    onClosing: function(){},
	    onClosed: function(){},
	    afterRender: function(){}
	});

	$(document).on('click', '.login-trigger', function (event) {
	    event.preventDefault();
	    // $('#modal').iziModal('setZindex', 99999);
	    // $('#modal').iziModal('open', { zindex: 99999 });
	    $('#modal').iziModal('open');
	});
</script>