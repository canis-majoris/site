<?php 
namespace App\Models\Text;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextLanguage extends Model  {

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
    protected $table = 'texts_languages';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'language_id', 'text_id', 'name', 'name_trans', 'watch', 'text', 'short_text', 'seo_title', 'seo_description', 'seo_keywords', 'video', 'source', 'img', 'alt', 'created_at', 'updated_at', 'deleted_at'];

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }

    public function scopeWithNoImage($query, $flag = true) {
        if ($flag) {
            return $query->where( function($query) {
                $query->where('img', '')->orWhereNull('img')->orWhere('img', '[]');
            });
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

    public function text()
    {
        return $this->belongsTo('App\Models\Text\Text', 'text_id', 'id');
    }

    public function get_item($id) {
        return $this->find($id);
    }

    public function siblings() {
        return $this->text()->first()->translated()->where('language_id', '!=', $this->language_id);
    }

    protected function manage_text_language_before_remove() {
        return ['status' => true, 'message' => null];
    }

    public function saveHelper($data) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
        $pls = [];
        $errors = [];
        $status = true;
        $pls ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $pls ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $pls ['remote']= $object;
            }*/

            $id = ($this->id ? $this->id : null);

            foreach ($pls as $connection => $pl) {
                if ($pl) {
                    foreach ($pl->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $pl->$attr = $data[$attr];
                        }
                    }

                    $pl->region_id = $region;

                    if ($id) {
                        $pl->id = $id;
                    }

                    if(!$pl->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $pl->id;
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