<?php 
namespace App\Repositories\Contracts;

interface LanguageInterface {

	public function getAll();

	public function getById($id);

	public function create(array $attributes);

	public function getUpdate(array $data);

	public function update($id, array $data);

	public function delete(array $ids);

	public function changeStatus(array $ids);
}
