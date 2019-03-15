<?php 
namespace App\Models\Page;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use App\Helpers\PageHelper;
use App\Models\ImageHelper;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class Page extends Model  {

    use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        //$this->connection = config('database.region.'.$this->region.'.database');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'index', 'root', 'status', 'locked', 'is_text', 'tags', 'created_at', 'updated_at', 'deleted_at',
        'order','controller', 'method', 'param', 'is_menu','target', 'url', 'name', 'name_trans','alt', 'img', 'short_text', 'text'];
    
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
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */

    public function translated() 
    {
        return $this->hasMany('App\Models\Page\PageLanguage', 'page_id');
    }

    // public function type()
    // {
    //     return $this->belongsTo('App\Models\Text\TextType', 'texts_type_id');
    // }

    public function children(){
        return $this->belongsToMany('App\Models\Page\Page', 'pages_menus', 'page_id', 'next_page_id')->withPivot('page_id', 'next_page_id', 'status', 'order', 'created_at');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function related()
    {
        return Page::filterRegion()->where('index', $this->ind)->select();
    }

    /**
     * @return [type]
     */
    public function getTranslatedByLanguageId($language_id, array $attributes) {
        return $this->translated()->where('language_id', $language_id)->select($attributes)->first();
    }

    public static function upload_img($data) {
        $data['directory'] = 'pages';
        return ImageHelper::upload($data, $data['id'], $data['size']);
    }

    public static function delete_img($data) {
        $data['directory'] = 'pages';
        return ImageHelper::delete($data, $data['id']);
    }

    public static function recursiveNestableListUpdate($list, $parent = null, $order = 1, $root = 0) {
        foreach ($list as $menu_item) {
            $item_id = $menu_item['id'];
            $item = Page::filterRegion()->find($item_id);

            if ($item) {
                $item->order = $order;
                $item->root = $root;
                $item->save();
                $order++;
                
                $children = ( isset($menu_item['children']) && !empty($menu_item['children']) ) ? $menu_item['children'] : [];

                $children_arr = [];

                // foreach ($children as $c_arr) {
                //     $children_arr [$c_arr['id']] = ['region_id' => $item->region_id];
                // }

                $item->children()->sync($children_arr);

                //dd($children_arr);

                if (count($children) > 0) {
                    $root++;
                    Page::recursiveNestableListUpdate($children, $item, $order, $root);
                    $root--;
                }
            }
        }

        return true;
    }

    public function ancestors_recursive_delete() {
        $children = $this->children()->get();

        foreach ($children as $child) {

            $child->ancestors_recursive_delete();

            $child->children()->sync([]);

            $translated = $child->translated()->get();
            foreach ($translated as $tr) {
                $tr->delete();
            }

            $child->delete();
        }

        return true;
    }

    public static function getNestableList($data) {
        $menu = Page::filterRegion()->where('root', 0)->orderBy('order')->get();
        $html = null;
        $language_id = $data['language_id'];

        $html = PageHelper::generate_menu($menu, $language_id, $data);

        return $html;
    }

    public static function updateNestableList($data) {
        $list = $data['list'];
        $html = null;

        $result = Page::recursiveNestableListUpdate($list);

        if ($result) {
            $html = Page::getNestableList($data);
        }

        return $html;
    }

    public function recursiveNestableListSelect($list, $parent = null) {
        foreach ($list as $item) {
            $item_id = $menu_item['id'];
            $menu_item = Page::filterRegion()->find($item_id);
            if ($menu_item) {
                $children = $menu_item->children()->get();
            }
            if (isset($menu_item['children'])) {
                foreach ($menu_item['children'] as $menu_item_c) {
                    $item_c = $menu_item_c['id'];
                }
            }
        }
    }

    public static function delete_with_img($id) {
        $page = Page::find($id);
        $path = 'img/pages/'.trim($page->img);
        if(file_exists($path)) {
            unlink($path);
        }
        $page->delete();
        return true;
    }

    public function manage_img($data) {
        if ($data['action'] == 'upload') {
            $fname = trim(Page::upload_img($data));
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            //if ($shared_img <= 1) {
                $fname = trim(Page::delete_img($data));
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
                $add_image_to_gallery = GalleryType::add_image('pages', $fname);
            } elseif ($data['action'] == 'delete') {
                $translated->img = null;
                $translated->save();
                $remove_image_from_gallery = GalleryType::remove_image('pages', $fname);
            }
        }

        return $fname;
    }

    public function manage_page_before_remove() {
        $translated = $this->translated()->get();
        
        foreach ($translated as $tr) {
            $tr->delete();
        }

        $ancestors_recursive_delete = $this->ancestors_recursive_delete();

        $this->children()->sync([]);

        return ['status' => true];
    }

    public function deleteHelper() {
        $page = [];
        $errors = [];
        $page ['local']= $this;
        
        foreach ($page as $k => $tt) {
            if ($tt) {
                $res = $tt->manage_page_before_remove();
                if ($res['status']) {
                    $tt->delete();
                } else $errors [$tt->id]= $res['message'];
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
        $pages_types = [];
        $errors = [];
        $status = true;
        $pages_types ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($pages_types as $connection => $tt) {
                if ($tt) {
                    foreach ($tt->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $tt->$attr = $data[$attr];
                        }
                    }

                    $tt->region_id = $region;

                    if ($id) {
                        $tt->id = $id;
                    }

                    if(!$tt->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $tt->id;
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