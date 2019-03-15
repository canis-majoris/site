<?php 
namespace App\Models\Promo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model  {

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
    protected $table = 'promo';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'code', 'type', 'date_create', 'date_start', 'date_end', 'date_used', 'limit1', 'percent', 'products', 'products_percent', 'status', 'type_discount', 'dealer_perc_status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function promos_used()
    {
        return $this->hasMany('App\Models\Promo\PromoUsed', 'promo_id');
    }

    public function log()
    {
        return $this->hasMany('App\Models\Promo\PromoUsed', 'promo_id');
    }

    public function manage_promo_before_remove() {

        // $this->users()->detach();
        // $this->dealers()->detach();

        return ['status' => true, 'message' => null];
    }
    
    public function deleteHelper() {

        $promos = [];
        $errors = [];
        $messages = [];
        $promos ['local']= $this;
        
        foreach ($promos as $k => $promo) {

            if ($promo) {
                //$res = $product->check_product_before_remove();
                //if ($res['status']) {
                    $res = $promo->manage_promo_before_remove();
                    if ($res['status']) {
                        $messages [$promo->code]= 'Promo <b>'.$promo->code.'</b> deleted';
                        $promo->delete();
                    } else $errors [$promo->code]= $res['message'];
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
        $promos = [];
        $errors = [];
        $status = true;
        $promos ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            //dd($data);

            foreach ($promos as $connection => $promo) {
                if ($promo) {
                    foreach ($promo->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $promo->$attr = $data[$attr];
                        }
                    }

                    $promo->region_id = $region;

                    if ($data['type'] == 1 && $data['percent']) {
                        $promo->percent = $data['percent'];
                        $promo->products_percent = null;
                    } elseif (($data['type'] == 2 || $data['type'] == 3) && $data['d_percents']) {
                        $products_percents = json_encode($data['d_percents']);
                        $promo->products_percent = $products_percents;
                        $promo->percent = 0;
                    } else {
                        $promo->products_percent = null;
                        $promo->percent = 0;
                    }

                    if ($id) {
                        $promo->id = $id;
                    }

                    if(!$promo->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $promo->id;
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