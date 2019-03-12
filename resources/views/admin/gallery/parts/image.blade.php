<div class="input-field col l7 m8 s12" style="margin-top: 0;">
    <div class="image-crop">
        @if($gallery_item != null && $gallery_item->img)
            @php($img_path = URL::asset('/img/' . $gallery_type->title . '/' . $gallery_item->img))
            @php($placeholder_img = false)
            @php($ext = explode('.', $gallery_item->img)[1])
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
<div class="img-preview m-t-xs"></div>
<div class="clear"></div>
<div  style="padding-top: 10px; display:none;" id="crop_status_switch-wrapper" class="col l12 m12 s12">
    <div class="deep-purple lighten-5 wrapper-custom-3" style="padding:10px;">
        <div class="input-field col l2 m6 s12">
            <select class="" tabindex="-1" style="width: 50%" id="image-size-select" name="size">
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
    <input type="hidden" name="select_img-ind" id="select_img-ind" value="{{ $gallery_item != null && $gallery_item->img ? 0 : 1 }}">
    <div class="file-field input-field col l9 m8 s12" id="select_img-btn" style="display: @if($gallery_item != null && $gallery_item->img) none; @else block; @endif padding: 0;">
        <div class="btn teal lighten-1 custom-btn-5 squere blue large" >
            <span>{{ trans('main.image_upload.select_image') }}</span>
            <input type="file" id="upload" name="pic_1" accept="image/jpg,image/png,image/jpeg,image/gif">
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" id="file_input" name="file_input" style="margin-bottom: 0;">
        </div>
        <small class="filesize-warning-sm-1">{{ trans('main.image_upload.max_size') }}</small>
    </div>
    <div class="col l3 m4 s12 right" style="padding: 0;">
        <button type="button" class="delete_image-btn btn custom-btn-5 shadow-1-1-h large right waves-effect waves-cc-3" @if($gallery_item != null && $gallery_item->img)  @else disabled @endif data-reset="<i class='material-icons'>close</i> {{trans('main.buttons.delete')}}"><i class="material-icons">close</i> {{trans('main.buttons.delete')}}</button>
  </div>
</div>
<input type="hidden" name="image_name" id="image_name" value="@if($gallery_item != null && $gallery_item->img) {{$gallery_item->img}} @endif">
<div class="clear"></div>