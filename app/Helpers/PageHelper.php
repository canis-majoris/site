<?php

namespace App\Helpers;

use App\Page\Page;
use App\Page\PageLanguage;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class PageHelper {
	public static function generate_menu($menu, $language_id=null, $data, $parent=null) {

		$html = '';
		//if ($menu->count() > 0) {
			$html .= '<ol class="dd-list">';
		//}

		foreach ($menu as $item) {
			$children = $item->children()->orderBy('order')->get();
			$nested = $children->count() > 0 ? true : false;

			// foreach ($children as $c) {
			// 	dump($c->id);
			// }

			//die;
			$parent = $item;
			$translated = $item->translated()->where('language_id', $language_id)->first();

			$name = $item->name;
			$nested_list_state = ($data['nested_list_state'] == 'true' ? true : false);
			$collapsed_class = !$nested_list_state ? 'dd-collapsed' : null;

			if ($translated) {
				$name = $translated->name;
			}

			// if (isset($data['current_menu_item_id']) && $item->id == $data['current_menu_item_id']) {
			// 	$collapsed_class = null;
			// }

			$html .= '<li class="dd-item dd3-item nested-level-' . $item->root . ' ' . ( $nested ? 'nested-list ' . $collapsed_class . ' ' : null ) . ( $item->status == 0 ? 'hidden-list' : 'visible-list' ) . '" data-id="' . $item->id . '">
				<div class="dd-handle dd3-handle">Drag</div>
            	<div class="dd3-content collection-item" data-id="' . $item->id . '"><span>' . $name . '</span>
            		<span class="secondary-content hover-show page-delete-btn" data-id="' . $item->id . '"><i class="material-icons">delete_forever</i></span>
            		<span class="primary-content page-add_item-btn" data-id="' . $item->id . '"><i class="material-icons">playlist_add</i></span>
            		<span class="item-sub-1"><i class="material-icons" style="color: lightgray;">keyboard_arrow_right</i></span>
            	</div>';

            if ($nested) {
				$html .= PageHelper::generate_menu($children, $language_id, $data, $parent);
			}

			$html .= '</li>';
		}

		//if ($menu->count() > 0) {
			$html .= '</ol>';
		//}

		return $html;
    }
}