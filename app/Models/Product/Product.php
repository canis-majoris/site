<?php 
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use App\Models\ImageHelper;
use App\Models\Product\ProductMenu;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use App\Models\Order\OrderProduct;
use Illuminate\Http\Request;

class Product extends Model  {

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
	protected $table = 'products';
	protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'language_id', 'ind', 'flag_id', 'watch', 'top', 'score', 'votes', 'rating', 'ord', 'price', 'price_old', 'sum_x', 'sum_y', 'date', 'name', 'name_trans', 'text', 'short_text', 'seo_title', 'seo_description', 'seo_keywords', 'video_link', 'breadcrumb', 'user_bonus',  'new', 'is_in_store', 'articul', 'img', 'alt', 'for_mobile', 'width',  'height', 'weight', 'sale', 'is_service', 'days', 'date_start', 'is_p', 'yearly', 'discount', 'paket', 'ru_name', 'cz_name', 'en_name', 'ru_text', 'cz_text', 'en_text', 'ru_seo_title', 'cz_seo_title', 'en_seo_title', 'ru_seo_keywords', 'cz_seo_keywords', 'en_seo_keywords', 'ru_seo_description', 'cz_seo_description', 'per_month_count', 'en_seo_description', 'is_com', 'visible_on_site', 'is_goods', 'created_at', 'updated_at', 'deleted_at'];

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
	public function language() 
	{
		return $this->belongsToMany('App\Models\Language', 'languages_products');
	}

    public function translated() 
    {
        return $this->hasMany('App\Models\Product\ProductLanguage', 'product_id');
    }

	public function type() 
	{
		return $this->belongsToMany('App\Models\Product\ProductMenu', 'eshop_menus_products', 'product_id', 'eshop_menu_id');
	}

    /**
     * @return [type]
     */
    public function getTranslatedByLanguageId($language_id, array $attributes) {
        return $this->translated()->where('language_id', $language_id)->select($attributes)->first();
    }

	public function menus_products()
    {
        return $this->hasMany('App\Models\Product\ProductMenuItem');
    }

    public static function upload_img($data) {
        $data['directory'] = 'products';
        return ImageHelper::upload($data, $data['id'], $data['size']);
    }

    public static function delete_img($data) {
        $data['directory'] = 'products';
        return ImageHelper::delete($data, $data['id']);
    }

    public static function delete_with_img($id) {
    	$product = Product::find($id);
    	$path = 'img/products/'.trim($product->img);
    	if(file_exists($path)) {
            unlink($path);
        }
        $product->delete();
        return true;
    }

    public function manage_img($data) {
        if ($data['action'] == 'upload') {
            $fname = trim(Product::upload_img($data));
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            //if ($shared_img <= 1) {
                $fname = trim(Product::delete_img($data));
            //}
            $message = 'image deleted';
        }

        $translated = $this->translated()->where('language_id', $data['language_id'])->first();
        // $siblings = $translated->siblings()->get();

        if ($translated) {
            if ($data['action'] == 'upload') {
                $img = json_encode([$fname]);
                $siblings = $translated->siblings()->withNoImage()->get();

                foreach ($siblings as $sibling) {
                    $sibling->img = $img;
                    $sibling->save();
                }

                $translated->img = $img;
                $translated->save();
                $add_image_to_gallery = GalleryType::add_image('products', $fname);
            } elseif ($data['action'] == 'delete') {
                $translated->img = null;
                $translated->save();
                $remove_image_from_gallery = GalleryType::remove_image('products', $fname);
            }
        }

        return $fname;
    }

    public static function get_stbs() {
        $region = session('region_id') ? session('region_id') : 1;
        return Product::where('region_id', $region)->where('is_p', 1)->where('for_mobile', 0)->where('is_goods', 0)->select();
    }

    public static function get_services() {
        $region = session('region_id') ? session('region_id') : 1;
        return Product::where('region_id', $region)->where('is_service', 1)->where('for_mobile', 0)->where('is_goods', 0)->select();
    }

    public static function get_mobile_services() {
        $region = session('region_id') ? session('region_id') : 1;
        return Product::where('region_id', $region)->where('is_service', 1)->where('for_mobile', 1)->where('is_goods', 0)->select();
    }

    public static function get_all_services() {
        $region = session('region_id') ? session('region_id') : 1;
        return Product::where('region_id', $region)->where('is_service', 1)->where('is_goods', 0)->select();
    }

    public static function get_goods() {
        $region = session('region_id') ? session('region_id') : 1;
        return Product::where('region_id', $region)->where('is_goods', 1)->select();
    }

    public function active_services_for_product($by_region = true) {
        return OrderProduct::filterRegion($by_region)->where('product_id', $this->id)->whereIn('products_statuse_id', [5, 6]);
    }

    public function active_mobile_services_for_product($by_region = true) {
        return OrderProduct::filterRegion($by_region)->where('product_id', $this->id)->whereIn('products_statuse_id', [5, 6]);
    }

    public function active_stb_for_product($by_region = true) {
        return OrderProduct::filterRegion($by_region)->where('product_id', $this->id)->whereHas('get_stb_service', function($query) {}, '>', 0)->where('products_statuse_id', 1);
    }

    protected function manage_product_before_remove() {
        $menu = $this->type()->first();
        if ($menu) {
            $menu->items()->detach($this->id);
        }

        $translated = $this->translated()->get();
        foreach ($translated as $tr) {
            // if ($tr->img) {
            //     Text::delete_img(['id' => $tr->id, 'name' => $tr->img]);
            // }
            
            $tr->delete();
        }

        return ['status' => true, 'message' => null];
    }

    public function check_product_before_remove() {

        $activated_count_for_service = 0;
        $activated_count_for_mobile_service = 0;
        $activated_count_for_stb = 0;

        $message = '';
        $status = true;

        if ($this->is_service) {
            if (!$this->for_mobile) {
                $activated_count_for_service = $this->active_services_for_product()->count();
                if ($activated_count_for_service > 0) {
                    $message = '<ul class="error_log-alert-1"><li>Product can not be deleted as it has ' . $activated_count_for_service . ' active services</li></ul>';
                    $status = false;
                }

            } else {
                $activated_count_for_mobile_service = $this->active_mobile_services_for_product()->count();
                if ($activated_count_for_mobile_service > 0) {
                    $message = '<ul class="error_log-alert-1"><li>Product can not be deleted as it has ' . $activated_count_for_mobile_service . ' active mobile services</li></ul>';
                    $status = false;
                }
                
            }
        } elseif ($this->is_p && !$this->is_goods) {
            $activated_count_for_stb = $this->active_stb_for_product()->count();
            if ($activated_count_for_stb) {
                $message = '<ul class="error_log-alert-1"><li>Product can not be deleted as it has ' . $activated_count_for_stb . ' active STBs</li></ul>';
                $status = false;
            }
            
        }

        return ['status' => $status, 'message' => $message];

    }

    public function deleteHelper() {

        $products = [];
        $errors = [];
        $messages = [];
        $products ['local']= $this;
        
        foreach ($products as $k => $product) {
            if ($product) {
                $res = $product->check_product_before_remove();
                if ($res['status']) {
                    $res = $product->manage_product_before_remove();
                    if ($res['status']) {
                        $messages [$product->code]= 'Product '.$product->code.' deleted';
                        $product->delete();
                    }
                } else $errors [$product->code]= $res['message'];
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

    public function saveHelper($data, $menu = null) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
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

                    $product->region_id = $region;

                    if($product->save()) {
                        if ($menu) {
                            $menu->products()->attach($product->id);
                        }
                         
                    } else {
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; break;
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

    public static function rules ($id = 0, $merge = []) {
        return array_merge([
            'name' => 'required|min:2|max:255',
            'short_text' => 'min:1|max:255',
            'price' => 'required',

        ], $merge);
    }

}