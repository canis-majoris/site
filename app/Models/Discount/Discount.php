<?php 
namespace App\Models\Discount;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model  {

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
    protected $table = 'discounts';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'code', 'type', 'type_discount', 'date_create', 'date_start', 'date_end', 'date_used', 'limit1', 'percent', 'products', 'products_percent', 'status', 'created_at', 'updated_at', 'deleted_at'];
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
    
    public function users()
    {
        return $this->belongsToMany('App\Models\Tvoyo\User', 'users_discounts', 'discount_id', 'user_id');
    }

    public function dealers()
    {
        return $this->belongsToMany('App\Models\Tvoyo\User', 'users_discounts', 'discount_id', 'dealer_id');
    }

    public function log()
    {
        return $this->hasMany('App\Models\Discount\DiscountUsed', 'discount_id');
    }


    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }

    public function manage_discount_before_remove() {

        $this->users()->detach();
        $this->dealers()->detach();

        return ['status' => true, 'message' => null];
    }
    
    public function deleteHelper() {

        $discounts = [];
        $errors = [];
        $messages = [];
        $discounts ['local']= $this;
        
        foreach ($discounts as $k => $discount) {

            if ($discount) {
                //$res = $product->check_product_before_remove();
                //if ($res['status']) {
                    $res = $discount->manage_discount_before_remove();
                    if ($res['status']) {
                        $messages [$discount->code]= 'Discount <b>'.$discount->code.'</b> deleted';
                        $discount->delete();
                    } else $errors [$discount->code]= $res['message'];
                //} else $errors [$discount->code]= $res['message'];
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
        $connection = config('database.region.'.$region.'.database_remote');
        $discounts = [];
        $errors = [];
        $status = true;
        $discounts ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            //dd($data);

            foreach ($discounts as $connection => $discount) {
                if ($discount) {
                    foreach ($discount->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $discount->$attr = $data[$attr];
                        }
                    }

                    $discount->region_id = $region;

                    if ($data['type'] == 1 && $data['percent']) {
                        $discount->percent = $data['percent'];
                        $discount->products_percent = null;
                    } elseif (($data['type'] == 2 || $data['type'] == 3) && $data['d_percents']) {
                        $products_percents = json_encode($data['d_percents']);
                        $discount->products_percent = $products_percents;
                        $discount->percent = 0;
                    } else {
                        $discount->products_percent = null;
                        $discount->percent = 0;
                    }

                    if ($id) {
                        $discount->id = $id;
                    }

                    if(!$discount->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $discount->id;
                    }
                } else { $errors [$connection]= 'something went wrong (could not save item on '.$connection.')'; $status = false; }
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

}