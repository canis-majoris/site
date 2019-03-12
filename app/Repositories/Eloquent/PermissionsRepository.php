<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\PermissionsInterface;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Config;

class PermissionsRepository extends BaseRepository implements PermissionsInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Permission $model, Role $model_type) {
		$this->model 	  = $model->filterRegion();
		$this->model_type = $model_type->filterRegion();
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
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
	public function createNew($item_type, $role, array $arr, array $update_arr) {
        $item = new Permission;

        $result = $item->saveHelper($arr, $role);

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function updateExisting($id, $role, array $arr, array $update_arr) {
		$item = $this->getByid($id);

        $result = $item->saveHelper($arr, $role);

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function getUpdate(array $data) {

		$id = (isset($data['permission_id']) && !empty($data['permission_id'])) ? $data['permission_id'] : null;
        $item = null;
        $item_type_id = $data['role_id'] ?? null;

        if ($id) {
            $item = $this->getByid($id);

            $item_type_id = $item->roles()->count() > 0 ? $item->roles()->first()->id : null;

        } else {
            if (isset($data['current_menu_item_id'])) {
                $item_type_id = $this->getTypes()->find($data['current_menu_item_id']);
            }
        }

        return ['permission' => $item, 'role_id' => $item_type_id];
	}

	//Manage Settings Item
	/**
	 * @param  array
	 * @return [type]
	 */

	public function update($id = null, array $data) {

        $arr = [];
        $update_arr = [];
        $language_id = null;
        $message = null;
        $result = null;

        $arr['name']          = isset($data["name"]) ? trim($data["name"]) : null;
        $arr['display_name']  = isset($data["display_name"]) ? trim($data["display_name"]) : null;
        $arr['description']   = isset($data["description"]) ? trim($data["description"]) : null;

        if (!isset($data['current_menu_item_id']) && isset($data['permission_id'])) {
            $item_type = $this->getById($data['permission_id'])->roles()->first();
        } elseif (isset($data['current_menu_item_id'])) {
            $item_type = $this->getTypes()->find($data['current_menu_item_id']);
        }

        if (isset($data['permission_id']) && $data['permission_id'] != null) {

            //update existing content

        	$result = $this->updateExisting($data['permission_id'], $item_type, $arr, $update_arr);

            $id = $result['item']->id;

            $message = 'Permission updated';

        } else {

            //add new content
            
            $result = $this->createNew($item_type, $item_type, $arr, $update_arr);

            $id = $result['item']->id;

            $message = 'New permission created';
        }

        if (isset($result['result']['errors']) && !empty($result['result']['errors'])) {
            $message = $result['result']['errors'];
        }

        return ['success' => $result['result']['status'], 'status' => $result['result']['status'], 'message' => $message, 'result' => ['id' => $id]];
	}

	public function delete(array $ids) {

        $errors = [];
        $messages = [];
        $status = 1; 

         foreach ($ids as $id) {
            $permission = $this->getById($id);

            if ($permission) {
                $code = $permission->display_name;

                if ($result = $permission->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$code]= $result['errors'];

                        $status = $result['status'];
                    } else $messages [$code]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
	}
}
