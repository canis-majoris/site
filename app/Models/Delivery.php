<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use App\Models\ImageHelper;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class Delivery extends Model  {

   // use SoftDeletes;
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
    protected $table = 'delivery';
    //protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'name', 'img', 'comment', 'price', 'hide', 'created_at', 'updated_at', 'deleted_at'];

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
    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

     public static function manage_img($data) {
        if ($data['action'] == 'upload') {
            $fname = trim(Delivery::upload_img($data));
            $add_image_to_gallery = GalleryType::add_image('delivery', $fname);
            $message = 'image uploaded';
        } elseif ($data['action'] == 'delete') {
            $fname = trim(Delivery::delete_img($data));
            $remove_image_from_gallery = GalleryType::remove_image('delivery', $fname);
            $message = 'image deleted';
        }

        $id = (isset($data['id'])) ? $data['id'] : null;

        if ($id && $delivery = Delivery::filterRegion()->find($id)) {
            if ($data['action'] == 'upload') {
                $img = json_encode([$fname]);
                $delivery->img = $img;
                $delivery->save();
                
            } elseif ($data['action'] == 'delete') {
                $delivery->img = null;
                $delivery->save();
            }
        }
        //var_dump($this->img); die;

        return $fname;
    }

    public function check_delivery_before_remove() {
        return ['status' => true, 'message' => ''];
    }

    public function deleteHelper() {

        $delivery = [];
        $errors = [];
        $messages = [];
        $delivery ['local']= $this;
        
        foreach ($delivery as $k => $d) {
            if ($d) {
                $res = $d->check_delivery_before_remove();
                if ($res['status']) {
                    $messages [$d->code]= 'Delivery item <b>'.$d->code.'</b> deleted' . $res['message'];

                    $d->delete();
                } else $errors [$d->code]= $res['message'];
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
        $connection = config('database.region.'.$region.'.database_remote');
        $delivery = [];
        $errors = [];
        $status = true;
        $delivery ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($delivery as $connection => $d) {
                if ($d) {
                    foreach ($d->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $d->$attr = $data[$attr];
                        }
                    }

                    $d->region_id = $region;

                    if ($id) {
                        $d->id = $id;
                    }

                    if(!$d->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $d->id;
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
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database');
        return array_merge(
            [
                'name' => 'required|max:100',
                'price' => 'required|max:11',
                'comment' => 'max:512',
            ], 
            $merge);
    }
    

    public static function upload_img($data, $id = null) {
        $data['directory'] = 'delivery';
        return ImageHelper::upload($data, $id, $data['size']);
    }

    public static function delete_img($data, $id = null)
    {
        $region = session('region_id') ? session('region_id') : 1;
        $data['directory'] = 'delivery';
        
        if ($id) {
            $delivery = Delivery::where('region_id', $region)->find($id);
            $delivery->img = null;
            $delivery->save();
        }

        return ImageHelper::delete($data, $id);
    }

    /*public static function delete_with_img($id) {
        $product = Product::find($id);
        $path = 'img/delivery/'.trim($product->img);
        if(file_exists($path)) {
            unlink($path);
        }
        $product->delete();
        return true;
    }*/

}