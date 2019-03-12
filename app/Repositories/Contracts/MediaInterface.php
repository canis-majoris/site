<?php 
namespace App\Repositories\Contracts;

interface MediaInterface {

	public function getAll();

	public function getById($id);

	public function create(array $attributes);

	public function update($id, array $attributes);

	public function delete($id);

	public function update_img($model, $data);

	// public function load_img_form($item, $data);

	public function attach_image_form($model, $data);

	public function attach_images($model, $data);

	public function load_attached_images($model, $data);

	public function load_gallery_items($model, $data);

	public function remove_img($model, $data);

}
