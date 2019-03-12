<ul class="collection with-header">
	<li class="collection-header side-collection-header custom-accent-color-1" style="padding-left: 28px;">
    	<a class="settings-types-controll modal-trigger waves-effect waves-light" id="add_menu_item-btn" href="#add_settings_type_modal"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="{{ trans('main.content.sidebar.settings_types.tools.add_item') }}">playlist_add</i></a>
    	{{-- <a class="settings-types-controll waves-effect waves-light"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50">lightbulb_outline</i></a> --}}
    	<a class="settings-types-controll waves-effect waves-light" id="clear_menu_items_filter-btn"><i class="material-icons dp48 tooltipped" data-position="top" data-delay="50" data-tooltip="{{ trans('main.content.sidebar.settings_types.tools.clear_all') }}" style="width: auto;">clear_all</i></a>
	</li>
	@forelse($menu_items as $t_t)
	<li data-id="{{$t_t->id}}" class="collection-item @if(isset($current_menu_item_id) && $current_menu_item_id == $t_t->id) active @endif">
	    <a href="#">
	        <h4 class="mail-title">{!! $t_t->name !!}</h4>
	        <span class="secondary-content hover-show settings_type-delete-btn" data-id="{{$t_t->id}}"><i class="material-icons">delete_forever</i></span>
	        <span class="item-sub-1"><i class="material-icons" style="color: lightgray;">keyboard_arrow_right</i></span>
	    </a>
	</li>
	@empty
    	<div class="empty-list-wrapper"><span><i class="material-icons">sentiment_dissatisfied</i> {{ trans('main.misc.list_empty') }}</span></div>
	@endforelse
</ul>
