<?php 
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStatusLog extends Model  {

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
    protected $table = 'products_statuses_logs';
    //protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'region_id', 'orders_product_id', 'products_statuse_id', 'products_statuse_id', 'date', 'owner', 'status', 'created_at', 'updated_at', 'deleted_at'];

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

    public function product_status()
    {
        return $this->belongsTo('App\Models\Product\ProductStatus', 'products_statuse_id');
    }

    public function orders_product()
    {
        return $this->belongsTo('App\Models\Order\OrderProduct', 'orders_product_id');
    }


    public function deleteHelper() {
        $logs = [];
        $errors = [];
        $messages = [];
        $logs ['local']= $this;
        
        foreach ($logs as $k => $log) {
            if ($log) {
                $name = $log->comment;
                if (!$log->delete()) {
                    $errors [$name]= $res['message'];
                } else $messages [$name]= 'Product log deleted';
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

    /**
     * Returns object using given $index
     * 
     * @return objects
     * @access public
     */

}