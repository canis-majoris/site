<?php 
namespace App\Models\Text;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextType extends Model  {

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
    protected $table = 'texts_types';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'watch', 'comments', 'voting', 'name', 'title', 'icon', 'updated_at', 'deleted_at', 'created_at',
        'description','region_id', ];
    
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
    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Text\Text', 'texts_type_id');
    }

    protected function manage_texts_types_before_remove() {
        $texts = $this->items()->get();

        foreach ($texts as $t) {
            $translated = $t->translated()->get();
            foreach ($translated as $t_t) {
                if ($t_t->img) {
                    Text::delete_img(['id' => $t_t->id, 'name' => $t_t->img]);
                }

                $t_t->delete();
            }
            
            $t->delete();
        }

        return ['status' => true, 'message' => null];
    }

    public function deleteHelper() {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $texts_types = [];
        $errors = [];

        $texts_types ['local']= $this;
        
        foreach ($texts_types as $k => $tt) {
            if ($tt) {
                $res = $tt->manage_texts_types_before_remove();
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
        $texts_types = [];
        $errors = [];
        $status = true;
        $texts_types ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $menus ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $menus ['remote']= $object;
            }*/

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
                'name' => 'required|max:512|min:2',
                'name_trans' => 'max:512',
            ], 
            $merge);
    }

}