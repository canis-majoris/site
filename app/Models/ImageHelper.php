<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Image;
use File;
class ImageHelper {

    public static function upload($data, $id = null, $size) {
        if (isset($data['directory'])) {
            $quality = (isset($data['quelity']) ? $data['quelity'] : 100);
            $img = Image::make($data['image']);
            if ($data['image_crop_coordinates'] != "null") {
                $image_crd = json_decode($data['image_crop_coordinates'], true);
                $img->crop((int)$image_crd['width'], (int)$image_crd['height'], (int)$image_crd['x'], (int)$image_crd['y']);
            } else {
                ///GIF CREATION
            }

            $dimensions = ['w' => $img->width(), 'h' => $img->height()];

            $set_dimensions = [];
            switch ($size) {
                case 'icon': $set_dimensions = ['m_w' => 160, 'm_h' => 160]; break;
                case 'small': $set_dimensions = ['m_w' => 640, 'm_h' => 480]; break;
                case 'medium': $set_dimensions = ['m_w' => 1280, 'm_h' => 720]; break;
                case 'large': $set_dimensions = ['m_w' => 1920, 'm_h' => 1080]; break;
                case 'extralarge': $set_dimensions = ['m_w' => 3840, 'm_h' => 2160]; break;
                default: $set_dimensions = ['m_w' => 640, 'm_h' => 480];
            }

            if ($dimensions['w'] >= $set_dimensions['m_w'] || $dimensions['h'] >= $set_dimensions['m_h']) {
                $w_r = $dimensions['w']/$set_dimensions['m_w'];
                $h_r = $dimensions['h']/$set_dimensions['m_h'];
                if ($w_r > $h_r) {
                    $dimensions['w'] = $set_dimensions['m_w'];
                    $dimensions['h'] = $dimensions['h']/$w_r;

                } elseif ($w_r < $h_r) {
                    $dimensions['h'] = $set_dimensions['m_h'];
                    $dimensions['w'] = $dimensions['w']/$h_r;
                } elseif ($w_r == $h_r) {
                    $dimensions['h'] = $set_dimensions['m_h'];
                    $dimensions['w'] = $set_dimensions['m_w'];
                }
            }

            //var_dump('img/'.$data['directory']); die;

            if (!File::exists('img/'.$data['directory'])) {
                $create_directory_result = File::makeDirectory('img/'.$data['directory'], 0775);
            }

            $fname = 'file' . time() . '.' . explode('/', $img->mime)[1]; 
            $img->resize($dimensions['w'], $dimensions['h'])->save('img/'.$data['directory'].'/'.$fname, $quality);
            return $fname;
        }
        return false;
    }

    public static function delete($data, $id = null)
    {
        if (isset($data['directory'])) {
            $fname = $data['name'];
            $path = 'img/'.$data['directory'].'/'.trim($fname);
            if(file_exists($path)) {
                unlink($path);
            }
            return $fname;
        }
        return false;
    }
}