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
                                <div class="col l6 m6 s12 hide-on-med-and-down">
                                    <div class=" bred-c-holder-1" >
                                        {!!config('database.region.'.session('region_id').'.transactions')!!}
                                        <a href="{{route('home-index')}}">{{trans('main.breadcrumbs.home')}}</a> <span><i class="material-icons">keyboard_arrow_right</i></span> <a href="{{route('transactions.index', 'provider=cartu')}}" style="font-size:25px;">{{trans('main.breadcrumbs.transactions')}}</a>
                                    </div>

                                </div>
                                <div class="col s12 m6 l6 tab-holder-wrapper-1 right" style="margin-top: 15px;">
                                    <div class=" bred-c-holder-1" style="padding-top: 0; padding-bottom: 0;">
                                        <div class="right tab-holder-top-1">
                                            <ul class="tabs z-depth-1 right">
                                                <li class="tab"><a data-provider="cartu" class="provider-selector-1 transactions-tabs-1 @if($provider == 'cartu') active @endif">{{trans('dashboard.sidebar.list.transactions.cartu')}}</a></li>
                                                <li class="tab"><a data-provider="paypal" class="provider-selector-1 transactions-tabs-1 @if($provider == 'paypal') active @endif">{{trans('dashboard.sidebar.list.transactions.paypal')}}</a></li>
                                            <div class="indicator" style="right: 403px; left: 201.5px;"></div></ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>
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
                                {!! $filter !!}
                            </div>
                            <div class="clear"></div>
                        </form>
                        <div>
                        <div class="dataTable-holder-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="fixed-action-btn">
        <a class="btn-floating btn-large waves-effect waves-light red menu-items-controll modal-trigger" id="add_user_btn" href="#add_user_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="Add User" style="font-size: 30px;">playlist_add</i></a>
    </div> -->
</main>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $('body').addClass('search-app');
    
    var provider = null;
    var curr_datatable = null;

   // $('select').material_select();

    $('label.input-label-1').addClass('active');


	(function(window,$){
        //$('.dropdown-button').dropdown();
        $(document).off('input', "input.expand-search") // Unbind previous default bindings
        .on("input", "input.expand-search", function(e) { // Bind our desired behavior
            // If the length is 3 or more characters, or the user pressed ENTER, search

            if(this.value.length >= 1 || e.keyCode == 13) {
                // Call the API search function
                $('.custom-datatable-1').search(this.value).draw();
            }
            // Ensure we clear the search if they backspace far enough
            if(this.value == "") {
                $('.custom-datatable-1').search("").draw();
            }
            return;
        });

        //$('.dataTable').addClass('responsive-table');
		$('.dataTables_length select').addClass('browser-default');
	    $('.dataTables_filter input[type=search]').addClass('expand-search');
	    $('.card-options').fadeOut(0);

        $(document).on('processing.dt', "#cartu_transactions-datatable, #paypal_transactions-datatable", function ( e, settings, processing ) {
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
        });

        var load_filter = function(provider){
            var url = "{{route('transactions.load_filter')}}";
            var curr_url = "{{route('transactions.index')}}";
            op_load_filter(url, provider, $('.load-filter-container'));
            window.history.pushState("", "Title", curr_url + '?provider=' + provider);
        }

        var load_table = function(provider){
            var url = "{{route('transactions.load_table')}}";
            table_data = {'provider': provider};
            op_load_table(url, table_data, $('.dataTable-holder-1'));

            // curr_datatable = $('#' + provider + '_transactions-datatable');
        }

        $(document).ready(function(e) {
            provider = "{{$provider}}";
            load_filter(provider);
            load_table(provider);
        });

        $(document).on('click', '.transactions-tabs-1',function(e) {
            e.preventDefault();
            provider = $(this).data('provider');
            load_filter(provider);
            load_table(provider);
        });

	})(window,jQuery);
</script>
@stop
