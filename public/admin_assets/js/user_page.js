var redraw_tables = function (op_type) {
	var redraw_tables = [];

	if (op_type == 'stbs' || op_type == 'services') {
        redraw_tables = [datatables_obj.stbs, datatables_obj.services];
    } else {
        redraw_tables = [datatables_obj[op_type]];
    }
    
    delay(function(){
      	$.each(redraw_tables, function( index, value ) {
            value.button('.buttons-reload').trigger();
        });
    }, 200 );
}