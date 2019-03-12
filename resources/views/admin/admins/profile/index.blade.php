@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
{{-- <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"> --}}
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
                <div>
                  <ul class="collapsible" data-collapsible="expandable">
                      <li class="active">
                          <div class="collapsible-header active" style="padding: 0.5rem 1rem;">
                              <h5><i class="material-icons">account_box</i> {{ trans('main.users.containers.header.profile') }}</h5>
                          </div>
                          <div class="collapsible-body">
                              <div class="row">
                                  <div class="content-block col l12 m12 s12">
                                      <form id="user-parameters-form">
                                          <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
                                          <div class="col l6 m6 s12">
                                              <div class="row grey lighten-4">
                                                  <div class="col s12">
                                                      <h5><span class="card-title">{{ trans('main.users.manage.form.headers.personal_info') }}</span></h5>
                                                  </div>
                                                  <div class="clear"> </div>
                                                  <div class="input-field row">
                                                      <div class="col s12">{{ trans('main.users.manage.form.gender.header') }}</div>
                                                      <div class="col l4 m6 s12">
                                                          <input name="gender" type="radio" id="is_male" value="male" @if($user->gender == 'male') checked @endif>
                                                          <label for="is_male">{{ trans('main.users.manage.form.gender.male') }}</label>
                                                      </div>
                                                      <div class="col l4 m6 s12">
                                                          <input name="gender" type="radio" id="is_female" value="female" @if($user->gender == 'female') checked @endif>
                                                          <label for="is_female">{{ trans('main.users.manage.form.gender.female') }}</label>
                                                      </div>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input type="text" class="validate" name="name" id="name" value="{{$user->name}}">
                                                      <label for="name" class="input-label-1">{{ trans('main.users.manage.form.name') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="birth_date" type="date" class="datepicker" value="" name="bith_date">
                                                      <label for="birth_date" class="input-label-1">{{ trans('main.users.manage.form.birth_date') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="email" type="email" class="validate" value="{{$user->email}}" name="email">
                                                      <label for="email" class="input-label-1">{{ trans('main.users.manage.form.email') }}</label>
                                                  </div>
                                              </div>
                                              <div class="row grey lighten-4">
                                                  <div class="col s12">
                                                      <h5><span class="card-title">{{ trans('main.users.manage.form.headers.address') }}</span></h5>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input type="text" class="validate" name="flat" id="flat" value="{{$user->flat}}">
                                                      <label for="flat" class="input-label-1">{{ trans('main.users.manage.form.address') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="region" type="text" class="validate" name="region" value="{{$user->region}}">
                                                      <label for="region" class="input-label-1">{{ trans('main.users.manage.form.region') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="postcode" type="text" class="validate" name="postcode" value="{{$user->postcode}}">
                                                      <label for="postcode" class="input-label-1">{{ trans('main.users.manage.form.postcode') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <select class="" style="width: 100%" name="country">
                                                          <option value="">-</option>
                                                          @foreach($countries as $country)
                                                              <option value="{{$country->id}}" @if($country->countryName == $user->country) selected @endif>{{$country->countryName}}</option>
                                                          @endforeach
                                                      </select>
                                                      <label>{{ trans('main.users.manage.form.country') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="city" type="text" class="validate" name="city" value="{{$user->city}}">
                                                      <label for="city" class="input-label-1">{{ trans('main.users.manage.form.city') }}</label>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col l6 m6 s12">
                                              <div class="row grey lighten-4">
                                                  <div class="col s12">
                                                      <h5><span class="card-title">Account</span></h5>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="code" type="text" class="validate" value="{{$user->code}}" name="code" disabled>
                                                      <label for="code" class="input-label-1">{{ trans('main.users.manage.form.code') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="created_at" type="text" class="validate" value="{{$user->created_at}}" name="created_at">
                                                      <label for="created_at" class="input-label-1">{{ trans('main.users.manage.form.reg_date') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="username" type="text" class="validate" value="{{$user->username}}" name="username">
                                                      <label for="username" class="input-label-1">{{ trans('main.users.manage.form.username') }}</label>
                                                  </div>
                                              </div>
                                              <div class="row grey lighten-4">
                                                  <div class="col s12">
                                                      <h5><span class="card-title">{{ trans('main.users.manage.form.headers.contact_info') }}</span></h5>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input type="text" class="validate" name="phone" id="phone" value="{{$user->phone}}">
                                                      <label for="phone" class="input-label-1">{{ trans('main.users.manage.form.phone') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="mobile" type="text" class="validate" name="mobile" value="{{$user->mobile}}">
                                                      <label for="mobile" class="input-label-1">{{ trans('main.users.manage.form.mobile') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="password" type="password" class="validate" name="password" value="">
                                                      <label for="password" class="input-label-1">{{ trans('main.users.manage.form.password') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="password_confirmation" type="password" class="validate" name="password_confirmation" value="">
                                                      <label for="password_confirmation" class="input-label-1">{{ trans('main.users.manage.form.repeat_password') }}</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <span class="input-wrapper-span-1">
                                                          <input type="checkbox" id="activated" name="activated" @if($user->activated==1)checked="checked"@endif>
                                                          <label for="activated" style="left:0;">{{ trans('main.users.manage.form.activated') }}</label>
                                                      </span>
                                                  </div>
                                              </div>
                                              <div class="row grey lighten-4">
                                                  <div class="col s12">
                                                      <h5><span class="card-title">{{ trans('main.users.manage.form.headers.dealer_settings') }}</span></h5>
                                                  </div>
                                                  <div class="input-field col s12 row">
                                                      <span class="input-wrapper-span-1">
                                                          <input type="checkbox" id="is_diller" name="is_diller" @if($user->is_diller==1)checked="checked"@endif>
                                                          <label for="is_diller" style="left:0;">{{ trans('main.users.manage.form.is_dealer') }}</label>
                                                      </span>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="percent" type="text" class="validate" name="percent" value="{{$user->percent}}">
                                                      <label for="percent" class="input-label-1">{{ trans('main.users.manage.form.percent') }}и</label>
                                                  </div>
                                                  <div class="input-field col s12">
                                                      <input id="percent_first" type="text" class="validate" name="percent_first" value="{{$user->percent_first}}"> 
                                                      <label for="percent_first" class="input-label-1">{{ trans('main.users.manage.form.percent_first') }}</label>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col s12">
                                                  <button class="waves-effect waves-light btn teal pull-right parameters-save-btn" type="submit" data-reset="{{ trans('main.buttons.save') }}" id="save-user-parameters-btn">{{ trans('main.buttons.save') }}</button>
                                              </div>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </li>
                  </ul>
              </div>
            </div>
        </div>
    </div>
</main>

<!-- Manage Administrator -->
<div id="manage_modal" class="modal modal-fixed-footer">
    <div class="container-2"></div>
</div>
{{-- <script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script> --}}


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
	      $('.card-options').fadeOut(0);

        url_au = "{{ route('admins-add') }}";
        form_au = $('#add_admin_form');
        un_email_url = "{{route('check-unique-email')}}";
        add_admin(url_au, form_au, un_email_url);

	    $(document).on('click', '.stat-change',function(e) {
            e.preventDefault();
            url = "{{ route('admins-changestatus') }}";
            change_user_status($(this), url);
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
