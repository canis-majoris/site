<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use Illuminate\Http\Request;

class ServiceAccount extends Model  {

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
	protected $table = 'services_accounts';
	protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'ref_account_id', 'ref_profile_id', 'ref_account_name', 'user_id', 'dispaly_name', 'username', 'password', 'pin', 'ref_service_id', 'status', 'service_status', 'qcmnd_id', 'msgcode', 'date', 'created_at', 'updated_at', 'deleted_at'];

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
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

    public function deleteHelper() {
        /*$region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');*/
        $products = [];
        $errors = [];

        //$products ['remote']= Product::on($connection)->find($this->id);
        $products ['local']= $this;
        
        foreach ($products as $k => $product) {
            if ($product) {
                $product->delete();
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">('.$reg.') '.$error.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str];
    }

    public function saveHelper($data, $menu_id = null) {
        /*$region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');*/
        $products = [];
        $errors = [];
        $status = true;
        $products ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $products ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $products ['remote'] = $object;
            }*/

            //$id = ($this->id ? $this->id : null);

            foreach ($products as $connection => $product) {
                if ($product) {
                    foreach ($product->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $product->$attr = $data[$attr];
                        }
                    }

                    if ($menu_id) {
                        if ($menu = Menu::find($menu_id)) {
                            $menu->products()->attach($product->id);
                        }
                    }

                    if(!$product->save()) {
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; break; 
                    } else {
                        //$id = $product->id;
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