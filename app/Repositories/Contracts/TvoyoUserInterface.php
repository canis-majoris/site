<?php 
namespace App\Repositories\Contracts;

interface TvoyoUserInterface {

	public function getAll();

	public function selectAll();

	public function getById($id);

	public function create(array $attributes);

	public function getAllDealers();

	public function selectDefaultRegionUsers();


}
