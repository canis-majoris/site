<?php 
namespace App\Repositories;

abstract class BaseRepository {

	/**
	 * The Model instance.
	 *
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	protected $domain;
    protected $region;
    protected $remote_connection;
    protected $default_language_id = 1;
    protected $default_region_id = 1;

    public function __construct() {
    	$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->domain = config('database.region.'.$this->region.'.domain');
    }
}
