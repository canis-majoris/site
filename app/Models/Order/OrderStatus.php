<?php 
namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model  {

    //use SoftDeletes;
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
    protected $table = 'orders_statuses';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'name', 'default', 'text', 'created_at', 'updated_at', 'deleted_at'];

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }
    
    /**
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

    public function orders()
    {
        return $this->hasMany('App\Models\Order\Order');
    }

    public function get_default_status() {
        return $this->where('default', 1)->first();
    }

    /**
     * Returns object using given $index
     * 
     * @return objects
     * @access public
     */
   /* public function get_item_lang($index, $language_id) {
        return $this->where('index', $index)->where('language_id', $language_id)->get();
    }*/

}