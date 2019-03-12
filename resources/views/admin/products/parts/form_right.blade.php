<ul class="collection with-header">
	<li class="collection-header side-collection-header custom-accent-color-1" style="padding-left: 28px;">
    	<a class="menu-items-controll modal-trigger waves-effect waves-light" id="add_menu_item-btn" href="#add_menu_item_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="{{ trans('main.catalog.sidebar.menu.tools.add_item') }}">playlist_add</i></a>
    	{{-- <a class="menu-items-controll waves-effect waves-light"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50">lightbulb_outline</i></a> --}}
    	<a class="menu-items-controll waves-effect waves-light" id="clear_menu_items_filter-btn"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="{{ trans('main.catalog.sidebar.menu.tools.clear_all') }}" style="width: auto;">clear_all</i></a>
	</li>
	@forelse($menu_items as $m_i)
	<li data-id="{{$m_i->id}}" class="collection-item @if(isset($current_menu_item_id) && $current_menu_item_id == $m_i->id) active @endif">
		@php($trans1 = $m_i->translated()->where('language_id', $language_id)->first())
	    <a href="#">
	        <h4 class="mail-title">@if($trans1){!! $trans1->name !!}@endif</h4>
	        {{-- <p class="hide-on-small-and-down mail-text">{!! $m_i->description !!}</p> --}}
	        <span class="secondary-content hover-show menu_item-delete-btn" data-id="{{$m_i->id}}"><i class="material-icons">delete_forever</i></span>
	        <span class="item-sub-1"><i class="material-icons" style="color: lightgray;">keyboard_arrow_right</i></span>
	    </a>
	</li>
	@empty
    	<div class="empty-list-wrapper"><span><i class="material-icons">sentiment_dissatisfied</i> {{ trans('main.misc.list_empty') }}</span></div>
	@endforelse
</ul>
