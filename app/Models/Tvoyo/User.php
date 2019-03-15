<?php

namespace App\Models\Tvoyo;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product\Product;
use App\Models\Product\ProductStatusLog;
use App\Models\Dealer\Dealer;
use App\Models\Order\OrderProduct;


class User extends Model
{

    //use SoftDeletes;

    protected $default_region_id = 1;
    protected $table = 'users';
    protected $prefix;
    protected $region;
    //protected $dates = ['deleted_at'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
        $this->prefix = config('database.region.'.$this->region.'.prefix');
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    //protected $table = 'tvoyo_eu_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'region_id', 'name', 'username', 'email', 'password', 'logins', 'last_login', 'name', 'surname', 'street', 'city', 'postcode', 'phone', 'bonus', 'firm', 'ic', 'dic',
        'fname', 'ffirm', 'fstreet', 'fcity', 'fpostcode', 'text', 'delivery', 'acivated', 'named', 'phoned', 'bday', 'bmonth', 'byear', 'gender', 'flat', 'country', 'mobile',
        'region', 'actual', 'is_diller', 'dealer_id', 'discount', 'transaction_id', 'user_rec', 'card_name_on_card', 'card_street', 'card_zip', 'card_city', 'card_country',
        'card_phone', 'card_email', 'card_card_bin', 'code', 'is_admin', 'score', 'percent', 'percent_for_dealer', 'reg_date', 'ticket', 'ticket_expires', 'friendcode',
        'friendnumber_id', 'buyyear', 'repass', 'percent_first', 'cash_payment_status', 'from_mobile', 'used_mob_trial', 'activated'];

        //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }

    public function scopeSearchSimple($query, $search_input) {
        return $query->where(function ($query) use ($search_input) {
            $query->where('username', 'LIKE', '%'.$search_input.'%')
            ->orWhere('email', 'LIKE', '%'.$search_input.'%')
            ->orWhere('name', 'LIKE', '%'.$search_input.'%')
            ->orWhere('code', 'LIKE', '%'.$search_input.'%');
        })->select();
    }

    public function scopeNoDiscount($query, $discount_id) {
        return $query->whereHas('user_discounts', function ($query) use($discount_id) {
            $query->where('discounts.id', '!=', $discount_id);
        }, '=', 0)->select();
    }

    public function scopeSelectDealers($query) {
        $region = $this->region;
        return $query->where('is_diller', 1)
            ->where(function ($query) use ($region) {
                $query->where('dealer_id', $region)
                ->orWhere(function ($query) {
                    $query->where('dealer_id', null)
                    ->where('is_user', 0);
                });
            })->select();
    }


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public function orders()
    {
        return $this->hasMany('App\Models\Order\Order', 'user_id');
    }

    public function orders_products()
    {
        return $this->hasMany('App\Models\Order\OrderProduct', 'user_id');
    }

    public function promos_used()
    {
        return $this->hasMany('App\Models\Promo\PromoUsed', 'user_id');
    }

    public function discounts_used()
    {
        return $this->hasMany('App\Models\Discount\DiscountUsed', 'user_id');
    }

    public function dealer_stats()
    {
        return $this->hasMany('App\Models\Dealer\DealerStat', 'user_id');
    }

    public function dealer_outputscore_stats()
    {
        return $this->hasMany('App\Models\Dealer\DealerOutputscoreStat', 'user_id');
    }

    public function cartu_transactions()
    {
        return $this->hasMany('App\Models\CartuTransaction', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'user_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }

    public function dealer()
    {
        return $this->hasOne('App\Models\Dealer\Dealer', 'user_id');
    }

    public function role()
    {
        return $this->belongsToMany('App\Models\Role', 'roles_users', 'user_id');
    }

    public function user_discounts()
    {
        return $this->belongsToMany('App\Models\Discount\Discount', 'users_discounts', 'user_id', 'discount_id');
    }

    public function dealer_discounts()
    {
        return $this->belongsToMany('App\Models\Discount\Discount', 'users_discounts', 'dealer_id', 'discount_id');
    }

    public function user_discounts_from_dealer()
    {
        $relation = null;
        if ($this->dealer_id) {
            $this->primaryKey = 'dealer_id';
            $relation = $this->belongsToMany('App\Models\Discount\Discount', 'users_discounts', 'dealer_id', 'discount_id');
            $this->primaryKey = 'id';
        }
        
        return $relation;
    }

    public function get_dealer()
    {
        return $this->belongsTo('App\Models\Dealer\Dealer', 'dealers', 'dealer_id');
    }

    public static function checkUnique(Array $arr){
        $region = session('region_id') ? session('region_id') : 1;
        //$connection_remote = config('database.region.'.$region.'.database_remote');
        if(User::where('region_id', $region)->where($arr['k'], '=', $arr['v'])->count() > 0 && User::where('region_id', $region)->where($arr['k'], '=', $arr['v'])->count() > 0) {
            return 'false';
        }
        return 'true';
    }

    public function check_orders() {
        return $this->orders()->whereIn('orders_status_id', [1, 2, 4, 6])->select();
    }

    public function get_mobile_services() {

        $as = $this->orders_products()
            ->where("is_service", "=", 1)
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 1)
                ->where('products.is_goods', '!=', 1);
            })
            /*->where(function ($query) {
                $query->where('order_id', "=", 0)
                ->orWhere(function ($query) {
                    $query->whereHas('order', function ($query) {
                         $query->where('orders.orders_status_id', '!=', 3);
                    });  
                });*/
           //})
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_services() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            /*->whereHas('order', function ($query) {})*/
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 1)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_services2() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_deactivated_services() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->whereHas('get_service_stbs', function ($query) {}, '=', 0)
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_free_services() {
        $as = $this->orders_products()
            ->where('is_service', 1)
            ->where('stoped', 0)
            ->where('s_code', 0)
            //->where('products_statuse_id', null)
            ->where('prolong_id', 0)
            ->whereHas('get_service_stbs', function ($query) {}, '=', 0)
            ->whereHas('product', function ($query) {
                $query->where('products.is_service', '=', 1)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy('orders_products.created_at', 'asc')
            ->select();

        return $as;
    }

    public function get_free_services_with_priority( $product_id ) {
        $as = $this->orders_products()
            ->where('is_service', 1)
            ->where('stoped', 0)
            ->where('s_code', 0)
            ->whereNull('products_statuse_id')
            ->where('prolong_id', 0)
            ->whereHas('get_service_stbs', function ($query) {}, '=', 0)
            ->whereHas('product', function ($query) {
                $query->where('products.is_service', '=', 1)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderByRaw('FIELD(product_id , ' . $product_id . ') desc, created_at asc')
            ->select();

        return $as;
    }

    public function get_services_nonstopped() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            ->where("stoped", "!=", 1)
            ->where("prolong_id", 0)
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_ps($region) {
        $user_id = $this->id;
        $region = session('region_id') ? session('region_id') : 1;
        $as = OrderProduct::where('region_id', $region)->where("is_p", 1)
            ->whereHas('order', function ($query) {
                $query->where('orders.user_id', '=', $user_id)
                ->where("orders.payed", 1);
            })
            ->whereHas('product', function ($query) {
                $query->where('products.is_p', 1)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_all_stbs() {

        $as = $this->orders_products()
            ->whereHas('product', function ($query) {
                $query->where('products.is_p', 1)
                ->where('products.is_goods', '!=', 1);
            })
            ->where(function ($query) {
                $query->whereHas('order', function ($query){
                    $query->where('orders.orders_status_id', '!=', 3);
                })
                ->orWhereHas('order', function ($query){}, '<', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_ps2() {

        $as = $this->orders_products()
            ->whereHas('product', function ($query) {
                $query->where('products.is_p', 1)
                ->where('products.is_goods', '!=', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_goods() {

        $as = $this->orders_products()
            ->whereHas('product', function ($query) {
                $query->where('products.is_goods', 1);
            })
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_free_active_stb() {

        $as = $this->orders_products()
            ->whereHas('product', function ($query) {
                $query->where('products.is_p', 1)
                ->where('products.is_goods', '!=', 1);
            })
            ->whereHas('get_stb_service', function ($query) {}, '=', 0)
            ->where('mac', '!=', null)
            ->where('mac', '!=', '')
           // ->where('products_statuse_id', 1)
           // 
            //->groupBy("orders_products.id")
            ->orderBy("orders_products.id", "desc")
            ->select();

        return $as;
    }

    public function get_aps($user = null) {
        return $this->orders_products()
            ->where("is_service", 1)
            ->where("stoped", "!=", 1)
            ->where("date_end", ">", time())
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->select();
    }

    public function get_active_multiroom_services() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            ->where("stoped", "!=", 1)
            ->where("date_end", ">", time())
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->whereHas('get_service_stbs', function ($query) {}, '>', 1)
            ->select();

        return $as;
    }

    public function get_active_non_multiroom_services() {
        $as = $this->orders_products()
            ->where("is_service", 1)
            ->where("stoped", "!=", 1)
            ->where("date_end", ">", time())
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->whereHas('get_service_stbs', function ($query) {}, '=', 1)
            ->select();

        return $as;
    }

    public static function rules ($id=null, $merge=[]) {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database');
        return array_merge(
            [
                'username' => 'required|max:100|regex:/^[-\pL\pN_.@]++$/uD|unique:users,username,' . $id . ',id,region_id,'.$region,
                'email' => 'required|email|max:255|unique:users,email,' . $id . ',id,region_id,'.$region,
            ], 
            $merge);
    }

    public function generate_code($region_id = null) {
        $prefix = $this->prefix['user'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['user'];
        } 
        return $prefix . str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    /**
     * Perform a hash, using the configured method.
     *
     * @param   string  string to hash
     * @return  string
     */
    public static function hash($str)
    {
        return hash('sha256', $str);
    }

    public static function find_salt($password)
    {
        $salt = '';
        $pattern = array("");

        foreach ($pattern as $i => $offset)
        {
            // Find salt characters, take a good long look...
            $salt .= substr($password, $offset + $i, 1);
        }

        return $salt;
    }

    /**
     * Creates a hashed password from a plaintext password, inserting salt
     * based on the configured salt pattern.
     *
     * @param   string  plaintext password
     * @return  string  hashed password string
     */
    public static function hash_password($password, $salt = FALSE)
    {
        // preg_split('/,\s*/', config('auth.salt_pattern'));
        $pattern = array("");
        if ($salt === FALSE)
        {
            // Create a salt seed, same length as the number of offsets in the pattern
            $salt = substr(self::hash(uniqid(NULL, TRUE)), 0, count($pattern));
        }


        // Password hash that the salt will be inserted into
        $hash = self::hash($salt.$password);
       
        // Change salt to an array
        $salt = str_split($salt, 1);

        // Returned password
        $password = '';

        // Used to calculate the length of splits
        $last_offset = 0;

        foreach ($pattern as $offset)
        {
            // Split a new part of the hash off
            $part = substr($hash, 0, $offset - $last_offset);

            // Cut the current part out of the hash
            $hash = substr($hash, $offset - $last_offset);

            // Add the part to the password, appending the salt character
            $password .= $part.array_shift($salt);

            // Set the last offset to the current offset
            $last_offset = $offset;
        }

        // Return the password, with the remaining hash appended
        return $password.$hash;
    }

    /*public static function find_salt($password){
        $salt = '';
        $pattern = array("");

        foreach ($pattern as $i => $offset)
        {
            // Find salt characters, take a good long look...
            $salt .= substr($password, $offset + $i, 1);
        }

        return $salt;
    }*/

    // public static function hash_password($password)
    // {
    //     $salt = self::find_salt($password);
    //     $pattern = array("");
    //     if ($salt === FALSE)
    //     {
    //         // Create a salt seed, same length as the number of offsets in the pattern
    //         $salt = substr(hash('sha256', uniqid(NULL, TRUE)), 0, count($pattern));
    //     }
    //     //echo "$salt<br>";

    //     // Password hash that the salt will be inserted into
    //     $hash =  hash('sha256', $salt.$password);

    //     // Change salt to an array
    //     $salt = str_split($salt, 1);

    //     // Returned password
    //     $password = '';

    //     // Used to calculate the length of splits
    //     $last_offset = 0;

    //     foreach ($pattern as $offset)
    //     {
    //         // Split a new part of the hash off
    //         $part = substr($hash, 0, $offset - $last_offset);

    //         // Cut the current part out of the hash
    //         $hash = substr($hash, $offset - $last_offset);

    //         // Add the part to the password, appending the salt character
    //         $password .= $part.array_shift($salt);

    //         // Set the last offset to the current offset
    //         $last_offset = $offset;
    //     }

    //     // Return the password, with the remaining hash appended
    //     return $password.$hash;
    // }
    // 
    

    public function manage_user_before_remove () {

        //CLEAN UP BEFORE DELETE
        
        $message = [];

        $orders = $this->orders();
        $dealer_stats = $this->dealer_stats();
        $promos_used = $this->promos_used();
        $discounts_used = $this->discounts_used();
        $user_discounts = $this->user_discounts();
        $dealer_discounts = $this->dealer_discounts();
        $orders_products = $this->orders_products()->get();

        $message []= '<ul class="message_log-alert-1"><h5>Associated Data</h5>';

        if ($orders->count()) {
            $message []= '<li>User orders deleted ('.$orders->count().')</li>';
            $this->orders()->delete(); 
        }

        if ($dealer_stats->count()) {
            $message []= '<li>User dealer statistics deleted ('.$dealer_stats->count().')</li>';
            $this->dealer_stats()->delete(); 
        }

        if ($promos_used->count()) {
            $message []= '<li>User promo code usage deleted ('.$promos_used->count().')</li>';
            $this->promos_used()->delete(); 
        }

        if ($discounts_used->count()) {
            $message []= '<li>User discount usage deleted ('.$discounts_used->count().')</li>';
            $this->discounts_used()->delete(); 
        }

        if ($user_discounts->count()) {
            $message []= '<li>User discounts deleted ('.$user_discounts->count().')</li>';
            $this->user_discounts()->delete(); 
        }

        if ($dealer_discounts->count()) {
            $message []= '<li>User dealer discounts deleted ('.$dealer_discounts->count().')</li>';
            $this->dealer_discounts()->delete(); 
        }

        $message []= '</ul><ul class="message_log-alert-1"><h5>Products</h5>';

        $this->role()->detach();

        foreach ($orders_products as $o_p) {
           // dd($orders_products->count());
            $o_p->logs()->delete();

            $message []= '<li>Product <b>'.$o_p->code.'</b> deleted</li>';

            $o_p->delete();
        }

        $message []= '</ul><ul class="message_log-alert-1"><h5>Transactions</h5>';

        $transactions = $this->cartu_transactions()->where("m_msg_type", "d")->get();
        if (count($transactions) && count($transactions_remote)) {
            $message []= '<li>' . count($transactions) . ' Cartu transactions deleted</li>';
            foreach($transactions as $tr) {
                $pass = "Vate2RukAsAw";
                $hash = md5($tr->m_desc . $id . $pass);
                $url = $this->domain .'/cartu/deletecard.php?TransactionId='.$r->m_desc.'&ClientId='.$id.'&hash='.$hash;
                /*$ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $answer = curl_exec($ch);
                    curl_close($ch);*/
                $tr->delete();
            }


        }

        $message []= '</ul>';

        $message = implode('', $message);

        return ['status' => true, 'message' => $message];
    }

    public function check_user_before_remove() {

        $services = $this->get_services2()->get();
        $mobile_services = $this->get_mobile_services()->get();
        $stbs = $this->get_ps2()->get();
        $goods = $this->get_goods()->get();

        $status = true;
        $result = ['status' => $status, 'message' => null];

        foreach ($services as $service) {
            $res = $service->check_service_before_remove();
            if (!$res['status']) {
                $result['services'][$service->code] = $res['message'];
                $result['status']= false;
            }
        }

        foreach ($stbs as $stb) {
            $res = $stb->check_stb_before_remove();
            if (!$res['status']) {
                $result['stbs'][$stb->code] = $res['message'];
                $result['status']= false;
            }
        }

        foreach ($mobile_services as $mobile_service) {
            $res = $mobile_service->check_mobile_service_before_remove();
            if (!$res['status']) {
                $result['mobile_services'][$mobile_service->code] = $res['message'];
                $result['status']= false;
            }
        }

        foreach ($goods as $good) {
            $res = $good->check_goods_before_remove();
            if (!$res['status']) {
                $result['goods'][$good->code] = $res['message'];
                $result['status']= false;
            }
        }

        if (isset($result['services']) && count($result['services'])) {
            $result['message'] .= '<h5>Services</h5><ul class="error_log-alert-1">' . implode('', $result['services']) . '</ul>';
            unset($result['services']);
        }

        if (isset($result['stbs']) &&  count($result['stbs'])) {
            $result['message'] .= '<h5>STBs</h5><ul class="error_log-alert-1">' . implode('', $result['stbs']) . '</ul>';
            unset($result['stbs']);
        }

        if (isset($result['mobile_services']) && count($result['mobile_services'])) {
            $result['message'] .= '<h5>Mobile services</h5><ul class="error_log-alert-1">' . implode('', $result['mobile_services']) . '</ul>';
            unset($result['mobile_services']);
        }

        if (isset($result['goods']) &&  count($result['goods'])) {
            $result['message'] .= 'Goods</h5><ul class="error_log-alert-1">' . implode('', $result['goods']) . '</ul>';
            unset($result['goods']);
        }

        return $result;
    }


    public function deleteHelper() {

        $users = [];
        $errors = [];
        $messages = [];
        $users ['local']= $this;
        
        foreach ($users as $k => $user) {
            if ($user) {

                $res = $user->check_user_before_remove();
                if ($res['status']) {
                    $res = $user->manage_user_before_remove();

                    if ($res['status']) {
                        $messages [$user->code]= 'User <b>'.$user->code.'</b> deleted' . $res['message'];

                        $user->delete();
                    }
                } else $errors [$user->code]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">'.$error.'</div>';
        }

        $messsage_str = '';
        foreach ($messages as $reg => $message) {
            $messsage_str .= '<div class="success_holder-dialog">'.$message.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str, 'messages' => $messsage_str];
    }

    public function saveHelper($data) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
        $users = [];
        $errors = [];
        $status = true;
        $users ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $users ['remote']= OrderProduct::where('region_id', $region)->find($this->id);
            } else {
                $object = new User;
                $object->setConnection($connection);
                $users ['remote'] = $object;
            }*/

            $id = ($this->id ? $this->id : null);

            foreach ($users as $connection => $user) {
                if ($user) {
                    foreach ($user->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $user->$attr = $data[$attr];
                        }
                    }

                    $user->region_id = $region;
                    
                    if ($id) {
                        $user->id = $id;
                    }

                    if(!$user->save()) { 
                        $errors [$connection]= 'something went wrong (could not save user  on '.$connection.')'; $status = false; 
                    } else {
                        $user->syncDealer($data);
                        $id = $user->id;
                    }
                } else { $errors [$connection]= 'something went wrong (could not save user on '.$connection.')'; $status = false; }
            }
        } else {
            $errors []= 'data not provided';
            $status = false;
        }

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">'.$error.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str];
    }

    public static function updateProductHelper($user_id, $product_id) {
        //$connection = config('database.region.'.$region.'.database_remote');
        $users = [
            'local' => User::filterRegion()->find($user_id),
            //'remote' => User::on($connection)->find($user_id)
        ];

        $products = [
            'local' => Product::filterRegion()->find($product_id),
            //'remote' => Product::on($connection)->find($product_id)
        ];

        $set_op_id = null;
        $set_log_id = null;
        foreach ($users as $location => $user) {
            $product = $products[$location];

            $is_service = 0;
            $is_p = 0;
            $for_mobile = 0;
            $p_name = '';

            if ($product->is_p) {
                $p_name = 'STB'.($user->orders_products()->where('is_p', 1)->count() + 1);
                $is_p = 1;
            } elseif ($product->is_service) {
                $is_service = 1;
            } elseif ($product->for_mobile) {
                $for_mobile = 1;
                $is_service = 1;
            }

            $op = new OrderProduct;
            $op->setConnection($product->getConnectionName());
            if ($set_op_id) {
                $op->id = $set_op_id;
            }
            $op->region_id = $user->region_id;
            $op->product_id = $product->id;
            $op->order_id = 0;
            $op->p_name = $p_name;
            $op->name = $product->name;
            $op->is_service = $is_service;
            $op->is_p = $is_p;
            $op->quantity = 1;
            if ($op->save()) {
                $set_op_id = $op->id;
                $mob_account_id = null;
                if ($product->is_p) {
                    $code  = $op->generate_stb_code($op->id);
                } elseif ($product->is_service) {
                    $code  = $op->generate_service_code($op->id);
                    $mob_account_id = $op->generate_mob_account_code($op->id);
                } elseif ($product->for_mobile) {
                    $code  = $op->generate_service_code($op->id);
                    $mob_account_id = $op->generate_mob_account_code($op->id);
                } elseif ($product->is_goods) {
                    $code  = $op->generate_goods_code($op->id);
                }
                
                $op->code = $code;
                $op->mob_account_id = $mob_account_id;
                $op->service_password = $op->generate_service_password();
                $op->save();

                $log = new ProductStatusLog;
                $log->setConnection($product->getConnectionName());
                if ($set_log_id) {
                    $log->id = $set_log_id;
                }
                $log->region_id = $user->region_id;
                $log->products_statuse_id = 10;
                $log->comment = 'Добавлено вручную';
                $log->owner = $user->id;
                $log->orders_product_id = $op->id;
                if($log->save()) {
                    $set_log_id = $log->id;
                }

                if(!$user->orders_products()->save($op)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function syncDealer($data) {
        if ($this->is_diller) {
            $tmp_dealer = $this->dealer;
            if (!$tmp_dealer) {
                $dealer = new Dealer;
            } else $dealer = $tmp_dealer;

            $dealer->user_id = $this->id;
            $dealer->region_id = $this->region_id;
            $dealer->name = $this->username;
            $dealer->percent = $data['percent'];
            $dealer->percent_first = $data['percent_first'];
            $dealer->status = 1;
            $dealer->save();
        } else {
            if ($this->dealer) {
                $this->dealer->delete();
            }
        }
        

        return true;
    }

    public static function updateUsersActivity(array $regions) {

        if (count($regions) > 0) {

            $users = User::whereIn('region_id', $regions)->get();
            $m_count = [0, 0, 0, 0, 0, 0];
            $active_mobile_services_count = false;
            $active_services_count = false;
            $get_active_multiroom_services = false;

            foreach ($users as $user) {
                $active_mobile_services_count = $user->get_mobile_services()->where('products_statuse_id', 5)->count();
                $active_services_count = $user->get_aps()->count();
                $get_active_multiroom_services = $user->get_active_multiroom_services()->count();
                
                if ($active_services_count > 0 && $get_active_multiroom_services > 0 && $active_mobile_services_count > 0) {
                    //active STB services with multiroom as well as mobile services
                    $user->active_service = 5;
                    $m_count[5]++;

                } elseif ($active_services_count > 0 && $get_active_multiroom_services > 0 && $active_mobile_services_count == 0) {
                    //active STB services with multiroom, no active mobile services
                    $user->active_service = 4;
                    $m_count[4]++;

                } elseif ($active_services_count > 0 && $get_active_multiroom_services == 0 && $active_mobile_services_count > 0) {
                    //active STB services as well as mobile services
                    $user->active_service = 3;
                    $m_count[3]++;

                } elseif ($active_services_count > 0 && $get_active_multiroom_services == 0 && $active_mobile_services_count == 0) {
                    //active STB services, no active mobile services
                    $user->active_service = 2;
                    $m_count[2]++;

                } elseif ($active_services_count == 0 && $get_active_multiroom_services == 0 && $active_mobile_services_count > 0) {
                    //active mobile services, no active STB services
                    $user->active_service = 1;
                    $m_count[1]++;

                } elseif ($active_services_count == 0 && $get_active_multiroom_services == 0 && $active_mobile_services_count == 0) {
                    //no active services
                    $user->active_service = 0;
                    $m_count[0]++;

                }

                $user->save();


            }

            dump($m_count);

            return true;
        }

        return false;
    }
}
