<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model  {

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
    protected $table = 'languages';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'status', 'name', 'language', 'language_code', 'created_at', 'updated_at', 'deleted_at'];

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
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
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function product() {
        
      return $this->belongsToMany('App\Models\Product\Product', 'languages_products');
    }

    public function products_translate() {

        return $this->belongsToMany('App\Models\Product\Product', 'products_languages', 'language_id', 'product_id')
            ->withPivot('ord', 'name', 'name_trans', 'text', 'short_text', 'seo_title', 'seo_description', 'seo_keywords', 'video_link', 'breadcrumb', 'articul', 'img', 'alt');
    }

    public function product_menu() {

      return $this->hasMany('App\Models\Product\ProductMenu');
    }

    public function map_points() {

      return $this->hasMany('App\Models\MapPoins', 'language_id');
    }

    public function manage_language_before_remove() {

        return ['status' => true, 'messages' => null];
    }

    public function deleteHelper() {


        $languages = [];
        $errors = [];
        $messages = [];
        $languages ['local']= $this;
        
        foreach ($languages as $k => $language) {
            if ($language) {
                $res = $language->manage_language_before_remove();
                if ($res['status']) {
                    $messages [$language->id]= 'Language deleted';
                    $language->delete();
                } else $errors [$language->id]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">('.$reg.') '.$error.'</div>';
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
        $languages = [];
        $errors = [];
        $status = true;
        $languages ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($languages as $connection => $language) {
                if ($language) {
                    foreach ($language->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $language->$attr = $data[$attr];
                        }
                    }

                    $language->region_id = $region;

                    if ($language->language_code) {
                        $language->name = $language->language_code;
                    }

                    if ($id) {
                        $language->id = $id;
                    }

                    if(!$language->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $language->id;
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

    public static function rules ($id=null, $merge=[]) {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database');
        return array_merge(
            [
                'language' => 'required|max:100|unique:languages,language,' . $id . ',id,region_id,' . $region,
                'language_code' => 'required|max:5|unique:languages,language_code,' . $id . ',id,region_id,' . $region,
            ], 
            $merge);
    }
    

}