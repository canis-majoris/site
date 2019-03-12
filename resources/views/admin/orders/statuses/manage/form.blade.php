<form id="manage_settings_form">
    <div class="modal-content row">
        <div style="padding-top: 1rem;">
            <div class="left">
                @if(!$orders_status)
                    <h4><i class="material-icons">settings_applications</i> {{trans('main.misc.new')}} {{trans('main.settings.manage.form.settings')}}</h4>
                    <input type="hidden" name="id" value="">
                @else
                    <h4><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$orders_status->name}}</h4>
                    <input type="hidden" name="id" value="{{$orders_status->id}}">
                @endif
            </div>
            <div class="left">
              <div class="row grey lighten-4">
                  <div class="clear"></div>
                  <div class="input-field col l12 s12">
                     <input id="name" type="text" class="validate" name="name" value="@if($orders_status != null){{ $orders_status->name }}@endif">
                     <label for="name" class="input-label-1">{{ trans('main.settings.manage.form.name') }}</label>
                 </div>
                 <div class="input-field col l12 s12">
                     <input id="default" type="text" class="validate" name="default" value="@if($orders_status != null){{ $orders_status->default }}@endif">
                     <label for="default" class="input-label-1">{{ trans('main.settings.manage.form.default') }}</label>
                 </div>
                <div class="input-field col s12">
                    <span>{{ trans('main.settings.manage.form.text') }}</span>
                    <div >
                        <textarea id="text" class="materialize-textarea" name="text">@if($orders_status != null){{$orders_status->text}}@endif</textarea>
                    </div>
                </div>
              </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="save-cancell-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">

    $(document).ready(function() {
        CKEDITOR.replace('text', {
            uiColor: '#E6E6E6',
            language: 'ru'
        });

        $('select').material_select();

        var url_sp = "{{route('settings.update')}}";
        var form_sp = $('#manage_settings_form');
        var form_text_names_arr = {'text': 'text'};
        var from_sp_vaidator = {
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator, form_text_names_arr, {}, 'post');

        @if($orders_status != null)$('#manage_settings_form').find('label.input-label-1').addClass('active');@endif
    });
</script>