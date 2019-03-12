@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css">
<main class="mn-inner">
    <div class="search-header" style="margin-bottom: 15px;">
        <div class="card card-transparent no-m">
            <div class="card-content no-s">
                <div class="z-depth-1 search-tabs">
                    <div class="search-tabs-container">
                        <div class="col s12 m12 l12">
                            <div class="row search-tabs-row search-tabs-container custom-accent-color-1">
                                <div class="col l6 m6 s12">
                                    <div class=" bred-c-holder-1" >
                                        {!!config('database.region.'.session('region_id').'.users')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('admins.index')}}" style="font-size:25px;">{{trans('main.breadcrumbs.administrators')}}</a>
                                    </div>
                                </div>
                                <div class="col s12 m6 l6 hide-on-med-and-down" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="row no-m-t no-m-b">
            <div class="col s12 m12 l12">
                <div class="card invoices-card">
                    <div class="card-content">
                          <form method="POST" id="search-form" class="form-inline" role="form" action="">
                              <div class="load-filter-container row deep-purple lighten-5">
                                  <!-- Dropdown Trigger -->
                                  <div class="input-field col l2 m3 s6" style="padding-left: 0;">
                                      <select class="" tabindex="-1" style="width: 50%" id="admins-status-select">
                                        <option value="1">{{trans('main.users.table.filter.activity.op_1')}}</option>
                                        <option value="0">{{trans('main.users.table.filter.activity.op_2')}}</option>
                                        <option value="any" selected>{{trans('main.users.table.filter.activity.default')}}</option>
                                      </select>
                                      <label style="left:0;">{{trans('main.users.table.filter.activity.header')}}</label>
                                  </div>
                                  <div class="input-field col l2 m3 s6" style="padding-left: 0;">
                                      <select multiple name="region[]" id="admins-region-select">
                                        <option value="" disabled selected>{{trans('main.users.table.filter.region.default')}}</option>
                                        @foreach($regions as $region)
                                            <option value="{{$region->id}}" >{{$region->name}}</option>
                                        @endforeach
                                      </select>
                                      <label style="left:0;">{{trans('main.users.table.filter.region.header')}}</label>
                                  </div>
                                  <div class="input-field col l4 m3 s12" style="float: right">
                                    <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input" name="search_input">
                                    <div class="clear"></div>
                                    <!-- <label for="date_end" class="input-label-1">date end</label> -->
                                  </div>
                                  <div class="clear"></div>
                                </div>
                              <!--<input type="submit" value="test"> -->
                          </form>
                        {!! $dataTable->table() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User -->
    <div id="add_admin_modal" class="modal modal-fixed-footer modal-small">
        <form id="add_admin_form">
            <div class="modal-content row">
                <div class="col s12">
                    <h4>Добавить администратора</h4>
                </div>
                <div class="row">
                    <div class="input-field col l6 s12">
                       <input id="firstname" type="text" class="validate" name="firstname" value="{{ old('firstname') }}">
                       <label for="firstname">{{trans('auth.signup.fields.firstname')}}</label>
                   </div>
                   <div class="input-field col l6 s12">
                       <input id="lastname" type="text" class="validate" name="lastname" value="{{ old('lastname') }}">
                       <label for="lastname">{{trans('auth.signup.fields.lastname')}}</label>
                   </div>
                   <div class="input-field col s12">
                       <input id="email" type="email" class="validate" name="email">
                       <label for="email">{{trans('auth.signup.fields.email')}}</label>
                   </div>
                   <div class="input-field col s12">
                       <input id="phone" type="text" class="validate" name="phone">
                       <label for="phone">{{trans('auth.signup.fields.phone')}}</label>
                   </div>
                   <div class="input-field col s12">
                       <input id="city" type="text" class="validate" name="city">
                       <label for="city">{{trans('auth.signup.fields.city')}}</label>
                   </div>
                   <div class="input-field col s12">
                      <select class="" style="width: 100%" name="country">
                          <option value="">{{trans('main.misc.any')}}</option>
                          @foreach($countries as $country)
                              <option value="{{$country->id}}" >{{$country->countryName}}</option>
                          @endforeach
                      </select>
                      <label>{{trans('auth.signup.fields.country')}}</label>
                   </div>
                   <div class="input-field col s12">
                    <select multiple name="region[]">
                      <option value="" disabled selected>{{trans('main.misc.any')}}</option>
                      @foreach($regions as $region)
                          <option value="{{$region->id}}" >{{$region->name}}</option>
                      @endforeach
                    </select>
                    <label>{{trans('auth.signup.fields.region')}}</label>
                  </div>
                   <div class="input-field col l6 s12">
                       <input id="password" type="password" class="validate" name="password">
                       <label for="password">{{trans('auth.signup.fields.password')}}</label>
                   </div>
                   <div class="input-field col l6 s12">
                       <input id="password_confirmation" type="password" class="validate" name="password_confirmation">
                       <label for="password_confirmation">{{trans('auth.signup.fields.repeat_password')}}</label>
                   </div>
                    <div class="input-field col s12">
                      <select multiple name="role[]">
                      <option value="" disabled selected>{{trans('main.misc.none')}}</option>
                          @foreach($roles as $role)
                              <option value="{{$role->id}}" >{{$role->display_name}}</option>
                          @endforeach
                      </select>
                      <label>{{trans('auth.signup.fields.role')}}</label>
                   </div>
                   <div class="input-field col s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="status" name="status">
                            <label for="status" style="left:0;">status</label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="waves-effect waves-green btn-flat right" id="save-admin-parameters-btn" type="submit" data-reset="save">SAVE</button>
                <a class="waves-effect waves-light btn-flat right modal-action modal-close" id="add_admin_save-calcel-btn">cancel</a>
            </div>
        </form>
    </div>
    <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light custom-main-color-1 menu-items-controll modal-trigger" id="add_btn" href="#manage_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add administrator" style="font-size: 30px;">playlist_add</i></a>
    </div>
</main>

<!-- Manage Administrator -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>

<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="{{ URL::asset('admin_assets/plugins/image-cropper/cropper.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">


        /*$('.dropdown-button').dropdown({
          inDuration: 300,
          outDuration: 225,
          constrainWidth: false, // Does not change width of dropdown to that of the activator
          hover: true, // Activate on hover
          gutter: 0, // Spacing from edge
          belowOrigin: false, // Displays dropdown below the button
          alignment: 'left', // Displays dropdown with edge aligned to the left of button
          stopPropagation: false // Stops event propagation
        }
      );*/

    //$('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });

    $('label.input-label-1').addClass('active');

    function initMap() {
        var options = {
            types: ['(cities)']
        };
        var input = document.getElementById('city');
        var autocomplete = new google.maps.places.Autocomplete(input, options);

        //    var input2 = document.getElementById('city2');
        //    var autocomplete2 = new google.maps.places.Autocomplete(input2, options);
    }
    initMap();

	(function(window,$){
        $('.dropdown-button').dropdown();
		var lang_ind = null;
        var status_value = 'any';
        var region_value = 'any'
        var search_input = null;
		window.LaravelDataTables = window.LaravelDataTables || {};
		window.LaravelDataTables["dataTableBuilder"] = $("#dataTableBuilder").DataTable({
			"serverSide":true,
			"processing":true,
			"ajax": {
				url: 'admins',
                //type: 'POST',
                data: function (d) {
                    d.status = status_value;
                    d.region = region_value;
                    d.search_input = search_input;
                    //d.title = $('input[name=title]').val();
                    //d.subtitle = $('input[name=subtitle]').val();
                    //d.description = $('input[name=description]').val();
                    //d.tags = $('#tag_multiselect').val();
                },
            },
            "responsive": true,
			"language": {
	            searchPlaceholder: "{{trans('main.filter.search_bar')}}",
                sZeroRecords: "{{trans('main.filter.empty')}}",
                sProcessing: "{{trans('main.filter.processing')}}",
                sInfo: "{{trans('main.filter.show_total')}}",
	            sSearch: '',
	            sLengthMenu: "{{trans('main.filter.record_count_show')}}" + ' _MENU_',
	            sLength: 'dataTables_length',
	            oPaginate: {
	                sFirst: '<i class="material-icons">chevron_left</i>',
	                sPrevious: '<i class="material-icons">chevron_left</i>',
	                sNext: '<i class="material-icons">chevron_right</i>',
	                sLast: '<i class="material-icons">chevron_right</i>'
	            }
	        },
			"columns":[
				{"name":"id","data":"id","title":"{{trans('main.users.table.header.id')}}","orderable":true,"searchable":true},
                {"name":"status", "data": 'status', "title":"{{trans('main.users.table.header.status')}}","orderable":true,"searchable":true},
				{"name":"avatar", "data": 'avatar', "title":"{{trans('main.users.table.header.avatar')}}","orderable":false,"searchable":false},
				{"name":"username","data":"username","title":"{{trans('main.users.table.header.name')}}","orderable":true,"searchable":true},
                {"name":"email","data":"email","title":"{{trans('main.users.table.header.email')}}","orderable":true,"searchable":true},
				{"name":"region","data":"region","title":"{{trans('main.users.table.header.region')}}","orderable":false,"searchable":false},
                {"name":"last_login","data":"last_login","title":"{{trans('main.users.table.header.last_login')}}","orderable":true,"searchable":true},
				{"name":"roles","data":"roles","title":"{{trans('main.users.table.header.role')}}","orderable":false,"searchable":false},
				{"name":"action","data":"action","title":"","orderable":false,"searchable":false},
			],
			"dom":"<'row'<'col l12 m12 s12'lBr>>" +
				"<'row'<'col l12 m12 s12'ip>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"buttons":["csv",
                {
                    extend: 'excel',
                    //text: 'excel',
                    exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6]
                    }
                },
                "print","reset","reload",
                {extend: 'selectAll', text: '<i class="material-icons">playlist_add_check</i> {{trans('main.misc.select_all')}}'},
                {extend: 'selectNone', text: '<i class="material-icons">remove_circle_outline</i> {{trans('main.misc.select_all_cancel')}}'},
                {extend: 'selected', text: "<i class='material-icons'>delete_forever</i> {{trans('main.misc.delete')}}",
                    action: function ( e, dt, node, config ) {
                        var rows = dt.rows( { selected: true } );
                        var dataArr = rows.data().toArray();
                        var rowCount = rows.count();
                        dataArr = $.map(dataArr, function(n,i){
                           return [ n.id ];
                        });

                         swal.queue([{
                            title: "{{trans('main.misc.delete_title')}}",
                            //text: "You will not be able to recover these discounts!",
                            type: "warning",
                            showLoaderOnConfirm: true,
                            showCancelButton: true,
                            showLoaderOnConfirm: true,
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "{{trans('main.misc.delete')}}",
                            cancelButtonText: "{{trans('main.misc.cancel')}}",
                            confirmButtonClass: 'btn red',
                            cancelButtonClass: 'btn btn-flat',
                            buttonsStyling: false,
                            preConfirm: () => {

                                url = "{{route('admins-delete')}}";
                                return new Promise(function(resolve, reject) {
                                    delete_items(url, dataArr, rowCount, [window.LaravelDataTables["dataTableBuilder"]], function (response) {

                                    });
                                });
                            }
                        }]).then(function() {
                            
                        });
                    }
                },
            ],
            'select': {
                style: 'multi',
                selector: '.selectable'
            },
            "columnDefs": [
                { className: "selectable", "targets": [3, 4, 5, 6, 7] }
            ],
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			'iDisplayLength': 25,
            drawCallback: function(settings){
                 var api = this.api();           
                 // Initialize custom control
                 initDataTableCtrl(api.table().container());
            },
		});

        function initDataTableCtrl(container) {
            $('.materialboxed').materialbox();
        }

        $(document).off('input', "input.expand-search") // Unbind previous default bindings
        .on("input", "input.expand-search", function(e) { // Bind our desired behavior
            // If the length is 3 or more characters, or the user pressed ENTER, search
            if(this.value.length >= 1 || e.keyCode == 13) {
                // Call the API search function
                window.LaravelDataTables["dataTableBuilder"].search(this.value).draw();
            }
            // Ensure we clear the search if they backspace far enough
            if(this.value == "") {
                window.LaravelDataTables["dataTableBuilder"].search("").draw();
            }
            return;
        });

		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

        window.LaravelDataTables["dataTableBuilder"].on( 'processing.dt', function ( e, settings, processing ) {
           // console.log(this)
            var wrapper = $(this).closest('.card-content');
            var tables_wrapper = $(this).closest('.dataTables_wrapper');
            if (processing) {
                tables_wrapper.addClass('processing-table');
                if (wrapper.find('.preloader-full').length == 0) {
                    $(loading_overlay_progress).hide().prependTo(wrapper).fadeIn(200);
                }

                if (wrapper.find('.ocean').length == 0) { 
                    $(wave_animation).hide().prependTo(tables_wrapper).fadeIn(200);
                }
                
            } else {
               tables_wrapper.removeClass('processing-table');
               wrapper.find('.preloader-full').fadeOut(200, function() { $(this).remove(); });
            }
        } );

        url_au = "{{ route('admins-add') }}";
        form_au = $('#add_admin_form');
        un_email_url = "{{route('check-unique-email')}}";
        add_admin(url_au, form_au, un_email_url);

	    $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('admins-changestatus') }}";
            changestatus($(this), url);
        });

        $(document).on('change', '#admins-status-select',function(e) {
            status_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('change', '#admins-region-select',function(e) {
            region_value = $(this).val();
            delay(function(){
              window.LaravelDataTables["dataTableBuilder"].draw();
            }, 200 );
            e.preventDefault();
        });

        $(document).on('click', '.show-admin-btn',function(e) {
            e.preventDefault();
            var url = "{{ route('users-show', 0) }}";
            var container = $('.mn-inner');
            //show_admin($(this), container, url);
        });

        $(document).on('click', '.admin-edit-btn', function(e) {
            var url = "{{route('admins-edit')}}";
            var data = {id: $(this).data('id')};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data);
            $('#manage_admin_form').find('.input-field').find('label').addClass('active');
        });

        $(document).on('click', '#add_btn',function(e) {
            e.preventDefault();
            var url = "{{ route('admins-edit') }}";
            var data = {};
            var container = $('#manage_modal > .container-2');
            manage_load_modal(url, container, data);
        });

        $(document).on('click', '#manage_save-btn', function (e) {
            $('#manage_admin_form').submit();

            delay(function(){
                window.LaravelDataTables["dataTableBuilder"].draw();
            }, 500 );

            $(this).unbind('click');
        });

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();


	})(window,jQuery);
</script>
@stop
