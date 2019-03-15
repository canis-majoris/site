<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseModel extends Model
{
    public function saveHelper($data, $menu = null) {
        $region = session('region_id') ? session('region_id') : 1;
        //$connection = config('database.region.'.$region.'.database_remote');
        $products = [];
        $errors = [];
        $status = true;
        $products ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $products ['remote']= Product::on($connection)->find($this->id);
            } else {
                $object = new Product;
                $object->setConnection($connection);
                $products ['remote'] = $object;
            }*/

            //$id = ($this->id ? $this->id : null);

            foreach ($products as $connection => $product) {
                if ($product) {
                    foreach ($product->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $product->$attr = $data[$attr];
                        }
                    }

                    $product->region_id = $region;

                    if($product->save()) {
                        if ($menu) {
                            $menu->products()->attach($product->id);
                        }
                         
                    } else {
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; break;
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