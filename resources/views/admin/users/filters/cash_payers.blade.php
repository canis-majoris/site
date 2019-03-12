{{-- <div class="input-field col l2 m3 s6" style="padding-left: 0;">
    <select class="regular-select" tabindex="-1" style="width: 50%" id="users-activity-select">
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
            <input id="date_start_from" type="date" class="datepicker" value="" name="date_start_from" style="margin: 0;">
            <label for="date_start_from" class="input-label-1">от</label>
        </div>
        <div class="input-field col" style="">
            <input id="date_start_to" type="date" class="datepicker" value="" name="date_start_to" style="margin: 0;">
            <label for="date_start_to" class="input-label-1">до</label>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="input-field col l1 m3 s6">
    <select class="regular-select" tabindex="-1" style="width: 50%" id="users-status-select">
        <option value="1">{{trans('main.users.table.filter.status.op_1')}}</option>
        <option value="0">{{trans('main.users.table.filter.status.op_2')}}</option>
        <option value="2" selected>{{trans('main.users.table.filter.status.default')}}</option>
    </select>
    <label>{{trans('main.users.table.filter.status.header')}}</label>
</div>
{{-- <div class="input-field col l2 m3 s6">
    <select class="" tabindex="-1" style="width: 50%" id="users-custom1-select">
      <option value="0" selected>{{trans('main.users.table.filter.status.default')}}</option>
      <option value="1">Mobile services only</option>
      <option value="2">Main services</option>
      <option value="3">Multiroom</option>
          </select>
    <label>With service</label>
</div> --}}
<div class="input-field col l3 m4 s6">
    <span class="input-wrapper-span-1">
        <input type="checkbox" id="show-dealers-only" name="show-dealers-only" >
        <label for="show-dealers-only">Показать только дилеров</label>
    </span>
</div>
<div class="row col l4 m12 s12">
    <div class="input-field col l9 m10 s12" style="padding: 0;">
        <select class="js-states browser-default cash_payer_users-select" tabindex="-1" style="width: 100%" id="cash_payer_user">
            <option value="default" selected></option>
            @foreach($regular_users as $user)
                <option value="{{$user->id}}" @if($user->cash_payment_status) disabled @endif>{{$user->username}} ({{$user->code}})</option>
            @endforeach
        </select>
        <label style="top: -15px; font-size: 0.8rem; left: 0;">Add user in cash payers list</label>
    </div>
    <div class="input-field col l3 m2 s12" style=" padding-right: 0;">
        <button class="waves-effect waves-light btn right btn-custom-width" id="cash_payer_user-save-btn" type="button" data-reset="<i class='material-icons'>check</i> save" style="margin-top: 5px;" disabled><i class='material-icons'>check</i> save</button>
    </div>
    <div class="clear"></div>
</div>
<script src="{{ URL::asset('admin_assets/plugins/select2/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $('.regular-select').material_select();
    $('.cash_payer_users-select').select2();

    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });
    var picker = datepicker.pickadate('picker');

    $(document).on('change', '.cash_payer_users-select', function (e) {
        if (val = $(this).val()) {
            $('#cash_payer_user-save-btn').prop('disabled', false);
        } else $('#cash_payer_user-save-btn').prop('disabled', true);
    });

</script>