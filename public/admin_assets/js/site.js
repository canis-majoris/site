var btn_loading = function(button) {
    $(button).prop('disabled', true).html("<i class='material-icons amnimated-1'>autorenew</i>")/*.css({'background-color':'lightgray'})*/;

}

var btn_reset = function(button) {
    $(button).prop('disabled', false).html($(button).data('reset'))/*.removeAttr('style')*/;
}

var toggleFocus = function(e){

    if( e.type == 'focusin' )
        $(this).addClass('open-search');
    else 
        $(this).removeClass('open-search');
}

$(document).on('focus blur', 'input.expand-search', toggleFocus);