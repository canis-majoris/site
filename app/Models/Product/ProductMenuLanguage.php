<?php 
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMenuLanguage extends Model  {

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
    protected $table = 'eshop_menus_languages';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'language_id', 'eshop_menu_id',  'name', 'name_trans', 'text', 'seo_title', 'seo_description', 'seo_keywords', 'img', 'alt', 'created_at', 'updated_at', 'deleted_at'];

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

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product');
    }

    public function get_item($id) {
        return $this->find($id);
    }

    protected function manage_product_menu_language_before_remove() {
        return ['status' => true, 'message' => null];
    }

    public function deleteHelper() {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $pmls = [];
        $errors = [];

        $pmls ['remote']= ProductMenu::on($connection)->find($this->id);
        $pmls ['local']= $this;
        
        foreach ($pmls as $k => $pml) {
            if ($pml) {
                $res = $pml->manage_product_menu_language_before_remove();
                if ($res['status']) {
                    $pml->delete();
                } else $errors [$pml->code]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">('.$reg.') '.$error.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str];
    }

    public function saveHelper($data) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
        $pmls = [];
        $errors = [];
        $status = true;
        $pmls ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $pmls ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $pmls ['remote']= $object;
            }*/

            $id = ($this->id ? $this->id : null);

            foreach ($pmls as $connection => $pml) {
                if ($pml) {
                    foreach ($pml->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $pml->$attr = $data[$attr];
                        }
                    }

                    $pml->region_id = $region;

                    if ($id) {
                        $pml->id = $id;
                    }

                    if(!$pml->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $pml->id;
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

    public static function rules ($id=0, $merge=[]) {
        return array_merge(
            [
                'name' => 'required|max:512',
                'name_trans' => 'max:512',
            ], 
            $merge);
    }

}