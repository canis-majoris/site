<section class="grid-wrap scale-transition" id="selectable-gallery">
    <ul class="grid" id="lightgallery">
        <li class="grid-sizer"></li><!-- for Masonry column width -->
        @foreach($gallery_items as $gi)
            @php($src = 'img/'.$gallery_type->title.'/'.$gi->img)
            @php($selected_items = $translated->img != null ? json_decode($translated->img, true) : [])
            <li class="grid-item gallery-item col {{$layout_grid_gallery}}">
                <figure class=" gallery-item-figure-inline white @if(in_array($gi->img, $selected_items)) selected @endif" data-img="{{$gi->img}}">
                    <div class="figure-content white waves-effect waves-custom-color-3">
                        <img src="{{ URL::asset($src) }}" alt="{{ $gi->title }}" class="responsive-img"/>
                        <figcaption><span class="">{{ $gi->title }}</span><small>{{ $gi->description }}</small></figcaption>
                    </div>
                    <div class="figure-selected-indicator shadow-0-1"></div>
                    <input type="checkbox" name="gallery_images[]" value="{{$gi->img}}" style="visibility: hidden;">
                </figure>
            </li>
        @endforeach
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

        // $grid.on( 'layoutComplete', function( event, items ) {
        //   $grid.masonry();
        // });

        // $(window).trigger('resize');

        //$("#lightgallery").lightGallery();
    });
    
</script>