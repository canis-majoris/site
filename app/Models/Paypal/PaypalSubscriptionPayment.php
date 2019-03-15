<?php 
namespace App\Models\Paypal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaypalSubscriptionPayment extends Model  {

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
    protected $table = 'paypal_subcription_payments';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'subscr_id', 'region_id', 'user_id', 'product_id', 'payment_date', 'payment_data', 'status'];

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
    public $timestamps = false;

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */

    public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }
    

}