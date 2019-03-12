<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\OrderInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use App\Models\Product\ProductStatusLog;
use App\Models\Language;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderProductStatus;
use App\Models\CartuTransaction;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Region;
use App\Models\Dealer\Dealer;
use Config;
use Image;

class OrderRepository extends BaseRepository implements OrderInterface {

	/**
	 * @param OrdersProducts
	 */
	public function __construct(Order $model, Product $product, ProductStatus $product_status, Language $language, OrderProductStatus $order_product_status, CartuTransaction $cartu_transaction, ProductStatusLog $product_status_log, User $user, Dealer $delaer) {
		$this->model 		        = $model->filterRegion();
		$this->product	            = $product->filterRegion();
		$this->product_status       = $product_status;
        $this->language             = $language->filterRegion();
        $this->order_product_status = $order_product_status;
        $this->cartu_transaction    = $cartu_transaction->filterRegion();
        $this->product_status_log   = $product_status_log->filterRegion();
        $this->user                 = $user->filterRegion();
		$this->delaer 	            = $delaer->filterRegion();
		$this->region               = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @return [type]
	 */
	public function getAll() {
		return $this->model->get();
	}

	/**
	 * @return [type]
	 */
	public function getTranslated() {
		return $this->model_language->get();
	}

	/**
	 * @return [type]
	 */
	public function getTypes() {
		return $this->model_type->get();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {

		return $this->model->find($id);
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getCount() {

		return $this->model->count();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getSelect($id, array $select_array) {

		return $this->model->select($select_array)->find($id);
	}

	public function getCreate(array $attributes) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function createNew(array $arr, array $update_arr) {

		$order = $this->model->orderBy('order', 'desc')->first()->order + 1;
        $index = $this->model->orderBy('index', 'desc')->first()->index + 1;
        $root = 0;
        $parent_item = null;

        if ($arr['parent_id'] != null) {
            $parent_item = $this->getByid($arr['parent_id']);
            if ($parent_item) {
                if ($parent_item->children()->count() > 0) {
                    $last_child = $parent_item->children()->orderBy('order', 'desc')->first();
                    $order = $last_child->order + 1;
                } else {
                    $order = $parent_item->order + 1;
                }

                $root = $parent_item->root + 1;

                $all_next = $this->model->where('order', '>=', $order)->get();

                foreach ($all_next as $a_n) {
                    $a_n->order = $a_n->order + 1;
                    $a_n->save();
                }
            }
        }

        $arr['order'] = $order;
        $arr['root'] = $root;
        $arr['index'] = $index;

        $languages = $this->language->get();
        $item = new Page;
        $result = $item->saveHelper($arr);

        if ($parent_item) {
            $parent_item->children()->save($item);
        }

        foreach ($languages as $lang) {

            $update_arr['language_id'] = $lang->id;
            $translated = new PageLanguage;
            $translated->saveHelper($update_arr);
            $item->translated()->save($translated);

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function updateExisting($id, $language_id, array $arr, array $update_arr) {

		$languages = $this->language->get();
		$item = $this->getByid($id);
        $result = $item->saveHelper($arr);
        $translated = $item->getTranslatedByLanguageId($language_id, ['*']);

        if ($translated) {

            $translated->saveHelper($update_arr);

        } else {

            foreach ($languages as $lang) {

                $update_arr['language_id'] = $lang->id;
                $translated = new PageLanguage;
                $translated->saveHelper($update_arr);
                $item->translated()->save($translated);

            }

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function getUpdate(array $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$language_id = null;
        $item = null;
        $translated = null;
        $uploaded_img = null;
        $parent_item = null;
        $item_type = null;
        $add_item_to_parent = false;

		if (isset($data['language_id'])) {

            $language_id = $data['language_id'];

            if ($id) {
                $item = $this->getByid($id);
                $translated = $item->getTranslatedByLanguageId($language_id, ['language_id', 'name', 'seo_title', 'seo_keywords', 'seo_description', 'img', 'alt', 'text', 'watch', 'video', 'created_at', 'updated_at']);

                $uploaded_img = $item->translated()->where('img', '!=', '')->whereNotNull('img')->count();

            } else {
                if (isset($data['parent_id']) && !empty($data['parent_id'])) {
                    $parent_item = $this->getByid($data['parent_id']);
                    $add_item_to_parent = true;
                }
            }
        }

        return ['page' => $item, 'translated' => $translated, 'page_type' => $item_type, 'parent_item' => $parent_item, 'language_id' => $language_id, 'uploaded_img' => $uploaded_img, 'add_item_to_parent' => $add_item_to_parent];
	}

	//Manage Text Item
	/**
	 * @param  array
	 * @return [type]
	 */

	public function update($id = null, array $data) {

        $arr = [];
        $update_arr = [];
        $language_id = $data['language_id'];
        $images = null;
        $message = null;
        $result = null;

        $arr['url']                    = (isset($data["url"]) ? $data["url"] : null);
        $arr['source']                 = (isset($data["source"]) ? $data["source"] : null);
        $arr['name_trans']             = (isset($data["name_trans"]) ? $data["name_trans"] : null);
        $arr['method']                 = (isset($data["method"]) ? $data["method"] : null);
        $arr['is_text']                = (isset($data["is_text"]) && $data["is_text"] == "on") ? 1 : 0;
        $arr['watch']                  = (isset($data["watch_global"]) && $data["watch_global"] == "on") ? 1 : 0;
        $arr['tags']                   = (isset($data["tags"]) ? json_encode($data["tags"]) : "");
        $arr['parent_id']              = (isset($data['parent_id']) && $data['parent_id'] != null) ? $data['parent_id'] : null;
        $update_arr['watch']           = (isset($data["watch"]) && $data["watch"] == "on") ? 1 : 0;
        $update_arr['video']           = isset($data["video"]) ? $data["video"] : null;
        $update_arr['text']            = isset($data["text"]) ? $data["text"] : null;
        $update_arr['alt']             = isset($data["alt"]) ? $data["alt"] : null;
        $update_arr['seo_title']       = isset($data["seo_title"]) ? $data["seo_title"] : null;
        $update_arr['seo_keywords']    = isset($data["seo_keywords"]) ? json_encode($data["seo_keywords"]) : null;
        $update_arr['seo_description'] = isset($data["seo_description"]) ? $data["seo_description"] : null;
        $update_arr['img']             = isset($data["text_image_name"]) ? trim($data["text_image_name"]) : null;
        $update_arr['name']            = htmlspecialchars($data["name"]);
        $update_arr['img'] = $images;

        if (isset($data['page_id']) && $data['page_id'] != null) {

            //update existing content

        	$result = $this->updateExisting($data['page_id'], $language_id, $arr, $update_arr);

            $id = $result['item']->id;
            $message = 'Page item updated';

        } else {

            //add new content
            
            $result = $this->createNew($arr, $update_arr);

            $id = $result['item']->id;
            $message = 'New page item created';
        }

        if (isset($result['result']['errors']) && !empty($result['result']['errors'])) {

            $message = $result['result']['errors'];

        }

        return ['success' => $result['result']['status'], 'status' => $result['result']['status'], 'message' => $message, 'result' => ['id' => $id]];
	}

    /**
     * @param  array
     * @return [type]
     */

    public function loadNestableList(array $data) {

        $generated_nestable_list = '';

        if (isset($data['language_id'])) {

            $generated_nestable_list = Page::getNestableList($data);

        }

        $add_item_to_parent = $data['add_item_to_parent_id'] == 'false' ? false : $data['add_item_to_parent_id'];
        $current_menu_item_id = (isset($data['current_menu_item_id']) && !empty($data['current_menu_item_id'])) ? $data['current_menu_item_id'] : ($add_item_to_parent ? $add_item_to_parent : null);

        return compact('generated_nestable_list', 'add_item_to_parent', 'current_menu_item_id');
    }
    
    /**
     * @param  array
     * @return [type]
     */
    
    public function updateNestableList(array $data) {

        $generated_nestable_list = '';

        if (isset($data['list'])) {

            $generated_nestable_list = Page::updateNestableList($data);

        }

        return compact('generated_nestable_list');
    }

    /**
     * @param  array
     * @return [type]
     */

	public function delete(array $ids) {

        $errors = '';

        foreach ($ids as $id) {

           if ($result = $this->getById($id)->deleteHelper()) {

                $errors .= ($result['errors'] ? $result['errors'] : '');

            }
        }

        return $errors;
	}

    /**
     * @param  array
     * @return [type]
     */

	public function changeStatus(array $ids) {

		$errors = '';
        $status = null;

        foreach ($ids as $id) {

            $item = $this->getById($id);
            $item->watch = ( $item->watch == 1 ? 0 : 1 );

            if ($result = $item->save()) {

                $errors .= ($result['errors'] ? $result['errors'] : '');
                $status = $item->watch;

            }

        }

        return ['status' => $status, 'errors' => $errors];
	}

}
