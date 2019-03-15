<?php 
namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ImageHelper;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class Settings extends Model  {

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
    protected $table = 'settings';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'ind', 'status', 'created_at', 'updated_at', 'deleted_at'];

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
        return $this->hasMany('App\Models\Settings\SettingsLanguage', 'settings_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Settings\SettingsType', 'settings_type_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }

    public function related()
    {
        return Settings::filterRegion(false)->where('ind', $this->ind)->select();
    }

    /**
     * @return [type]
     */
    public function getTranslatedByLanguageId($language_id, array $attributes) {
        return $this->translated()->where('language_id', $language_id)->select($attributes)->first();
    }

    public static function upload_img($data) {
        $data['directory'] = 'settings';
        return ImageHelper::upload($data, $data['id'], $data['size']);
    }

    public static function delete_img($data) {
        $data['directory'] = 'settings';
        return ImageHelper::delete($data, $data['id']);
    }

    public static function delete_with_img($id) {
        $text = Settings::find($id);
        $path = 'img/settings/'.trim($text->img);
        if(file_exists($path)) {
            unlink($path);
        }
        $text->delete();
        return true;
    }

    public function manage_img($data) {

        //$translated = $this->translated()->where('language_id', $data['language_id'])->first();
        $shared_img = $this->translated()->where('share_img', 1)->count();
        $fname = null;
        $share_img = (isset($data["share_img"]) && $data["share_img"] == "on") ? 1 : 0;
        if ($data['action'] == 'upload') {
            $fname = trim(Settings::upload_img($data));
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            //if ($shared_img <= 1) {
                $fname = trim(Settings::delete_img($data));
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
                $add_image_to_gallery = GalleryType::add_image('settings', $fname);
            } elseif ($data['action'] == 'delete') {
                $translated->img = null;
                $translated->save();
                $remove_image_from_gallery = GalleryType::remove_image('settings', $fname);
            }
        }

        return $fname;
    }

    public function manage_settings_before_remove() {
        $translated = $this->translated()->get();
        foreach ($translated as $tr) {

            // if ($tr->img) {
            //     Settings::delete_img(['id' => $tr->id, 'name' => $tr->img]);
            // }
            
            $tr->delete();
        }

        return ['status' => true];
    }

    public function deleteHelper() {

        $settings = [];
        $errors = [];
        $messages = [];
        $settings ['local']= $this;
        
        foreach ($settings as $k => $settings) {
            if ($settings) {
                $res = $settings->manage_settings_before_remove();
                if ($res['status']) {
                    $messages [$settings->id]= 'Settings item deleted';
                    $settings->delete();
                } else $errors [$settings->id]= $res['message'];
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
        
        $settings = [];
        $errors = [];
        $status = true;
        $settings ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($settings as $connection => $s) {
                if ($s) {
                    foreach ($s->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $s->$attr = $data[$attr];
                        }
                    }

                    $s->region_id = $region;

                    if ($id) {
                        $s->id = $id;
                    }

                    if(!$s->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $s->id;
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
            'value' => 'min:1|max:255',
        ], $merge);
    }
}