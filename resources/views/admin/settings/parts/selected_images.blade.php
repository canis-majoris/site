<section class="grid-wrap scale-transition">
    <ul class="grid" id="lightgallery">
        <li class="grid-sizer"></li><!-- for Masonry column width -->
        @forelse($gallery_items as $gi)
            @php($src = 'img/settings/'.$gi->img)
            @php($ext = explode('.', $gi->img)[1])
            <li class="grid-item gallery-item col {{$layout_grid_gallery}}">
                <figure class=" gallery-item-figure-inline white shadow-0-1" data-img="{{$gi->img}}">
                    <div class="figure-content @if($ext == 'png') custom-color-1 lighten-5 @endif waves-effect waves-custom-color-3">
                        <img src="{{ URL::asset($src) }}" alt="{{ $gi->title }}" class="materialboxed responsive-img"/>
                        <figcaption class="white"><span class="">{{ $gi->title }}</span><small>{{ $gi->description }}</small></figcaption>
                    </div>
                </figure>
                <div class="action-wrapper-1">
                    <button type="button" class="remove-image-btn btn-flat white-text tooltipped left" data-img="{{$gi->img}}" data-position="top" data-delay="50" data-tooltip="{{trans('main.misc.remove')}}"><i class="material-icons">close</i></button>
                </div>
            </li>
        @empty
            <li class="grid-item col l12 m12 s12"><div class="empty-list-wrapper"><span><i class="material-icons">sentiment_dissatisfied</i> {{trans('no_attached_images')}}</span></div></li>
        @endforelse
    </ul>
</section><!-- // grid-wrap -->
<script type="text/javascript">
    
    $(document).ready(function (e) {

        $grid = $('.grid');
        $grid.imagesLoaded()
        .done(function(){
          $grid.masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-item',
            percentPosition: true,
            gutter: 0
          });
        });

        $(document).off('click', '#options').on('click', '#options', function(e) {
            $grid.masonry()
        });

        $('.materialboxed').materialbox();

        $('#load_selected_images .tooltipped').tooltip({delay: 50});


        '@if($item != null)'
            $(document).off('click', '.remove-image-btn').on('click', '.remove-image-btn', function(e) {
                var img = $(this).data('img');
                var wrapper = $(this).closest('.gallery-item');
                var container = $('#load_selected_images');
                var data = {'id': "{{$item->id}}", 'language_id': $('#current_lang_id').val()};

                swal.queue([{
                  title: "{{trans('main.misc.remove.img.title')}}",
                  text: "{{trans('main.misc.remove.img.text')}}",
                  showLoaderOnConfirm: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "{{trans('main.misc.remove')}}",
                  cancelButtonText: "{{trans('main.misc.cancel')}}",
                  preConfirm: () => {
                    swal.showLoading();
                    $(wrapper).fadeOut(100, function() {
                        update_glob("{{route('settings-remove_img')}}", {'id': "{{$item->id}}", 'language_id': $('#current_lang_id').val(), 'img': img}, function() {
                            manage_load("{{route('settings-load_attached_images')}}", container, data);
                            swal("{{trans('main.misc.removed')}}", "{{trans('main.misc.remove.img.success.title')}}", "success");
                        });
                    });
                  }
                }])
            });
        '@endif'
    });
    
</script>