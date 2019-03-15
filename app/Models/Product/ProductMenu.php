<?php 
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductMenu extends Model  {

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
    protected $table = 'eshop_menus';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'ind', 'img', 'watch', 'ord', 'is_group', 'breadcrumb', 'created_at', 'updated_at', 'deleted_at'];

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

    public function items()
    {
        return $this->belongsToMany('App\Models\Product\Product', 'eshop_menus_products', 'eshop_menu_id', 'product_id');
    }

    public function translated() 
    {
        return $this->hasMany('App\Models\Product\ProductMenuLanguage', 'eshop_menu_id');
    }

    public function get_item($id) {
        return $this->find($id);
    }

    public function menus_products()
    {
        return $this->hasMany('App\Models\Product\ProductMenuItem', 'eshop_menu_id');
    }

    /**
     * Returns object using given $index
     * 
     * @return objects
     * @access public
     */
    public function get_item_lang($index, $language_id) {
        return $this->where('index', $index)->where('language_id', $language_id)->get();
    }

    protected function manage_menu_before_remove() {
        $products = $this->items()->get();

        foreach ($products as $p) {
            $translated = $p->translated()->get();
            foreach ($translated as $p_t) {
                if ($p_t->img) {
                    Product::delete_img(['id' => $p_t->id, 'name' => $p_t->img]);
                }

                $p_t->delete();
            }
            
            $p->delete();
        }

        $translated = $this->translated()->get();
        
        foreach ($translated as $tr) {

            $tr->delete();
        }

        return ['status' => true, 'message' => null];
    }

    public function deleteHelper() {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $menus = [];
        $errors = [];

        $menus ['remote']= ProductMenu::on($connection)->find($this->id);
        $menus ['local']= $this;
        
        foreach ($menus as $k => $menu) {
            if ($menu) {
                $res = $menu->manage_menu_before_remove();
                if ($res['status']) {
                    $menu->delete();
                } else $errors [$menu->code]= $res['message'];
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
        $menus = [];
        $errors = [];
        $status = true;
        $menus ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $menus ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $menus ['remote']= $object;
            }*/

            $id = ($this->id ? $this->id : null);

            foreach ($menus as $connection => $menu) {
                if ($menu) {
                    foreach ($menu->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $menu->$attr = $data[$attr];
                        }
                    }

                    $menu->region_id = $region;

                    if ($id) {
                        $menu->id = $id;
                    }

                    if(!$menu->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $menu->id;
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
                'name' => 'required|max:512|min:2',
                'name_trans' => 'max:512',
            ], 
            $merge);
    }

}