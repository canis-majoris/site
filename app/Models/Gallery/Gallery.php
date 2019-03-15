<?php

namespace App\Models\Gallery;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use App\Models\ImageHelper;
use App\Models\Product\ProductLanguage;
use App\Models\Settings\SettingsLanguage;
use App\Models\Text\TextLanguage;
use App\Models\Delivery;

class Gallery extends Model
{
	//use SoftDeletes;
    protected $table = 'gallery';
    protected $fillable = ['id', 'title', 'region_id', 'language_id', 'image', 'description', 'status', 'type', 'created_at', 'updated_at', 'deleted_at'];

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

    public function type()
    {
        return $this->belongsTo('App\Models\Gallery\GalleryType', 'gallery_type_id');
    }

    public static function upload_img($data) {
        return ImageHelper::upload($data, $data['id'], $data['size']);
    }

    public static function delete_img($data) {
        return ImageHelper::delete($data, $data['id']);
    }

    public static function delete_with_img($id) {
        $text = Gallery::find($id);
        $path = 'img/gallery/'.trim($text->img);
        if(file_exists($path)) {
            unlink($path);
        }
        $text->delete();
        return true;
    }

    public function manage_img($data) {
        //var_dump($data); die;
        $fname = null;
        if ($data['action'] == 'upload') {
            $fname = trim(Gallery::upload_img($data));
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            $gallery_item = Gallery::find($data['id']);

            if ($gallery_item) {
                $gallery_item->manage_gallery_before_remove();
            }
            
            $message = 'image deleted';
        }

        if ($data['id']) {
            $gallery = Gallery::find($data['id']);
            if ($data['action'] == 'upload') {
                $gallery->img = $fname;
                $gallery->save();
            } elseif ($data['action'] == 'delete') {
                $gallery->img = null;
                $gallery->save();
            }
        }

        return $fname;
    }

    public function manage_gallery_before_remove() {
    	
        if ($this->img) {
            $process_arr = [
                'products' => ProductLanguage::all(),
                'settings' => SettingsLanguage::all(),
                'pages' => TextLanguage::all(),
                'delivery' => Delivery::all(),
            ];

            foreach ($process_arr as $p_a) {
                foreach ($p_a as $item) {
                    if ($item->img) {
                        $images = json_decode($item->img, true);

                        if (json_last_error() === 0) {
                             if (($key = array_search($this->img, $images)) !== false) {
                                unset($images[$key]);
                            }

                            if (count($images) > 0) {
                                $images = json_encode($images);
                            } else $images = null;

                            $item->img = $images;
                            $item->save();
                        }
                    }
                }
            }

            Gallery::delete_img(['id' => $this->id, 'name' => $this->img]);
        }

        return ['status' => true];
    }

    public function deleteHelper() {
        $gallery = [];
        $errors = [];
        $gallery ['local']= $this;
        
        foreach ($gallery as $connection => $s) {
            if ($s) {
                $res = $s->manage_gallery_before_remove();
                if ($res['status']) {
                    $s->delete();
                } else $errors [$s->id]= $res['message'];
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
        
        $gallery = [];
        $errors = [];
        $status = true;
        $gallery ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($gallery as $connection => $s) {
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
}
