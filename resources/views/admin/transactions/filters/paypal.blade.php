<div class="wrapper-custom-1 col l4 m5 s12">
    <div class="datapicker-wrapper-1">
        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">{{trans('main.orders_products.table.filter.date.start')}}:</span></div>
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
<div class="input-field col l2 m3 s6">
    <select class="" tabindex="-1" style="width: 50%" id="transactions-status-select">
        <option value="any" selected>{{trans('main.users.table.filter.status.default')}}</option>
        <option value="Completed Successfully">{{trans('main.misc.accepted')}}</option>
        <option value=" ">{{trans('main.misc.declined')}}</option>
    </select>
    <label>{{trans('main.orders_products.table.filter.status')}}</label>
</div>
<div class="input-field col l4 m3 s12" style="float: right">
    <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input" name="search_input">
    <div class="clear"></div>
    <!-- <label for="date_end" class="input-label-1">date end</label> -->
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