<form id="manage_language_form" class="edit_item-form">
    <div class="modal-content row">
        <div class="row">
            <div class="col s12">
                @if(!$language)
                    <h4>{{trans('main.misc.new')}} {{trans('main.misc.language')}}</h4>
                    <input type="hidden" name="id" value="">
                @else
                    <h4>{{trans('main.misc.edit')}} {{$language->language}}</h4>
                    <input type="hidden" name="id" value="{{$language->id}}">
                @endif
                <h5 class="inner-header-5"><i class="material-icons">settings_applications</i>{{trans('main.misc.main_settings')}}</h5>
            </div>
            <div class="input-field col l6 m6 s12">
               <input id="language" type="text" class="validate" name="language" value="@if($language != null){{ $language->language}}@endif">
               <label for="language" class="input-label-1">{{trans('main.languages.manage.form.language')}}</label>
            </div>
            <div class="input-field col l6 m6 s12">
               <input id="language_code" type="text" class="validate" name="language_code" value="@if($language != null){{$language->language_code}}@endif">
               <label for="language_code" class="input-label-1">{{trans('main.languages.manage.form.name')}}</label>
            </div>
            <div class="input-field col s12">
                <span class="input-wrapper-span-1">
                    <input type="checkbox" id="watch_1" name="watch" @if($language != null && $language->watch) checked @endif>
                    <label for="watch_1" style="left:0;">{{trans('main.misc.status')}}</label>
                </span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat item-parameters-save-btn parameters-save-btn" id="manage_save-btn" type="submit" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="manage_save-cancel-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#manage_save-cancel-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        $('select').material_select();

        var url_sp = "{{route('languages-save')}}";
        var form_sp = $('#manage_language_form');
        var from_sp_vaidator = {
            rules: {
                language: {
                    required: true
                },
                language_code: {
                    required: true
                }
                /*date_start: {
                    required: true
                },
                date_end: {
                    required: true
                }*/
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator);

        var delay = (function(){
          var timer = 50;
          return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
          };
        })();

        $('#manage_language_form').find('label.input-label-1').addClass('active');
    });
</script>