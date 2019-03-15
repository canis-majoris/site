<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;


class Region extends Model  {

    use SoftDeletes;
    
    protected $connection = 'mysql_admin';
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'regions';
    protected $dates = ['deleted_at'];


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
    public function products() 
    {
      return $this->hasMany('App\Models\Product\Product');
    }

    public function menu() 
    {
      return $this->hasMany('App\Models\Product\ProductMenu');
    }

    public function users() 
    {
      return $this->hasMany('App\Models\Tvoyo\User');
    }

    public function orders() 
    {
      return $this->hasMany('App\Models\Order\Order');
    }

    public function orders_products() 
    {
      return $this->hasMany('App\Models\Order\OrderProduct');
    }

    public function orders_statuses() 
    {
      return $this->hasMany('App\Models\Order\OrderStatus');
    }

    public function orders_products_statuses() 
    {
      return $this->hasMany('App\Models\Order\OrderProductStatus');
    }

    public function products_statuses() 
    {
      return $this->hasMany('App\Models\Product\ProductStatus');
    }

    public function orders_statuses_logs() 
    {
      return $this->hasMany('App\Models\Product\ProductStatusLog');
    }

    public function currency() 
    {
      return $this->hasMany('App\Models\Currency');
    }

    public function cartu_transactions() 
    {
      return $this->hasMany('App\Models\CartuTransaction');
    }

    public function dealers() 
    {
      return $this->hasMany('App\Models\Dealer\Dealer');
    }

    public function dealers_statistics() 
    {
      return $this->hasMany('App\Models\Dealer\DealerStat');
    }

    public function deliveries() 
    {
      return $this->hasMany('App\Models\Delivery');
    }

    public function discounts() 
    {
      return $this->hasMany('App\Models\Discount\Discount');
    }

    public function services_accounts() 
    {
      return $this->hasMany('App\Models\ServiceAccount');
    }

    public function transactions() 
    {
      return $this->hasMany('App\Models\Transaction');
    }

    public function promos() 
    {
      return $this->hasMany('App\Models\Promo\Promo');
    }

    public function paypal_transactions() 
    {
      return $this->hasMany('App\Models\Paypal\PaypalTransaction');
    }

    public function myphone_numbers() 
    {
      return $this->hasMany('App\Models\Myphone');
    }

    public function multiroom() 
    {
      return $this->hasMany('App\Models\Multiroom\Multiroom');
    }

    public function multiroom_control() 
    {
      return $this->hasMany('App\Models\Multiroom\MultiroomControl');
    }

     public function messages() 
    {
      return $this->hasMany('App\Models\Message');
    }

     public function map_points() 
    {
      return $this->hasMany('App\Models\MapPoint');
    }

    public static function make_current($id) 
    {   
        $region_id = 1;
        if ($region = Region::find($id)) {
            $region_id = $region->id;
        } else $region_id = 1;

        session(['region_id' => $region_id]);
        session(['current_database' => 'tvoyo_billing_' . $region_id]);
        \Session::save();
        return true;
    }

}