<div class="input-field col l7 m8 s12" style="margin-top: 0;">
    <div class="image-crop">
        <img src="{{ URL::asset('img/site/placeholder-image2.png') }}" style="max-width: 100%; max-height: 100%;"  alt="" class="responsive-img">
        <input type="hidden" name="crop-editor-status-default" id="crop-editor-status-default" value="1">
        <input type="hidden" name="crop-editor-status" id="crop-editor-status" value="0">
    </div>
</div>
<div class="img-preview m-t-xs"></div>
<div class="clear"></div>
<div  style="padding-top: 10px; display:none;" id="crop_status_switch-wrapper" class="col l12 m12 s12">
    <div class="deep-purple lighten-5" style="padding:10px;">
        <div class="input-field col l2 m6 s12">
            <select class="" tabindex="-1" style="width: 50%" id="image-size-select" name="image_size">
              <option value="icon">{{trans('main.img.form.size.icon')}}</option>
              <option value="small" selected>{{trans('main.img.form.size.small')}}</option>
              <option value="medium">{{trans('main.img.form.size.medium')}}</option>
              <option value="large">{{trans('main.img.form.size.large')}}</option>
              <option value="extralarge">{{trans('main.img.form.size.extralarge')}}</option>
            </select>
            <label>{{trans('main.img.form.size.label')}}</label>
        </div>
        <div class="col l4 m6 s12">
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

<div class="col l12 m12 s12" style="padding-top:30px;" id="image_upload_input-wrapper">
    <div class="file-field input-field col l9 m8 s12" id="select_img-btn" style="padding: 0;">
        <div class="btn teal lighten-1 custom-btn-5 squere blue large" >
            <span>{{ trans('main.image_upload.select_image') }}</span>
            <input type="file" id="upload" name="pic_1" accept="image/jpg,image/png,image/jpeg,image/gif">
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" id="file_input" name="file_input" style="margin-bottom: 0;">
        </div>
        <small class="filesize-warning-sm-1">{{ trans('main.image_upload.max_size') }}</small>
    </div>
    <div class="col l3 m4 s12" style="padding: 0;">
      <button type="button" class="upload_image-btn btn custom-btn-5 custom-color-1 shadow-1-1-h large right waves-effect waves-cc-1" disabled data-reset="<i class='material-icons'>file_upload</i> {{trans('main.buttons.upload')}}"><i class="material-icons">file_upload</i> {{trans('main.buttons.upload')}}</button>
  </div>
</div>
<input type="hidden" name="image_name" id="image_name" value="">
<div class="clear"></div>