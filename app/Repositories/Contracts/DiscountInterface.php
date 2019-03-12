<?php 
namespace App\Repositories\Contracts;

interface DiscountInterface {

	public function validator(array $attributes);

	public function getAll();

	public function getById($id);

	public function getByIds(array $ids);

	public function create(array $attributes);

	public function update($id, array $attributes);

	public function delete($id);

	public function filterData(array $data);

	public function getDiscountUsers();
}
