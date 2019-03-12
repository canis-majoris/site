<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageTranslated extends Model
{

	use SoftDeletes;

    protected $table = 'pages_translated';

    public $timestamps = true;

    protected $fillable = ['id', 'page_id', 'language_id', 'name', 'status', 'text', 'short_text', 'seo_title', 'seo_description', 'seo_keywords', 'tags', 'img', 'alt', 'created_at', 'updated_at', 'deleted_at'];

    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }

    public function get_item($id) {
        return $this->find($id);
    }

    public function siblings() {
        return $this->page()->first()->translated()->where('language_id', '!=', $this->language_id);
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
