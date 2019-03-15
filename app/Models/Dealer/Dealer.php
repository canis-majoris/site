<?php 
namespace App\Models\Dealer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model  {

   // use SoftDeletes;
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
    protected $table = 'dealers';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'user_id', 'name', 'percent', 'percent_first', 'status', 'created_at', 'updated_at', 'deleted_at'];

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
    public $timestamps = true;

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    /*public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Tvoyo\User', 'user_id');
    }*/

    public function users()
    {
        return $this->hasMany('App\Models\Tvoyo\User', 'dealer_id');
    }
    

}