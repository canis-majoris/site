<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
use App\DataTables\LanguagesDataTable;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Language;
use Config;
use Image;

class FileController extends AppController {

    public function upload(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $image_crd = json_decode($data['image_crop_coordinates'], true);
            $img = Image::make($data['image']);
            $img->crop((int)$image_crd['width'], (int)$image_crd['height'], (int)$image_crd['x'], (int)$image_crd['y']);
            $fname = 'file' . time() . '.' . explode('/', $img->mime)[1];
            if($img->save('img/products/'.$fname, 95)) {
                return response()->json(['success' => true, 'status' => 1, 'message' => 'image uploaded', 'newfilename' => $fname]);
            } else 
                return response()->json(['success' => false, 'status' => 0, 'message' => 'could not upload the image', 'newfilename' => $fname]);
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $filename = $data['filename'];
            $directory = $data['directory'];
            $path = 'img/'.$directory.'/'.trim($filename);
            //$img = Image::make('img/products/'.$filename);
            if(file_exists($path)) {
                unlink($path);
                return response()->json(['success' => true, 'status' => 1, 'message' => 'image deleted', 'newfilename' => $filename]);
            } else 
                return response()->json(['success' => false, 'status' => 0, 'message' => 'could not delete the image', 'newfilename' => $filename]);
        }
    }
}