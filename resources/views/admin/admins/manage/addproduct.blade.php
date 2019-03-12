<form id="add_product_form">
    <div class="modal-content row">
        <h4>Add {{$type}}</h4>
        <div>
           <div class="input-field col s12">
              <select class="" style="width: 100%" name="product">
                  @foreach($products as $product)
                      <option value="{{$product->id}}">{{$product->name}}</option>
                  @endforeach
              </select>
              <label>{{trans('main.user.add.'.$type)}}</label>
           </div>
           <div class="clear"></div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="waves-effect waves-green btn-flat" id="add_product_save-btn" type="button">SAVE</button>
        <button class="modal-action modal-close waves-effect waves-light btn-flat" id="add_product_save-cancell-btn" type="button">cancel</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#add_product_save-btn').on('click', function (e) {
            $('#add_product_form').submit();
            $(this).unbind('click');
        });

        $('#add_product_save-cancell-btn').on('click', function (e) {
            $('#add_product_modal').closeModal();
        });

        $('select').material_select();

        var url = "{{route('users-add-product-save')}}";
        var form = $('#add_product_form');
        save_add_user_product(url, form);

        //$('#manage_admin_form').find('label.input-label-1').addClass('active');
    });
</script>