<?php 
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMenuItem extends Model  {

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
    protected $table = 'eshop_menus_products';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'eshop_menu_id', 'product_id', 'ord', 'get', 'created_at', 'updated_at', 'deleted_at'];

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
    //public $timestamps = false;

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function get_item($id) {
        return $this->find($id);
    }

    public function menu()
    {
        return $this->belongsTo('App\Models\Product\ProductMenu');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product');
    }

    /**
     * Returns object using given $index
     * 
     * @return objects
     * @access public
     */
    public function get_item_lang($index, $language_id) {
        return $this->where('ind', $ind)->where('language_id', $language_id)->get();
    }

}