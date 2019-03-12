<form id="manage_admin_form">
    <div class="modal-content row">
        <div>
            <div class="col s12">
                @if(!$admin)
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.new')}} {{trans('main.admins.manage.form.admin')}}</h5>
                    <input type="hidden" name="id" value="">
                @else
                    <h5 class="inner-header-5"><i class="material-icons">settings_applications</i> {{trans('main.misc.edit')}} {{$admin->username}}</h5>
                    <input type="hidden" name="id" value="{{$admin->id}}">
                @endif
                <h5>{{trans('main.misc.main_settings')}}</h5>
            </div>
            <div class="col l6 m6 s12" style="padding: 0;">
                <div class="row grey lighten-4">
                    <div class="col l12 m12 s12">
                        <div class="wrapper-custom-4 white" style="margin-top: 10px; padding-bottom: 10px; min-height: 345px;">
                            <div class="col l12 m12 s12 ">
                                <h5>avatar</h5>
                                <span>{{ trans('main.image_upload.preview') }}<span>
                            </div>
                            <form id="image_form">
                                <div class="input-field col l7 m8 s12" style="margin-top: 0;">
                                    <div class="image-crop">
                                        @if($admin != null && $admin->avatar)
                                            @php($img_path = URL::asset('/img/admins/avatars/' . $admin->avatar))
                                            @php($placeholder_img = false)
                                            @php($ext = explode('.', $admin->avatar)[1])
                                        @else
                                            @php($img_path = URL::asset('img/site/placeholder-image2.png'))
                                            @php($placeholder_img = true)
                                            @php($ext = null)
                                        @endif
                                        <img src="{{$img_path}}" style="max-width: 100%; max-height: 100%;"  alt=""  class="responsive-img @if($ext == 'png') custom-color-1 lighten-5-transparent @endif">
                                        <input type="hidden" name="crop-editor-status-default" id="crop-editor-status-default" value="1">
                                        <input type="hidden" name="crop-editor-status" id="crop-editor-status" value="0">
                                    </div>
                                </div>
                                <div class="img-preview m-t-xs col l5 m4 s12"></div>
                                <div class="clear"></div>
                                <div  style="padding-top: 10px; display:none;" id="crop_status_switch-wrapper" class="col l12 m12 s12">
                                    <div class="deep-purple lighten-5 wrapper-custom-3" style="padding:10px;">
                                        <div class="input-field col l4 m3 s6">
                                            <select class="" tabindex="-1" style="width: 50%" id="image-size-select" name="image_size">
                                                <option value="icon">{{trans('main.img.form.size.icon')}}</option>
                                                <option value="small" selected>{{trans('main.img.form.size.small')}}</option>
                                                <option value="medium">{{trans('main.img.form.size.medium')}}</option>
                                                <option value="large">{{trans('main.img.form.size.large')}}</option>
                                                <option value="extralarge">{{trans('main.img.form.size.extralarge')}}</option>
                                            </select>
                                            <label>{{trans('main.img.form.size.label')}}</label>
                                        </div>
                                        <div class="col l7 m6 s12">
                                            <div id="upload-demo" style="width:350px"></div>
                                            <div class="switch">
                                                <label>
                                                    crop off
                                                    <input type="checkbox" value="1" name="crop_status_switch" id="crop_status_switch" checked>
                                                    <span class="lever"></span>
                                                    crop on
                                                </label>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="col l12 m12 s12" style="padding-top: 10px;">
                                    <button type="button" class="upload_image-btn btn custom-btn-5 custom-color-1 shadow-1-1-h large waves-effect waves-cc-1" disabled data-reset="{{trans('main.buttons.upload')}}">{{trans('main.buttons.upload')}}</button>
                                    <button type="button" class="delete_image-btn btn custom-btn-5 shadow-1-1-h large waves-effect waves-cc-3" @if($admin != null && $admin->avatar)  @else disabled @endif data-reset="{{trans('main.buttons.delete')}}">{{trans('main.buttons.delete')}}</button>
                                </div>
                                <div class="col l12 m12 s12" style=" display: @if($admin != null && $admin->avatar) none; @else block; @endif" id="image_upload_input-wrapper">
                                    <div class="file-field input-field" >
                                        <div class="btn teal lighten-1 custom-btn-5 squere blue large" >
                                            <span>{{ trans('main.image_upload.select_image') }}</span>
                                            <input type="file" id="upload" name="pic_1" accept="image/jpg,image/png,image/jpeg,image/gif">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input class="file-path validate" type="text" id="file_input" name="file_input" style="margin-bottom: 0;">
                                        </div>
                                        <small class="filesize-warning-sm-1">{{ trans('main.image_upload.max_size') }}</small>
                                    </div>
                                </div>
                                <input type="hidden" name="image_name" id="image_name" value="@if($admin != null && $admin->avatar) {{$admin->avatar}} @endif">
                                <div class="clear"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col l6 m6 s12" style="padding: 0;">
                <div class="row grey lighten-4">
                  <div class="clear"></div>
                  <div class="input-field col l12 s12">
                     <input id="firstname" type="text" class="validate" name="firstname" value="@if($admin != null){{ explode(' ', $admin->username)[0] }}@endif">
                     <label for="firstname" class="input-label-1">{{trans('auth.signup.fields.firstname')}}</label>
                 </div>
                 <div class="input-field col l12 s12">
                     <input id="lastname" type="text" class="validate" name="lastname" value="@if($admin != null && isset( explode(' ', $admin->username)[1])){{ explode(' ', $admin->username)[1] }}@endif">
                     <label for="lastname" class="input-label-1">{{trans('auth.signup.fields.lastname')}}</label>
                 </div>
                 <div class="input-field col s12">
                     <input id="email" type="email" class="validate" name="email" value="@if($admin != null){{$admin->email}}@endif">
                     <label for="email" class="input-label-1">{{trans('auth.signup.fields.email')}}</label>
                 </div>
                 <div class="input-field col s12">
                     <input id="phone" type="text" class="validate" name="phone" value="@if($admin != null){{$admin->phone}}@endif">
                     <label for="phone" class="input-label-1">{{trans('auth.signup.fields.phone')}}</label>
                 </div>
                 <div class="input-field col s12">
                    <span class="input-wrapper-span-1">
                        <input type="checkbox" id="status" name="status" @if($admin != null && $admin->status) checked @endif>
                        <label for="status" style="left:0;">status</label>
                    </span>
                </div>
              </div>
            </div>
            <div class="clear"></div>
            <div class="col l6 m6 s12" style="padding: 0;">
                <div class="row grey lighten-4">
                    <div class="input-field col s12">
                       <input id="city" type="text" class="validate" name="city" value="@if($admin != null){{$admin->city}}@endif">
                       <label for="city" class="input-label-1">{{trans('auth.signup.fields.city')}}</label>
                    </div>
                    <div class="input-field col s12">
                      <select class="" style="width: 100%" name="country">
                          <option value="">{{trans('main.misc.any')}}</option>
                          @foreach($countries as $country)
                              <option value="{{$country->id}}" @if($admin != null && $admin->country == $country->id) selected @endif>{{$country->countryName}}</option>
                          @endforeach
                      </select>
                      <label>{{trans('auth.signup.fields.country')}}</label>
                        </div>
                        <div class="input-field col s12">
                        @php($admin_regions = ($admin != null && $admin->region && $admin->region != 'all' ? json_decode($admin->region, true) : []))
                        <select multiple name="region[]">
                          <option value="" disabled selected>{{trans('main.misc.any')}}</option>
                          @foreach($regions as $region)
                              <option value="{{$region->id}}" @if(in_array($region->id, $admin_regions)) selected @endif>{{$region->name}}</option>
                          @endforeach
                        </select>
                        <label>{{trans('auth.signup.fields.region')}}</label>
                    </div>
                    <div class="input-field col s12">
                      @php($admin_roles = ($admin != null ? $admin->roles()->pluck('id')->toArray() : []))
                      <select multiple name="role[]">
                      <option value="" disabled selected>{{trans('main.misc.none')}}</option>
                          @foreach($roles as $role)
                              <option value="{{$role->id}}" @if(in_array($role->id, $admin_roles)) selected @endif>{{$role->display_name}}</option>
                          @endforeach
                      </select>
                      <label>{{trans('auth.signup.fields.role')}}</label>
                   </div>
                   <div class=""clear></div>
                </div>
            </div>
            <div class="col l6 m6 s12" style="padding: 0;">
                <div class="row grey lighten-4">
                    <div class="input-field col l12 s12">
                        <input id="password" type="password" class="validate" name="password">
                        <label for="password">{{trans('auth.signup.fields.password')}}</label>
                    </div>
                    <div class="input-field col l12 s12">
                        <input id="password_confirmation" type="password" class="validate" name="password_confirmation">
                        <label for="password_confirmation">{{trans('auth.signup.fields.repeat_password')}}</label>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat parameters-save-btn" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="admin_save-cancell-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script src="{{ URL::asset('admin_assets/js/manage_image_upload.js') }}"></script>
<script type="text/javascript">
    var $image = $(".image-crop > img");
    $('#image-crop-on-btn').on('click', function() {
      $image.cropper({
          aspectRatio: 1 / 1,
          preview: ".img-preview",
      });

      $('#crop-editor-status').val(1);
  });

    $(document).ready(function() {
        $('#admin_save-cancell-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        $('select').material_select();

        var url_sp = "{{route('admins-save')}}";
        var form_sp = $('#manage_admin_form');
        var un_email_url = "{{route('check-unique-email')}}";
        var from_sp_vaidator = {
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true,
                    remote: {
                        url: un_email_url,
                        data: {'id' : "@if($admin){{$admin->id}}@else null @endif"},
                        type: "post"
                    }
                }
            },
            messages: {}
        }

        save_parameters(url_sp, form_sp, from_sp_vaidator);

        $(document).off('click', '.upload_image-btn').on('click', '.upload_image-btn', function(e) {
            var form = $('#image_form');
            var url = "{{route('admin-updateimg')}}";
            var id = $('#manage_admin_form').find('input[name=id]').val() || null;
            var size = $('#image-size-select').val();
            data = {'id': id, 'size': size};
            upload_image(form, url, data);
        });

        $(document).off('click', '.delete_image-btn').on('click', '.delete_image-btn', function(e) {
            $('#crop_status_switch-wrapper').slideUp(100);
            $('#image_upload_input-wrapper').slideDown(100);
            var url = "{{route('admin-updateimg')}}";
            var id = $('#manage_admin_form').find('input[name=id]').val() || null;
            var name = $('#image_name').val();
            var data = {'id': id, 'name': name}; 
            delete_image(url, data);
        });

        $(document).off('click', '#product_save-cancel-btn').on('click', '#product_save-cancel-btn', function (e) {
            e.preventDefault();
            var tmp_image_name = $('#image_name').val();
            var id = $('#manage_admin_form').find('input[name=id]').val() || null;
            if (tmp_image_name && !id) {
                var url = "{{route('file-delete')}}";
                var data = {'filename': tmp_image_name, 'action':'delete', 'directory': 'admins/avatars'};
                delete_image(url, data);
            }
            $('#manage_modal').modal('close');
        });

        @if($admin != null)$('#manage_admin_form').find('label.input-label-1').addClass('active');@endif
    });
</script>