@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<main class="mn-inner">
    <div class="">
        <div class="row no-m-t no-m-b">
            <div class="col s12 m12 l12">
                <div class="">
                    <div class=" bred-c-holder-1" >
                        <a href="{{route('admin-index')}}">მთავარი</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('languages-all')}}">ენები</a>
                    </div>
                </div>
                <div class="card invoices-card">
                    <div class="card-content">
                        <div class="card-options">
                            <input type="text" class="expand-search" placeholder="Search" autocomplete="off">
                        </div>
                        <div class="card-options">
                            <input type="text" class="expand-search" placeholder="Search" autocomplete="off">
                        </div>
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
        <a class="btn-floating btn-large red">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating green tooltipped" data-position="left" data-tooltip="ენის დამატება" href="{{route('languages-create')}}"><i class="material-icons">add</i></a></li>
        </ul>
    </div> -->
</main>
<div class="page-footer">
    <div class="footer-grid container">
        <div class="footer-r white">&nbsp;</div>
        <div class="footer-grid-r white">
            <a class="footer-text" href="{{route('users-all')}}">
                <i class="material-icons arrow-r">arrow_forward</i>
                <span class="direction">შემდეგი</span>
            </a>
        </div>
    </div>
</div>


<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script type="text/javascript">

	(function(window,$){
		var lang_ind = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'languages',
                //type: 'POST',
                //data: function (d) {
                    //d.activated = '1';
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                //},
            },
			"language": {
	            searchPlaceholder: 'Search records',
	            sSearch: '',
	            sLengthMenu: 'Show _MENU_',
	            sLength: 'dataTables_length',
	            oPaginate: {
	                sFirst: '<i class="material-icons">chevron_left</i>',
	                sPrevious: '<i class="material-icons">chevron_left</i>',
	                sNext: '<i class="material-icons">chevron_right</i>',
	                sLast: '<i class="material-icons">chevron_right</i>'
	            }
	        },
			"columns":[
				{"name":"id","data":"id","title":"Id","orderable":true,"searchable":true},
                {"name":"status", "data": 'status', "title": 'status',"orderable":true,"searchable":true},
				{"name":"language", "data": 'language', "title": 'Language',"orderable":true,"searchable":true},
				{"name":"language_code", "data": 'language_code', "title": 'Language Code',"orderable":true,"searchable":true},
			],
			"dom":"<'row'<'col l12 m12 s12'Blfr>>" +
				"<'row'<'col l12 m12 s12'ip>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"buttons":["csv","excel","print","reset","reload"],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
		});

		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

	    $(document).on('click', '.stat-change',function(e) {
            url = "{{ route('user-changestatus') }}";
            change_user_status($(this), url);
        });


	})(window,jQuery);
</script>
@stop
