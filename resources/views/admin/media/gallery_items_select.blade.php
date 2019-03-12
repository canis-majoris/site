<section class="grid-wrap scale-transition" id="selectable-gallery">
    <ul class="grid" id="lightgallery">
        <li class="grid-sizer"></li><!-- for Masonry column width -->
        @forelse($gallery_items as $gi)
            @php($src = 'img/'.$gallery_type->title.'/'.$gi->img)
            @php($ext = explode('.', $gi->img)[1])
            @php($selected_items = [])

            @if($translated != null && $translated->img)
                @php($selected_items = json_decode($translated->img, true))
            @elseif($item != null && $item->img)
                @php($selected_items = json_decode($item->img, true))
            @endif

            <li class="grid-item gallery-item col {{$layout_grid_gallery}}">
                <figure class=" gallery-item-figure-inline white @if(in_array($gi->img, $selected_items)) selected @endif" data-img="{{$gi->img}}">
                    <div class="figure-content @if($ext == 'png') custom-color-1 lighten-5 @endif waves-effect waves-custom-color-3">
                        <img src="{{ URL::asset($src) }}" alt="{{ $gi->title }}" class="responsive-img"/>
                        <figcaption class="white"><span class="">{{ $gi->title }}</span><small>{{ $gi->description }}</small></figcaption>
                    </div>
                    <div class="figure-selected-indicator shadow-0-1 @if(in_array($gi->img, $selected_items)) pulse-s @endif"></div>
                    <input type="checkbox" name="gallery_images[]" value="{{$gi->img}}" style="visibility: hidden;">
                </figure>
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

        $(document).on('click', '.show-on-large, .side-slidable-toggle-btn', function(e) {
            setTimeout(function() {
                $grid.masonry()
            }, 200);
        });
    });
    
</script>