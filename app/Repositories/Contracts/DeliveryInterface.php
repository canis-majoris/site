<?php 
namespace App\Repositories\Contracts;

interface DeliveryInterface {

	public function getAll();

	public function getById($id);

	public function getTranslated();

	public function getTypes();

	public function getCreate(array $attributes);

	public function createNew($settings_type, array $arr, array $update_arr);

	public function updateExisting($id, $language_id, array $arr, array $update_arr);

	public function getUpdate(array $attributes);

	public function update($id = null, array $attributes);

	public function delete(array $ids);

	public function getSelect($id, array $select_array);

	public function getCount();

	public function changeStatus(array $ids);

	public function reorder(array $data);
}
