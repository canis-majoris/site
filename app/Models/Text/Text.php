<?php 
namespace App\Models\Text;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use App\Models\ImageHelper;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class Text extends Model  {

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
    protected $table = 'texts';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'texts_type_id', 'region_id', 'index', 'watch', 'language_id', 'created_at', 'updated_at', 'deleted_at',
        'order','name', 'name_trans', 'alt', 'img', 'short_text', 'text', 'seo_title', 'seo_keywords', 'seo_description', 'name2',  'source', 'tags', 'video', 'name2',];
    
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
        return $this->hasMany('App\Models\Text\TextLanguage', 'text_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Text\TextType', 'texts_type_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function related()
    {
        return Text::filterRegion(false)->where('ind', $this->ind)->select();
    }

    /**
     * @return [type]
     */
    public function getTranslatedByLanguageId($language_id, array $attributes) {
        return $this->translated()->where('language_id', $language_id)->select($attributes)->first();
    }

    public static function upload_img($data) {
        $data['directory'] = 'texts';
        return ImageHelper::upload($data, $data['id'], $data['size']);
    }

    public static function delete_img($data) {
        $data['directory'] = 'texts';
        return ImageHelper::delete($data, $data['id']);
    }

    public static function delete_with_img($id) {
        $text = Text::find($id);
        $path = 'img/texts/'.trim($text->img);
        if(file_exists($path)) {
            unlink($path);
        }
        $text->delete();
        return true;
    }

    public function manage_img($data) {
        if ($data['action'] == 'upload') {
            $fname = trim(Text::upload_img($data));
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            //if ($shared_img <= 1) {
                $fname = trim(Text::delete_img($data));
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
                $add_image_to_gallery = GalleryType::add_image('texts', $fname);
            } elseif ($data['action'] == 'delete') {
                $translated->img = null;
                $translated->save();
                $remove_image_from_gallery = GalleryType::remove_image('texts', $fname);
            }
        }

        return $fname;
    }

    public function manage_text_before_remove() {
        $translated = $this->translated()->get();
        foreach ($translated as $tr) {
            // if ($tr->img) {
            //     Text::delete_img(['id' => $tr->id, 'name' => $tr->img]);
            // }
            
            $tr->delete();
        }

        return ['status' => true, 'message' => null];
    }

    public function deleteHelper() {

        $text = [];
        $errors = [];
        $messages = [];
        $text ['local']= $this;
        
        foreach ($text as $k => $tt) {
            if ($tt) {
                $res = $tt->manage_text_before_remove();
                if ($res['status']) {
                    $messages [$tt->id]= 'Settings item deleted';
                    $tt->delete();
                } else $errors [$tt->id]= $res['message'];
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
        //$connection = config('database.region.'.$region.'.database_remote');
        $texts_types = [];
        $errors = [];
        $status = true;
        $texts_types ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($texts_types as $connection => $tt) {
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