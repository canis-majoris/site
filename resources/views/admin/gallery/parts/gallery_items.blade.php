<section class="grid-wrap scale-transition">
    <ul class="grid" id="lightgallery">
        <li class="grid-sizer"></li><!-- for Masonry column width -->
        @forelse($gallery_items as $gi)
            @php($ext = null)
            @if($gi->img)
                @php($src = 'img/'.$gi->type()->first()->title.'/'.$gi->img)
                @php($ext = explode('.', $gi->img)[1])
            @endif
            <li class="grid-item gallery-item col {{$layout_grid_gallery}}">
                <figure class=" gallery-item-figure-inline white shadow-0-1" data-img="{{$gi->img}}">
                    <div class="figure-content @if($ext == 'png') custom-color-1 lighten-5-transparent @else white @endif waves-effect waves-custom-color-3">
                        @if($gi->img)
                            <img src="{{ URL::asset($src) }}"  alt="{{ $gi->title }}" class="materialboxed responsive-img lazy"/>
                        @endif
                        <figcaption class="white"><span class="">{{ $gi->title }}</span><small>{{ $gi->description }}</small></figcaption>
                    </div>
                    <!-- <div class="figure-selected-indicator shadow-0-1"></div>
                    <input type="checkbox" name="gallery_images[]" value="{{$gi->img}}" style="visibility: hidden;"> -->
                </figure>
                <div class="action-wrapper-1">
                    <button type="button" class="gallery_item-edit-btn btn-flat white-text tooltipped left" data-id="{{$gi->id}}" data-position="top" data-delay="50" data-tooltip="{{trans('main.misc.edit')}}"><i class="material-icons">mode_edit</i></button>
                    <button type="button" class="remove-image-btn btn-flat white-text tooltipped left" data-id="{{$gi->id}}" data-position="top" data-delay="50" data-tooltip="{{trans('main.misc.delete')}}"><i class="material-icons">delete_forever</i></button>
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
        // $grid.masonry({
        //     // options
        //     itemSelector: '.grid-item',
        //     columnWidth: '.grid-item',
        //     percentPosition: true,
        //     gutter: 0
        // });

        $grid.imagesLoaded()
        .done(function(){
          $grid.masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-item',
            percentPosition: true,
            gutter: 0
          });
        });


        $(document).off('click', '.grid-data').on('click', '.grid-data', function(e) {
            $grid.masonry()
        });

        $(document).on('click', '.show-on-large, .side-slidable-toggle-btn', function(e) {
            setTimeout(function() {
                $grid.masonry()
            }, 200);
        });

        $('.materialboxed').materialbox();

        $('#items-container .tooltipped').tooltip({delay: 50});

    });
    
</script>