<?php 
namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class OrderProductStatus extends Model  {

	use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders_products_statuses';
	//protected $dates = ['deleted_at'];
	protected $fillable = ['region_id', 'orders_product_id', 'products_statuse_id', 'created_at', 'updated_at', 'deleted_at'];

	//scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }
    
	/**
	 * The timestamps.
	 *
	 * @var bool
	 */
	//public $timestamps = true;

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	/*public function language() 
	{
		return $this->belongsToMany('App\Language', 'languages_products');
	}*/

	/*public function menu_item() 
	{
		return $this->belongsToMany('App\ProductMenu', 'eshop_menus_products', 'product_id', 'eshop_menu_id');
	}*/


    public function orders_product()
    {
        return $this->belongsTo('App\Models\Order\OrderProduct', 'orders_product_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Product\ProductStatus', 'products_statuse_id');
    }

}