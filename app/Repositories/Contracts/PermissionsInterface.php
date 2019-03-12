<?php 
namespace App\Repositories\Contracts;

interface PermissionsInterface {

	public function getAll();

	public function getById($id);

	public function getTypes();

	public function getCreate(array $attributes);

	public function getUpdate(array $attributes);

	public function update($id = null, array $attributes);

	public function delete(array $ids);

	public function getSelect($id, array $select_array);

	public function getCount();
}
