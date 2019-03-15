<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use File;

class GalleryType extends Model
{
    //use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;
    protected $table = 'gallery_types';
    protected $fillable = ['id', 'title', 'region_id', 'language_id', 'image', 'description', 'status', 'type', 'created_at', 'updated_at', 'deleted_at'];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
    }

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

    public function content()
    {
        return $this->hasMany('App\Models\Gallery\Gallery', 'gallery_type_id');
    }

    public static function add_image($type_name, $fname) {
        $gallery_type = GalleryType::filterRegion(false)->where('title', $type_name)->first();
        $image = new Gallery;
        $image->title = $fname;
        $image->img = $fname;
        $image->status = 1;
        return $gallery_type->content()->save($image);
    }

    public static function remove_image($type_name, $fname) {
        $gallery_type = GalleryType::filterRegion(false)->where('title', $type_name)->first();
        $image = $gallery_type->content()->where('title', $fname)->orWhere('img', $fname)->first();
        if ($image) {
            $image->delete();
        }

        return true;
    }

    protected function manage_gallery_types_before_remove() {
        $content = $this->content()->get();

        foreach ($content as $s) {
            if ($s->img) {
                Gallery::delete_img(['id' => $s->id, 'name' => $s->img]);
            }

            $s->delete();
        }

        // $path = 'img/' . $this->title;
        // if (File::exists($path)) {
        //     $delete_directory_result = File::deleteDirectory($path);
        // }

        return ['status' => true];
    }

    public function deleteHelper() {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $gallery_types = [];
        $errors = [];

        $gallery_types ['local']= $this;
  
        foreach ($gallery_types as $k => $tt) {
            if ($tt) {
                $res = $tt->manage_gallery_types_before_remove();
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

    public function saveHelper($data, $new = false) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
        $gallery_types = [];
        $errors = [];
        $status = true;
        $gallery_types ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);

            foreach ($gallery_types as $connection => $tt) {
                if ($tt) {

                    $old_path = 'img/' . $tt->title;

                    foreach ($tt->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $tt->$attr = $data[$attr];
                        }
                    }

                    $new_path = 'img/' . $tt->title;

                    $tt->region_id = $region;

                    if ($id) {
                        $tt->id = $id;
                    }

                    if(!$tt->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $tt->id;
                        if ($new && $id) {
                            if (!File::exists($new_path)) {
                                $create_directory_result = File::makeDirectory($new_path, 0775);
                            }
                        } elseif ($id) {
                            if (File::exists($old_path) && $old_path != $new_path) {
                                File::copyDirectory($old_path, $new_path);
                                File::deleteDirectory($old_path);
                            }
                        }
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
                'title' => 'required|min:2|max:100|regex:/^[-\pL\pN_.@]++$/uD|unique:gallery_types,title,' . $id . ',id,region_id,'.$region,
                'description' => 'max:1024',
            ], 
            $merge);
    }
}
