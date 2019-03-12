<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\DeliveryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Delivery;
use App\Models\Region;
use Config;
use Image;

class DeliveryRepository extends BaseRepository implements DeliveryInterface  {

	/**
	 * @param Delivery
	 */
	public function __construct(Delivery $model, Language $language) {
		$this->model 	= $model->filterRegion();
		$this->language = $language->filterRegion();
		$this->region   = session('region_id') ? session('region_id') : $this->default_region_id;
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
	public function createNew($item_type, array $arr, array $update_arr) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function updateExisting($id, $language_id, array $arr, array $update_arr) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function getUpdate(array $data) {
		$id = $data['id'] ?? null;

		$delivery = null;

		if ($id) {
            $delivery = $this->getByid($id);
        } else {
            $delivery = null;
        }

        return compact('delivery');
	}

	/**
     * Update delivery item.
     *
     * @param  $id, array $data
     * @return void
     */
	public function update($id = null, array $data) {

		$arr = [];
        $images = null;
        $message = null;
        $result = null;

		$arr['name']    = $data['name'] ?? null;
        $arr['price']   = $data['price'] ?? null;
        $arr['comment'] = $data['comment'] ?? null;
        $arr['hide']   	= (isset($data['hide']) && $data['hide'] == 'on') ? 0 : 1;

        $images = null;

        if (isset($data['gallery_images']) && !empty($data['gallery_images'])) {
            $images = json_encode($data['gallery_images']);
        } elseif (isset($data["image_name"]) && !empty($data['image_name'])) {
            $images = json_encode([$data["image_name"]]);
        }

        $arr['img'] = $images;

		if ($id) {
            $delivery = $this->getByid($data['id']);
        } else {
            $delivery = new Delivery;
        }

        if ($delivery) {
            $result = $delivery->saveHelper($arr);

            $message = 'Delivery updated';

            if ($result['errors']) {
                $message = $result['errors'];
            }

            $id = $delivery->id;
        }

        return ['success' => true, 'status' => $result['status'], 'message' => $message, 'result' => ['id' => $id]];
	}

	/**
     * Delete delivery item.
     *
     * @param  array $ids
     * @return void
     */
	public function delete(array $ids) {

        $errors = [];
        $messages = [];
        $status = 1; 

        foreach ($ids as $id) {
            $delivery = $this->getByid($id);

            if ($delivery) {
                $code = $delivery->code;

                if ($result = $delivery->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$code]= $result['errors'];

                        $status = $result['status'];

                    } else $messages [$code]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
	}

	/**
     * Reorder delivery items.
     *
     * @param  array $data
     * @return void
     */
	public function reorder(array $data) {

		$tmp_arr = [];

        if (isset($data['current_menu_item_id']) && $data['current_menu_item_id'] != '' && isset($data['diff'])) {

            $item_type = $this->getTypes()->find($data['current_menu_item_id']);
            $i = 0;
            $for_ids = array_keys($data['diff']);
            $item = $item_type->items()->whereIn('products.ord', $for_ids)->orderBy('products.id', 'asc')->get();

            foreach ($item as $s) {

                $i++;
                $s->ord = $data['diff'][$s->ord];
                $tmp_arr[$s->ord] = $data['diff'][$s->ord];
                $s->save();

            }

            return true;
       
        }

        return false;
	}


	/**
     * Change delivery item status.
     *
     * @param  array $ids
     * @return void
     */
	public function changeStatus(array $ids) {

		$errors = '';
        $status = null;

        foreach ($ids as $id) {

            $item = $this->getById($id);
            $item->hide = ( $item->hide == 1 ? 0 : 1 );

            if ($result = $item->save()) {

                $errors .= ($result['errors'] ? $result['errors'] : '');
                $status = !$item->hide;

            }
        }

        return ['status' => $status, 'errors' => $errors];
	}

}
