{{-- <div class="input-field col l1 m3 s6" style="padding-left: 0;">
    <select class="" tabindex="-1" style="width: 50%" id="users-activity-select">
      <option value="1">{{trans('main.users.table.filter.activity.op_1')}}</option>
      <option value="0">{{trans('main.users.table.filter.activity.op_2')}}</option>
      <option value="2" selected>{{trans('main.users.table.filter.activity.default')}}</option>
    </select>
    <label style="left:0;">{{trans('main.users.table.filter.activity.header')}}</label>
</div> --}}
<div class="wrapper-custom-1 col l4 m5 s12">
    <div class="datapicker-wrapper-1">
        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">Дата создания:</span></div>
        <div class="input-field col" style="">
            <input id="date_start" type="date" class="datepicker date_picker-custom-1" value="" name="date_start" style="margin: 0;">
            <label for="date_start" class="input-label-1">от</label>
        </div>
        <div class="input-field col" style="">
            <input id="date_end" type="date" class="datepicker date_picker-custom-1" value="" name="date_end" style="margin: 0;">
            <label for="date_end" class="input-label-1">до</label>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="input-field col l2 m3 s12">
    <select multiple name="users_service[]" id="users-service-select">
      <option value="" disabled selected>{{trans('main.users.table.filter.region.default')}}</option>
      @foreach($all_services as $a_service)
            @php($trans_service_name=$a_service->translated()->first())
            <option value="{{$a_service->id}}">@if($trans_service_name) {{$trans_service_name->name . ($trans_service_name->short_text ? ' (' . $trans_service_name->short_text . ')' : '')}} @else {{$a_service->name}} @endif</option>
      @endforeach
    </select>
    <label>{{trans('main.users.table.filter.activity.header')}}</label>
</div>
<div class="input-field col l2 m4 s12">
    <select multiple name="users_activity[]" id="users-custom1-select">
      <option value="any" disabled selected>{{trans('main.users.table.filter.status.default')}}</option>
      <option value="0">{{ trans('main.charts.Inactive') }}</option>
      <option value="1">{{ trans('main.charts.Active mobile services, no active STB services') }}</option>
      <option value="2">{{ trans('main.charts.Active STB services, no active mobile services') }}</option>
      <option value="3">{{ trans('main.charts.Active STB services as well as mobile services') }}</option>
      <option value="4">{{ trans('main.charts.Active STB services with multiroom, no active mobile services') }}</option>
      <option value="5">{{ trans('main.charts.Active STB services with multiroom as well as mobile services') }}</option>
    </select>
    <label>{{trans('main.users.table.filter.with_service')}}</label>
</div>
<div class="input-field col l1 m3 s12">
    <select class="" tabindex="-1" style="width: 50%" id="users-status-select">
      <option value="1">{{trans('main.users.table.filter.status.op_1')}}</option>
      <option value="0">{{trans('main.users.table.filter.status.op_2')}}</option>
      <option value="2" selected>{{trans('main.users.table.filter.status.default')}}</option>
    </select>
    <label>{{trans('main.users.table.filter.status.header')}}</label>
</div>
<div class="input-field col l2 m7 s12 right" style="float: right">
    <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input" name="search_input">
    <div class="clear"></div>
    <!-- <label for="date_end" class="input-label-1">date end</label> -->
</div>
<div class="clear"></div>
<div class="input-field col l2 m5 s12 left" style="padding: 0;">
    <span class="input-wrapper-span-1">
        <input type="checkbox" id="show-dealers-only" name="show-dealers-only" >
        <label for="show-dealers-only">Показать только дилеров</label>
    </span>
</div>
<div class="clear"></div>
<script type="text/javascript">
    $('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });
    var picker = datepicker.pickadate('picker');

    $(document).on('focus blur', 'input.expand-search', toggleFocus);

    var toggleFocus = function(e){

        if( e.type == 'focusin' )
            $(this).addClass('open-search');
        else 
            $(this).removeClass('open-search');
    }
</script>