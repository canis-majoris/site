<form id="add_product_form">
    <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">
    <input type="hidden" name="op_type" id="op_type" value="{{$redraw_type}}">
    <div class="modal-content row">
        <h4>{{trans('main.misc.add')}} {{$type}}</h4>
        <div>
           <div class="input-field">
              <select class="" style="width: 100%" name="product">
                  @foreach($products as $product)
                    @php($translated = $product->translated()->first())
                      <option value="{{$product->id}}">{{($translated ? $translated->name . ' ' . $translated->short_text : $product->name)}}</option>
                  @endforeach
              </select>
              <label>{{trans('main.users.containers.header.'.$redraw_type)}}</label>
           </div>
           <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat add_product_save-btn" id="manage_save-btn" type="button" data-reset="{{trans('main.buttons.save')}}">{{trans('main.buttons.save')}}</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="add_product_save-cancell-btn" type="button">{{trans('main.buttons.cancel')}}</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {

        $('#add_product_save-cancell-btn').on('click', function (e) {
            $('#manage_modal').modal('close');
        });

        $('select').material_select();

        var url = "{{route('users-add-product-save')}}";
        var form = $('#add_product_form');
        save_parameters(url, form);

        //$('#manage_admin_form').find('label.input-label-1').addClass('active');
    });
</script>